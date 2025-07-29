import { useNavigation } from '@react-navigation/native';
import React, { useCallback, useState } from 'react';
import {
  Dimensions,
  Image,
  ImageSourcePropType,
  RefreshControl,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';

const primaryGreen = '#1D9D65';
const screenWidth = Dimensions.get('window').width;

type Business = {
  id: string;
  name: string;
  category: string;
  image: ImageSourcePropType;
  rating: number;
  address: string;
};

const categories = ['All', 'Restaurants', 'Beach Resorts', 'Coffee Shops', 'Stores'];

const businesses: Business[] = [
  {
    id: '1',
    name: 'Cora RTW Store',
    category: 'Stores',
    image: require('../../assets/images/store.jpg'),
    rating: 4.5,
    address: 'Brgy. 10, Nasugbu, Batangas',
  },
  {
    id: '2',
    name: "Bulalo Point",
    category: 'Restaurants',
    image: require('../../assets/images/bulalo.jpg'),
    rating: 4.8,
    address: 'Brgy. Bucana, Nasugbu, Batangas',
  },
  {
    id: '3',
    name: 'Koffie Kiosk',
    category: 'Coffee Shops',
    image: require('../../assets/images/koffie.jpg'),
    rating: 4.6,
    address: 'Brgy. Bucana, Nasugbu, Batangas',
  },
  {
    id: '4',
    name: 'Berna Beach Resort',
    category: 'Beach Resorts',
    image: require('../../assets/images/berna.jpg'),
    rating: 4.5,
    address: 'Nasugbu, Batangas',
  },
];

export default function MarketplaceScreen() {
  const [selectedCategory, setSelectedCategory] = useState<string>('All');
  const [searchText, setSearchText] = useState<string>('');
  const [refreshing, setRefreshing] = useState(false);
  const navigation = useNavigation<any>();

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    setTimeout(() => {
      setRefreshing(false);
    }, 1000);
  }, []);

  const filteredBusinesses =
    selectedCategory === 'All'
      ? businesses
      : businesses.filter(b => b.category === selectedCategory);

  const renderStars = (rating: number) => {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating - fullStars >= 0.5;
    const stars = [];

    for (let i = 0; i < fullStars; i++) {
      stars.push(<Icon key={`full-${i}`} name="star" size={14} color="#ffc107" />);
    }
    if (hasHalfStar) {
      stars.push(<Icon key="half" name="star-half-empty" size={14} color="#ffc107" />);
    }
    const remaining = 5 - stars.length;
    for (let i = 0; i < remaining; i++) {
      stars.push(<Icon key={`empty-${i}`} name="star-o" size={14} color="#ccc" />);
    }
    return <View style={{ flexDirection: 'row' }}>{stars}</View>;
  };

  return (
    <>
      <StatusBar barStyle="dark-content" backgroundColor="#E0E0E0" />
      <ScrollView
        style={styles.container}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
      >
        {/* TopBar */}
        <View style={styles.topBar}>
          <Image source={require('../../assets/images/logo.png')} style={styles.logo} />
          <View style={styles.searchContainer}>
            <TextInput
              placeholder="Search for"
              placeholderTextColor="#555"
              style={styles.searchBox}
              value={searchText}
              onChangeText={setSearchText}
            />
            <Icon name="search" size={18} color="#555" style={styles.searchIcon} />
          </View>
        </View>

        {/* Categories */}
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={styles.categoryContainer}
        >
          {categories.map(category => (
            <TouchableOpacity
              key={category}
              onPress={() => setSelectedCategory(category)}
              style={[
                styles.categoryButton,
                selectedCategory === category && styles.activeCategoryButton,
              ]}
            >
              <Text
                style={[
                  styles.categoryText,
                  selectedCategory === category && styles.activeCategoryText,
                ]}
              >
                {category}
              </Text>
            </TouchableOpacity>
          ))}
        </ScrollView>

        {/* Business Cards */}
        <View style={styles.cardGrid}>
          {filteredBusinesses.map(item => (
            <TouchableOpacity
              key={item.id}
              style={styles.card}
              onPress={() => navigation.navigate('BusinessDetails', { business: item })}
            >


              <Image source={item.image} style={styles.cardImage} />
              <View style={styles.cardContent}>
                <Text style={styles.cardTitle}>{item.name}</Text>
                <View style={{ flexDirection: 'row', alignItems: 'center', marginVertical: 2 }}>
                  {renderStars(item.rating)}
                  <Text style={{ fontSize: 12, color: '#777', marginLeft: 4 }}>
                    {item.rating.toFixed(1)}
                  </Text>
                </View>
                <Text style={styles.address}>{item.address}</Text>
              </View>
            </TouchableOpacity>
          ))}
        </View>
      </ScrollView>
    </>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff' },

  topBar: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 12,
    marginTop: 10,
  },
  logo: {
    width: 160,
    height: 70,
    resizeMode: 'contain',
  },
  searchContainer: {
    flex: 1,
    flexDirection: 'row',
    borderWidth: 2,
    borderColor: '#ccc',
    borderRadius: 20,
    paddingHorizontal: 12,
    alignItems: 'center',
    marginLeft: 8,
  },
  searchBox: {
    flex: 1,
    paddingVertical: 6,
    color: '#333',
  },
  searchIcon: {
    marginLeft: 6,
  },

  categoryContainer: {
    flexDirection: 'row',
    paddingVertical: 8,
    paddingLeft: 12,
    marginBottom: 12,
  },
  categoryButton: {
    borderWidth: 1,
    borderColor: '#ccc',
    paddingHorizontal: 12,
    paddingVertical: 5,
    borderRadius: 20,
    marginRight: 8,
    backgroundColor: '#f9f9f9',
  },
  activeCategoryButton: {
    backgroundColor: primaryGreen,
    borderColor: primaryGreen,
  },
  categoryText: {
    fontSize: 13,
    color: '#333',
  },
  activeCategoryText: {
    color: 'white',
  },

  cardGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'space-between',
    paddingHorizontal: 12,
    paddingBottom: 16,
  },
  card: {
    width: (screenWidth - 36) / 2,
    backgroundColor: '#fff',
    borderRadius: 12,
    overflow: 'hidden',
    marginBottom: 12,
    elevation: 3,
  },
  cardImage: { width: '100%', height: 130 },
  cardContent: { padding: 10 },
  cardTitle: { fontSize: 15, fontWeight: 'bold', marginBottom: 2 },
  address: { color: '#666', fontSize: 12 },
});
