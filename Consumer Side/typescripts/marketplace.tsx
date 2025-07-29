import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useNavigation } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import React, { useEffect, useRef, useState } from 'react';
import {
  ActivityIndicator,
  Image,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import type { RootStackParamList } from '../navigation/StackNavigator';
import { BASE_URL } from '../utils/api';


const categories = ['All', 'Restaurants', 'Clothes', 'Resorts'];

type Business = {
  name: string;
  image_url: string;
  address: string;
  category: string;
  rating: number;
};

export default function Marketplace() {
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const [selectedCategory, setSelectedCategory] = useState('All');
  const [categoryLayouts, setCategoryLayouts] = useState<{ [key: string]: number }>({});
  const categoryScrollRef = useRef<ScrollView>(null);
  const [username, setUsername] = useState('');
  const [businesses, setBusinesses] = useState<Business[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchText, setSearchText] = useState(''); // ✅ NEW

  useEffect(() => {
    fetch(`${BASE_URL}/load_businesses.php`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          setBusinesses(data.businesses);
        }
      })
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  }, []);

  useEffect(() => {
    AsyncStorage.getItem('username').then(value => {
      if (value) {
        setUsername(value);
        console.log('✅ Logged-in user in Marketplace:', value);
      }
    });
  }, []);

  const handleCategoryPress = (cat: string) => {
    setSelectedCategory(cat);
    if (categoryScrollRef.current && categoryLayouts[cat] !== undefined) {
      categoryScrollRef.current.scrollTo({ x: categoryLayouts[cat] - 20, animated: true });
    }
  };

  // ✅ FILTERING
  const filteredBusinesses = businesses
    .filter((biz) => {
      const matchesCategory = selectedCategory === 'All' || biz.category === selectedCategory;
      const matchesSearch = biz.name.toLowerCase().includes(searchText.toLowerCase());
      return matchesCategory && matchesSearch;
    })
    .sort((a, b) => b.rating - a.rating);

  return (
    <View style={styles.container}>
      {/* Top Bar */}
      <View style={styles.topBar}>
        <Image source={require('../../assets/images/logo.png')} style={styles.logo} />
        <View style={styles.searchContainer}>
          <Ionicons name="search" size={18} color="gray" />
          <TextInput
            placeholder="Search for"
            style={styles.searchInput}
            value={searchText}
            onChangeText={setSearchText} // ✅ Bind input
          />
        </View>
      </View>

      {/* Categories */}
      <ScrollView
        ref={categoryScrollRef}
        horizontal
        showsHorizontalScrollIndicator={false}
        contentContainerStyle={styles.categoryScroll}
      >
        {categories.map((cat) => (
          <TouchableOpacity
            key={cat}
            style={[styles.categoryButton, selectedCategory === cat && styles.categoryActive]}
            onPress={() => handleCategoryPress(cat)}
            onLayout={(e) => {
              const x = e.nativeEvent.layout.x;
              setCategoryLayouts((prev) => ({ ...prev, [cat]: x }));
            }}
          >
            <Text
              style={[styles.categoryText, selectedCategory === cat && styles.categoryTextActive]}
            >
              {cat}
            </Text>
          </TouchableOpacity>
        ))}
      </ScrollView>

      {/* Business Cards */}
      {loading ? (
        <View style={{ marginTop: 30, alignItems: 'center' }}>
          <ActivityIndicator size="large" color="#004225" />
        </View>
      ) : (
        <ScrollView contentContainerStyle={styles.cardsWrapper}>
          {filteredBusinesses.length > 0 ? (
            filteredBusinesses.map((biz, index) => (
              <TouchableOpacity
                key={index}
                style={styles.card}
                onPress={() =>
                  navigation.navigate('BusinessDetails', {
                    name: biz.name,
                    image: biz.image_url,
                    address: biz.address,
                    username: username,
                  })
                }
              >
                <Image source={{ uri: biz.image_url }} style={styles.cardImage} />
                <View style={styles.cardContent}>
                  <Text style={styles.cardTitle}>{biz.name}</Text>
                  <Text style={styles.cardAddress}>{biz.address}</Text>
                  <View style={styles.ratingRow}>
                    {[...Array(5)].map((_, i) => (
                      <Ionicons
                        key={i}
                        name={
                          i < Math.floor(biz.rating)
                            ? 'star'
                            : i < Math.ceil(biz.rating)
                            ? 'star-half'
                            : 'star-outline'
                        }
                        size={16}
                        color="#FFD700"
                      />
                    ))}
                    <Text style={styles.ratingText}>{biz.rating.toFixed(1)}</Text>
                  </View>
                </View>
              </TouchableOpacity>
            ))
          ) : (
            <View style={styles.noResult}>
              <Text style={styles.noResultText}>No businesses found.</Text>
            </View>
          )}
        </ScrollView>
      )}
    </View>
  );
}



const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    paddingTop: 30,
  },
  topBar: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 15,
    marginBottom: 15,
  },
  logo: {
    width: 100,
    height: 30,
  },
  searchContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#eee',
    marginLeft: 10,
    flex: 1,
    borderRadius: 20,
    paddingHorizontal: 10,
  },
  searchInput: {
    padding: 8,
    flex: 1,
  },
  categoryScroll: {
    paddingHorizontal: 10,
    paddingBottom: 5,
  },
  categoryButton: {
    borderColor: '#ccc',
    borderWidth: 1,
    height: 40,
    paddingHorizontal: 20,
    borderRadius: 20,
    marginRight: 10,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 10,
  },
  categoryActive: {
    backgroundColor: '#004225',
    borderColor: '#004225',
  },
  categoryText: {
    color: '#000',
    fontSize: 14,
  },
  categoryTextActive: {
    color: '#fff',
    fontWeight: 'bold',
  },
  cardsWrapper: {
    paddingHorizontal: 15,
    paddingBottom: 20,
    marginTop: 10,
  },
  card: {
    marginBottom: 15,
    backgroundColor: '#f9f9f9',
    borderRadius: 10,
    overflow: 'hidden',
  },
  cardImage: {
    width: '100%',
    height: 160,
  },
  cardContent: {
    padding: 10,
  },
  cardTitle: {
    fontWeight: 'bold',
    fontSize: 16,
  },
  cardAddress: {
    fontSize: 13,
    color: '#555',
    marginTop: 4,
  },
  ratingRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 6,
  },
  ratingText: {
    marginLeft: 6,
    fontSize: 13,
    color: '#444',
  },
  noResult: {
    alignItems: 'center',
    marginTop: 50,
  },
  noResultText: {
    fontSize: 16,
    color: '#999',
  },
});
