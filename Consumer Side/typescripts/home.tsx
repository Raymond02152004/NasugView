import { Ionicons } from '@expo/vector-icons';
import { useNavigation, useRoute } from '@react-navigation/native';
import type { NativeStackNavigationProp } from '@react-navigation/native-stack';
import React, { useCallback, useEffect, useState } from 'react';
import {
  Image,
  NativeScrollEvent,
  NativeSyntheticEvent,
  RefreshControl,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from 'react-native';
import ImageViewing from 'react-native-image-viewing';
import type { RootStackParamList } from '../navigation/StackNavigator';
import { BASE_URL } from '../utils/api';


type Review = {
  name: string;
  date: string;
  profile?: string; // optional in case profile is not used
  text: string;
  images: string[];
};


const featuredCards = [
  {
    image: require('../../assets/images/berna.png'),
    name: 'BernaBeach Resort',
    location: 'Brgy. Bucana, Nasugbu, Batangas',
    rating: 4.9,
  },
  {
    image: require('../../assets/images/unli.jpg'),
    name: 'Len Wings',
    location: 'Brgy. Wawa, Nasugbu, Batangas',
    rating: 4.5,
  },
  {
    image: require('../../assets/images/bulalo.jpg'),
    name: 'Bulalohan sa Kanto',
    location: 'Brgy. 10, Nasugbu, Batangas',
    rating: 4.8,
  },
  {
    image: require('../../assets/images/pendong.jpg'),
    name: 'Pendong By Rance',
    location: 'Concepcion St, Nasugbu, Batangas',
    rating: 4.6,
  },
];

export default function Home() {
  const [reviews, setReviews] = useState<Review[]>([]);
  const [likedMap, setLikedMap] = useState<{ [key: string]: boolean }>({});
  const [likeCountMap, setLikeCountMap] = useState<{ [key: string]: number }>({});
  const [refreshing, setRefreshing] = useState(false);
  const [activeIndex, setActiveIndex] = useState(0);
  const [isViewerVisible, setIsViewerVisible] = useState(false);
  const [viewerImages, setViewerImages] = useState<{ uri: string }[]>([]);
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();
  const route = useRoute();
  const username = (route.params as { username?: string })?.username;

  const handleScroll = (event: NativeSyntheticEvent<NativeScrollEvent>) => {
    const scrollX = event.nativeEvent.contentOffset.x;
    const cardWidth = 200 + 15;
    const index = Math.floor(scrollX / cardWidth + 0.5);
    setActiveIndex(index);
  };

  const toggleHeart = (name: string) => {
    setLikedMap((prev) => ({
      ...prev,
      [name]: !prev[name],
    }));
    setLikeCountMap((prev) => ({
      ...prev,
      [name]: (prev[name] || 0) + (likedMap[name] ? -1 : 1),
    }));
  };

  const onImagePress = (imageIndex: number, reviewIndex: number) => {
    const selectedReview = reviews[reviewIndex];
    const formattedImages = selectedReview.images.map((uri) => ({ uri }));
    setViewerImages(formattedImages);
    setCurrentImageIndex(imageIndex);
    setIsViewerVisible(true);
  };

  const fetchReviews = async () => {
    try {
      const response = await fetch(`${BASE_URL}/load_post.php`);
      const data = await response.json();
      if (data.success) {
        const parsed = data.posts.map((post: any) => ({
        name: post.name,
        date: post.date,
        text: post.text,
        profile: post.profile,
        images: post.images,
      }));

        setReviews(parsed);
      } else {
        setReviews([]);
      }
    } catch (error) {
      console.error('Error fetching reviews:', error);
    }
  };

  useEffect(() => {
    fetchReviews();
  }, []);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    fetchReviews().finally(() => setRefreshing(false));
  }, []);



useEffect(() => {
  // console.log('Fetched reviews:', reviews);
}, [reviews]);

  return (
    <>
      <ScrollView
        style={styles.container}
        contentContainerStyle={{ paddingBottom: 20 }}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
      >
        {username && <Text style={styles.welcomeMessage}>Welcome, {username}!</Text>}

        <View style={styles.topBar}>
          <Image source={require('../../assets/images/logo.png')} style={styles.logo} />
         <TouchableOpacity style={styles.searchBar} onPress={() => navigation.navigate('Marketplace')}>
  <Text style={styles.searchInput}>Search for ...</Text>
  <Ionicons name="search" size={16} color="#888" style={styles.searchIcon} />
</TouchableOpacity>

        </View>

        <Text style={styles.sectionTitle}>Check this out!</Text>

        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          snapToInterval={215}
          decelerationRate="fast"
          snapToAlignment="start"
          contentContainerStyle={{ paddingLeft: 10, paddingRight: 20 }}
          onScroll={handleScroll}
          scrollEventThrottle={16}
        >
          {featuredCards.map((item, index) => (
            <TouchableOpacity
              key={index}
              style={styles.featuredCard}
              onPress={() =>
                navigation.navigate('BusinessDetails', {
                  name: item.name,
                  image: Image.resolveAssetSource(item.image).uri,
                  address: item.location,
                  username: username ?? '',
                })
              }
            >
              <Image source={item.image} style={styles.featuredImage} />
              <View style={styles.cardTextRow}>
                <Text style={styles.featuredName}>{item.name}</Text>
                <View style={styles.ratingRow}>
                  <Ionicons name="star" size={14} color="#FFD700" />
                  <Text style={styles.ratingText}>{item.rating}</Text>
                </View>
              </View>
              <Text style={styles.featuredLocation}>{item.location}</Text>
            </TouchableOpacity>
          ))}
        </ScrollView>

        {/* Reviews Section */}
        {reviews.map((review, reviewIndex) => (
          <View key={reviewIndex} style={styles.reviewCard}>
            <View style={styles.reviewImages}>
              {review.images.map((img, imgIndex) => (
                <TouchableOpacity key={imgIndex} onPress={() => onImagePress(imgIndex, reviewIndex)}>
                  <Image source={{ uri: img }} style={styles.reviewImage} />
                </TouchableOpacity>
              ))}
            </View>

            <View style={styles.reviewMeta}>
              <View style={styles.reviewHeader}>
                        <Image
          source={{ uri: review.profile }}
          style={styles.profilePic}
        />
                <View>
                  <Text style={styles.reviewName}>{review.name}</Text>
                  <Text style={styles.reviewDate}>{review.date}</Text>
                </View>
              </View>

              <Text style={styles.reviewText}>{review.text}</Text>

              <View style={styles.iconRow}>
                <TouchableOpacity onPress={() => toggleHeart(review.name)} style={styles.heartRow}>
                  <Ionicons
                    name={likedMap[review.name] ? 'heart' : 'heart-outline'}
                    size={20}
                    color={likedMap[review.name] ? 'red' : '#333'}
                  />
                  <Text style={styles.likeCount}>{likeCountMap[review.name] || 0}</Text>
                </TouchableOpacity>

                <Ionicons name="chatbubble-outline" size={20} color="#333" style={styles.icon} />
                <Ionicons name="share-social-outline" size={20} color="#333" style={styles.icon} />
              </View>
            </View>
          </View>
        ))}
      </ScrollView>

      <ImageViewing
        images={viewerImages}
        imageIndex={currentImageIndex}
        visible={isViewerVisible}
        onRequestClose={() => setIsViewerVisible(false)}
      />
    </>
  );
}


const styles = StyleSheet.create({
  container: {
    flex: 1,
    paddingTop: 10,
    backgroundColor: '#fff',
  },
  topBar: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 20,
  },
  logo: {
    width: 100,
    height: 70,
    resizeMode: 'contain',
  },
  searchBar: {
    flex: 1,
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#f0f0f0',
    borderRadius: 20,
    paddingHorizontal: 10,
    height: 34,
    marginLeft: 10,
    justifyContent: 'space-between',
  },
  searchInput: {
    flex: 1,
    fontSize: 14,
    paddingVertical: 0,
    paddingRight: 8,
  },
  searchIcon: {
    marginLeft: 6,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#008000',
    marginHorizontal: 20,
    marginTop: 5,
    marginBottom: 10,
  },
  featuredCard: {
    backgroundColor: '#fff',
    borderRadius: 10,
    marginRight: 15,
    width: 200,
    borderWidth: 1,
    borderColor: '#ccc',
    overflow: 'hidden',
  },
  featuredImage: {
    width: 200,
    height: 100,
    borderTopLeftRadius: 10,
    borderTopRightRadius: 10,
  },
  cardTextRow: {
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    marginHorizontal: 5,
    marginTop: 5,
  },
  featuredName: {
    fontWeight: '600',
    fontSize: 14,
  },
  ratingRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  ratingText: {
    fontSize: 13,
    color: '#333',
    marginLeft: 3,
  },
  featuredLocation: {
    fontSize: 12,
    color: '#555',
    marginHorizontal: 5,
    marginBottom: 5,
  },
  reviewCard: {
    backgroundColor: '#fff',
    margin: 20,
    padding: 10,
    borderRadius: 10,
    elevation: 2,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.1,
    shadowRadius: 2,
    marginBottom: 2,
  },
  reviewImages: {
    width: '100%',   
    marginBottom: 10,
  },
  reviewImage: {
  width: '100%',
  height: 200,
  borderRadius: 10,
  resizeMode: 'cover',
},

  reviewMeta: {
    paddingHorizontal: 5,
  },
  reviewHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 5,
  },
  profilePic: {
    width: 35,
    height: 35,
    borderRadius: 50,
    marginRight: 10,
  },
  reviewName: {
    fontWeight: 'bold',
    fontSize: 14,
  },
  reviewDate: {
    fontSize: 12,
    color: '#777',
  },
  reviewText: {
    fontSize: 14,
    marginVertical: 10,
  },
  iconRow: {
    flexDirection: 'row',
    justifyContent: 'flex-start',
  },
  icon: {
    marginRight: 15,
  },
  heartRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginRight: 15,
  },
  likeCount: {
    fontSize: 13,
    color: '#333',
    marginLeft: 3,
  },
  welcomeMessage: {
  fontSize: 16,
  fontWeight: '600',
  color: '#004225',
  marginBottom: 6,
  marginLeft: 20,
},


});
