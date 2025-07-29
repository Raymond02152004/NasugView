import { Ionicons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import React, { useState } from 'react';
import {
  Dimensions,
  Image,
  Linking,
  RefreshControl,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import MapView, { Marker, UrlTile } from 'react-native-maps';
import Icon from 'react-native-vector-icons/FontAwesome';
import { MarketStackParamList } from '../tabs/MarketStackNavigator';

type Props = NativeStackScreenProps<MarketStackParamList, 'BusinessDetails'>;

const screenWidth = Dimensions.get('window').width;
const primaryGreen = '#1D9D65';

export default function BusinessDetailsScreen({ route }: Props) {
  const { business } = route.params;
  const navigation = useNavigation<any>();
  const [refreshing, setRefreshing] = useState(false);
  const [activeTab, setActiveTab] = useState<'Info' | 'Reviews' | 'More'>('Info');
  const [recommend, setRecommend] = useState<string | null>(null);
  const [selectedFilter, setSelectedFilter] = useState<string>('All');

  const handleCall = () => {
    Linking.openURL(`tel:09123456789`);
  };

  const onRefresh = () => {
    setRefreshing(true);
    setTimeout(() => {
      setRefreshing(false);
    }, 1200);
  };

  const handleRecommend = (value: string) => {
    setRecommend(value);
    if (value === 'yes') {
      navigation.navigate('WriteReview', { business });
    } else {
      navigation.goBack();
    }
  };

  const renderStars = (count: number) => (
    <View style={{ flexDirection: 'row' }}>
      {Array.from({ length: 5 }).map((_, i) => (
        <Icon
          key={i}
          name={i < count ? 'star' : 'star-o'}
          size={14}
          color="#FFD700"
          style={{ marginRight: 2 }}
        />
      ))}
    </View>
  );

  const reviews = [
    {
      name: 'Adrian',
      image: require('../../assets/images/bulalo.jpg'),
      stars: 4,
      text: 'Great food and nice staff!',
    },
    {
      name: 'Sheila',
      image: require('../../assets/images/koffie.jpg'),
      stars: 5,
      text: 'Loved the ambience and coffee!',
    },
    {
      name: 'Gee',
      image: require('../../assets/images/store.jpg'),
      stars: 3,
      text: 'Okay experience.',
    },
  ];

  const filteredReviews =
    selectedFilter === 'All'
      ? reviews
      : reviews.filter(r => `${r.stars}★` === selectedFilter);

  return (
    <ScrollView
      style={{ backgroundColor: '#fff' }}
      refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
    >
      <View style={styles.topBar}>
        <TouchableOpacity
          onPress={() => navigation.goBack()}
          style={{ flexDirection: 'row', alignItems: 'center' }}
        >
          <Ionicons name="chevron-back" size={28} color={primaryGreen} style={{ marginRight: 2 }} />
          <Text style={styles.backLabel}>Marketplace</Text>
        </TouchableOpacity>

        <View style={styles.searchContainer}>
          <TextInput placeholder="Search for" placeholderTextColor="#555" style={styles.searchBox} />
          <Icon name="search" size={18} color="#555" style={{ marginLeft: 6 }} />
        </View>
      </View>

      <View style={{ position: 'relative' }}>
        <Image source={business.image} style={styles.bannerImage} />
        <View style={styles.imageOverlay} />
        <View style={styles.imageTextContainer}>
          <Text style={styles.title}>{business.name}</Text>
          <View style={styles.ratingRow}>
            <Text style={styles.ratingBadge}>{business.rating.toFixed(1)} RATING</Text>
            {renderStars(Math.round(business.rating))}
          </View>
          <Text style={styles.addressText}>{business.address}</Text>
        </View>
      </View>

      <View style={styles.tabRow}>
        {['Info', 'Reviews', 'More like this'].map((tabLabel, index) => {
          const key = index === 0 ? 'Info' : index === 1 ? 'Reviews' : 'More';
          return (
            <TouchableOpacity
              key={key}
              onPress={() => setActiveTab(key as any)}
              style={{ flex: 1, alignItems: 'center' }}
            >
              <Text
                style={[
                  styles.tabText,
                  activeTab === key && { fontWeight: 'bold', color: '#000' },
                ]}
              >
                {tabLabel}
              </Text>
              {activeTab === key && <View style={styles.activeUnderline} />}
            </TouchableOpacity>
          );
        })}
      </View>

      <View style={{ height: 1, backgroundColor: '#ddd' }} />

      <View style={{ padding: 16 }}>
        {activeTab === 'Info' && (
          <>
            <Text style={styles.sectionTitle}>Info</Text>
            <Text>Open from 9:00 AM to 10:00 PM, Monday to Friday</Text>

            <Text style={styles.sectionTitle}>Call</Text>
            <TouchableOpacity style={styles.callButton} onPress={handleCall}>
              <Icon name="phone" size={16} color="#fff" />
              <Text style={{ color: '#fff', marginLeft: 8 }}>0912-345-6789</Text>
            </TouchableOpacity>

            <Text style={styles.sectionTitle}>Location</Text>
            <MapView
              style={{ width: '100%', height: 200, borderRadius: 12, marginTop: 8 }}
              initialRegion={{
                latitude: 14.06553,
                longitude: 120.63178,
                latitudeDelta: 0.02,
                longitudeDelta: 0.02,
              }}
            >
              <UrlTile
                urlTemplate="http://c.tile.openstreetmap.org/{z}/{x}/{y}.png"
                maximumZ={19}
                flipY={false}
              />
              <Marker
                coordinate={{ latitude: 14.06553, longitude: 120.63178 }}
                title={business.name}
                description={business.address}
              />
            </MapView>
          </>
        )}

        {activeTab === 'Reviews' && (
          <>
            <Text style={styles.sectionTitle}>Do you recommend this business?</Text>
            <View style={{ flexDirection: 'row', marginBottom: 16 }}>
              <TouchableOpacity
                style={[
                  styles.voteButton,
                  recommend === 'yes' && { backgroundColor: primaryGreen },
                ]}
                onPress={() => handleRecommend('yes')}
              >
                <Text style={{ color: recommend === 'yes' ? '#fff' : '#333' }}>Yes</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={[
                  styles.voteButton,
                  recommend === 'no' && { backgroundColor: '#d9534f' },
                ]}
                onPress={() => handleRecommend('no')}
              >
                <Text style={{ color: recommend === 'no' ? '#fff' : '#333' }}>No</Text>
              </TouchableOpacity>
            </View>

            {/* Ratings Graph */}
            <Text style={styles.sectionTitle}>Ratings Summary</Text>
            {[5, 4, 3, 2, 1].map(stars => {
              const colors = {
                5: '#1D9D65',
                4: '#2FB379',
                3: '#50C98D',
                2: '#79DDA7',
                1: '#A9EFD0',
              };
              return (
                <View key={stars} style={styles.ratingRowGraph}>
                  <Text style={{ width: 30 }}>{stars}</Text>
                  <View style={styles.graphBar}>
                    <View
                      style={[
                        styles.graphFill,
                        {
                          width: `${stars * 20}%`,
                          backgroundColor: colors[stars as keyof typeof colors],
                        },
                      ]}
                    />
                  </View>
                </View>
              );
            })}

            {/* Filter Buttons */}
            <Text style={[styles.sectionTitle, { marginTop: 16 }]}>Filter Reviews</Text>
            <View style={styles.filterRow}>
              {['All', '5★', '4★', '3★', '2★', '1★'].map(filter => (
                <TouchableOpacity
                  key={filter}
                  style={[
                    styles.voteButton,
                    selectedFilter === filter && { backgroundColor: primaryGreen },
                  ]}
                  onPress={() => setSelectedFilter(filter)}
                >
                  <Text
                    style={{
                      color: selectedFilter === filter ? '#fff' : '#333',
                    }}
                  >
                    {filter}
                  </Text>
                </TouchableOpacity>
              ))}
            </View>

            <Text style={[styles.sectionTitle, { marginTop: 16 }]}>Customer Reviews</Text>

            {filteredReviews.map((item, index) => (
              <View key={index} style={styles.reviewCard}>
                <View style={styles.reviewHeader}>
                  <Image source={item.image} style={styles.avatar} />
                  <View style={{ flex: 1 }}>
                    <Text style={styles.reviewerName}>{item.name}</Text>
                    <View style={styles.reviewStars}>{renderStars(item.stars)}</View>
                  </View>
                </View>
                <Text style={styles.reviewText}>{item.text}</Text>
              </View>
            ))}

            {filteredReviews.length === 0 && (
              <Text style={{ color: '#777', fontSize: 14, marginTop: 12 }}>
                No reviews found.
              </Text>
            )}
          </>
        )}

        {activeTab === 'More' && (
          <Text style={{ fontSize: 16, color: '#555' }}>
            You might also like nearby places. (Soon!)
          </Text>
        )}
      </View>
    </ScrollView>
  );
}

// your existing styles untouched, same as your original

const styles = StyleSheet.create({
  topBar: { flexDirection: 'row', alignItems: 'center', padding: 12, backgroundColor: '#fff' },
  backLabel: { fontSize: 14, fontWeight: '600', color: primaryGreen },
  searchContainer: {
    flex: 1,
    flexDirection: 'row',
    borderWidth: 2,
    borderColor: '#ccc',
    borderRadius: 20,
    paddingHorizontal: 14,
    alignItems: 'center',
    marginLeft: 8,
  },
  searchBox: { flex: 1, paddingVertical: 6, color: '#333' },
  bannerImage: { width: screenWidth, height: 240 },
  imageOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    width: screenWidth,
    height: 240,
    backgroundColor: 'rgba(0,0,0,0.25)',
  },
  imageTextContainer: { position: 'absolute', bottom: 16, left: 16 },
  title: { fontSize: 26, fontWeight: 'bold', color: '#fff' },
  ratingRow: { flexDirection: 'row', alignItems: 'center', marginVertical: 6 },
  ratingBadge: {
    backgroundColor: primaryGreen,
    color: '#fff',
    fontSize: 12,
    paddingVertical: 2,
    paddingHorizontal: 6,
    borderRadius: 4,
    marginRight: 8,
  },
  addressText: { color: '#eee', fontSize: 14 },
  tabRow: { flexDirection: 'row', paddingVertical: 14, backgroundColor: '#fff' },
  tabText: { fontSize: 14, color: '#555' },
  activeUnderline: {
    height: 4,
    width: '70%',
    backgroundColor: primaryGreen,
    borderRadius: 8,
    marginTop: 6,
  },
  sectionTitle: { fontSize: 16, fontWeight: 'bold', marginVertical: 8 },
  callButton: {
    flexDirection: 'row',
    backgroundColor: primaryGreen,
    padding: 10,
    borderRadius: 8,
    alignItems: 'center',
    marginVertical: 8,
  },
  ratingRowGraph: { flexDirection: 'row', alignItems: 'center', marginVertical: 4 },
  graphBar: {
    flex: 1,
    height: 8,
    backgroundColor: '#eee',
    borderRadius: 4,
    marginLeft: 8,
    overflow: 'hidden',
  },
  graphFill: { height: '100%' },
  reviewCard: {
    backgroundColor: '#fff',
    borderRadius: 10,
    padding: 14,
    marginBottom: 14,
    elevation: 2,
  },
  reviewHeader: { flexDirection: 'row', alignItems: 'center', marginBottom: 10 },
  avatar: { width: 45, height: 45, borderRadius: 22.5, marginRight: 12 },
  reviewerName: { fontSize: 14, fontWeight: 'bold', marginBottom: 4 },
  reviewStars: { flexDirection: 'row' },
  reviewText: { fontSize: 13, color: '#555', lineHeight: 18, marginTop: 4 },
  voteButton: {
    backgroundColor: '#eee',
    paddingVertical: 6,
    paddingHorizontal: 14,
    borderRadius: 6,
    marginRight: 8,
  },
  thumbRow: { flexDirection: 'row', alignItems: 'center' },
  overallRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 10,
  },
  overallScore: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#333',
  },
  filterRow: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginVertical: 8,
  },
  filterButton: {
    backgroundColor: '#eee',
    paddingVertical: 6,
    paddingHorizontal: 12,
    borderRadius: 20,
    marginRight: 8,
    marginBottom: 8,
  },
  filterButtonText: {
    fontSize: 13,
    color: '#333',
  },
});
