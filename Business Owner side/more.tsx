import AsyncStorage from '@react-native-async-storage/async-storage';
import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import React, { useEffect, useState } from 'react';
import {
  Alert,
  Image,
  RefreshControl,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';
import { RootStackParamList } from '../_layout';

const primaryGreen = '#1D9D65';
const SERVER_URL = 'http://192.168.0.101/NasugView-Backend';

type RootStackNavigation = NativeStackNavigationProp<RootStackParamList>;

export default function More() {
  const navigation = useNavigation<RootStackNavigation>();
  const [user, setUser] = useState<{
    fullName: string;
    username: string;
    coverPhoto: string | null;
  } | null>(null);
  const [refreshing, setRefreshing] = useState(false);

  const fetchUser = async () => {
    try {
      const userData = await AsyncStorage.getItem('userData');

      if (!userData) {
        setUser(null);
        return;
      }

      const parsed = JSON.parse(userData);
      const email = parsed.email;

      const response = await fetch(`${SERVER_URL}/get_profile.php?email=${encodeURIComponent(email)}`);
      const result = await response.json();

      if (result.success && result.data) {
        const data = result.data;

        setUser({
          fullName: data.name || parsed.fullName || parsed.name || 'No name',
          username: data.username || parsed.username || 'No username',
          coverPhoto: data.cover_photo || parsed.coverPhoto || null,
        });
      } else {
        // fallback to local user data
        setUser({
          fullName: parsed.fullName || parsed.name || 'No name',
          username: parsed.username || 'No username',
          coverPhoto: parsed.coverPhoto || null,
        });
      }
    } catch (error) {
      console.error('Error fetching user from backend:', error);
    }
  };

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchUser();
    setRefreshing(false);
  };

  useEffect(() => {
    fetchUser();
  }, []);

  const handleLogout = () => {
    Alert.alert('Logout', 'Are you sure you want to logout?', [
      { text: 'Cancel', style: 'cancel' },
      {
        text: 'Logout',
        onPress: async () => {
          await AsyncStorage.removeItem('userData');
          navigation.replace('Login');
        },
        style: 'destructive',
      },
    ]);
  };

  const handleMenuPress = (label: string) => {
    switch (label) {
      case 'Edit Profile':
        break;
      case 'Event Calendar':
        navigation.navigate('EventCalendar');
        break;
      case 'Likes':
      case 'Favorites':
      case 'Reviews':
      case 'Language':
      case 'Settings':
        Alert.alert(label, `You tapped ${label}`);
        break;
      default:
        Alert.alert('Coming Soon', `Feature "${label}" is not yet available.`);
    }
  };

  return (
    <ScrollView
      style={styles.container}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }
    >
      <TouchableOpacity
        style={styles.profileContainer}
        onPress={() => navigation.navigate('Profile')}
      >
        <View style={styles.imageWrapper}>
          {user?.coverPhoto ? (
            <Image source={{ uri: user.coverPhoto }} style={styles.profileImage} />
          ) : (
            <View
              style={[
                styles.profileImage,
                {
                  backgroundColor: '#ccc',
                  justifyContent: 'center',
                  alignItems: 'center',
                },
              ]}
            >
              <Text style={{ color: '#fff', fontSize: 24 }}>?</Text>
            </View>
          )}
        </View>

        <View style={styles.profileText}>
          <Text style={styles.profileName}>
            {user?.fullName || 'Loading Name...'}
          </Text>
          <Text style={styles.username}>@{user?.username || 'username'}</Text>
        </View>
      </TouchableOpacity>

      <View style={styles.divider} />

      {menuItems.map((item, index) => (
        <TouchableOpacity
          key={index}
          style={styles.menuItem}
          onPress={() =>
            item.label === 'Logout' ? handleLogout() : handleMenuPress(item.label)
          }
        >
          <View style={styles.menuLeft}>
            <Icon name={item.icon} size={20} color={item.color} style={{ width: 26 }} />
            <Text style={styles.menuLabel}>{item.label}</Text>
          </View>
          <Icon name="chevron-right" size={16} color="#333" />
        </TouchableOpacity>
      ))}
    </ScrollView>
  );
}

const menuItems = [
  { label: 'Edit Profile', icon: 'edit', color: primaryGreen },
  { label: 'Likes', icon: 'heart', color: '#E91E63' },
  { label: 'Favorites', icon: 'bookmark', color: primaryGreen },
  { label: 'Reviews', icon: 'star', color: primaryGreen },
  { label: 'Event Calendar', icon: 'calendar', color: primaryGreen },
  { label: 'Language', icon: 'globe', color: primaryGreen },
  { label: 'Settings', icon: 'cog', color: primaryGreen },
  { label: 'Logout', icon: 'sign-out', color: primaryGreen },
];

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
  },
  profileContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 20,
    marginTop: 20,
  },
  imageWrapper: {
    position: 'relative',
    width: 70,
    height: 70,
    marginRight: 16,
  },
  profileImage: {
    width: 70,
    height: 70,
    borderRadius: 50,
  },
  profileText: {
    justifyContent: 'center',
  },
  profileName: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#222',
  },
  username: {
    fontSize: 14,
    color: '#555',
    marginTop: 4,
  },
  divider: {
    height: 1,
    backgroundColor: '#ccc',
    marginHorizontal: 20,
    marginVertical: 10,
  },
  menuItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 14,
    paddingHorizontal: 20,
    justifyContent: 'space-between',
  },
  menuLeft: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  menuLabel: {
    fontSize: 16,
    color: '#333',
    marginLeft: 12,
  },
});
