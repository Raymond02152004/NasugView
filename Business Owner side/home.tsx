import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import React, { useCallback, useState } from 'react';
import {
  Dimensions,
  RefreshControl,
  ScrollView,
  StatusBar,
  StyleSheet,
  Text,
  View
} from 'react-native';
import TopBar from '../tabs/TopBar'; // ✅ import your TopBar component
import { RootStackParamList } from '../types';
import CheckThisOut from './CheckThisOut';
import PostCard from './PostCard';

const screenWidth = Dimensions.get('window').width;
const primaryGreen = '#1D9D65';

type HomeScreenNavigationProp = NativeStackNavigationProp<RootStackParamList>;

export default function Home() {
  const [searchText, setSearchText] = useState('');
  const [refreshing, setRefreshing] = useState(false);
  const navigation = useNavigation<HomeScreenNavigationProp>();

  const checkThisOutCards = [
    {
      id: 1,
      title: 'Berna Beach Resort',
      rating: 4.5,
      reviews: 15,
      address: 'Brgy. Bucana, Nasugbu, Batangas',
      image: require('../../assets/images/3.png'),
    },
    {
      id: 2,
      title: 'Bulalo Point',
      rating: 4.8,
      reviews: 21,
      address: 'Tagaytay-Nasugbu Hwy',
      image: require('../../assets/images/Bulalo5.webp'),
    },
    {
      id: 3,
      title: 'Seaside Cafe',
      rating: 4.6,
      reviews: 18,
      address: 'Nasugbu Boulevard',
      image: require('../../assets/images/bulalo.jpeg'),
    },
    {
      id: 4,
      title: 'Sunset Deck',
      rating: 4.9,
      reviews: 12,
      address: 'Fortune Island Road',
      image: require('../../assets/images/3.png'),
    },
    {
      id: 5,
      title: 'Luna’s Grill',
      rating: 4.4,
      reviews: 19,
      address: 'J.P. Laurel St.',
      image: require('../../assets/images/Bulalo5.webp'),
    },
    {
      id: 6,
      title: 'Casa Blanca',
      rating: 4.7,
      reviews: 25,
      address: 'Kaybiang Tunnel Exit',
      image: require('../../assets/images/bulalo.jpeg'),
    },
  ];

  const initialPosts = [
    {
      id: 1,
      user: 'Lindsay A.',
      caption: '10/10 for me! Must try!',
      date: 'June 29, 2025',
      images: [
        require('../../assets/images/Bulalo5.webp'),
        require('../../assets/images/bulalo.jpeg'),
        require('../../assets/images/3.png'),
      ],
    },
    {
      id: 2,
      user: 'Sheila G.',
      caption: 'Beach trip snaps 🏖️',
      date: 'June 28, 2025',
      images: [
        require('../../assets/images/3.png'),
        require('../../assets/images/Bulalo5.webp'),
      ],
    },
    {
      id: 3,
      user: 'Jaymee Z.',
      caption: 'Grabe ang sarap ng food dito!! 💯🔥',
      date: 'June 27, 2025',
      images: [
        require('../../assets/images/bulalo.jpeg'),
        require('../../assets/images/3.png'),
      ],
    },
  ];

  const [posts, setPosts] = useState(initialPosts);

  const shufflePosts = (array: typeof initialPosts) =>
    [...array].sort(() => Math.random() - 0.5);

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    setTimeout(() => {
      setPosts(shufflePosts(initialPosts));
      setRefreshing(false);
    }, 1000);
  }, []);

  const filteredPosts = posts.filter(
    (post) =>
      post.user.toLowerCase().includes(searchText.toLowerCase()) ||
      post.caption.toLowerCase().includes(searchText.toLowerCase())
  );

  return (
    <>
      <StatusBar barStyle="dark-content" backgroundColor="#E0E0E0" />
      <ScrollView
        style={styles.container}
        refreshControl={<RefreshControl refreshing={refreshing} onRefresh={onRefresh} />}
      >
        {/* ✅ using TopBar component */}
        <TopBar searchText={searchText} onSearchTextChange={setSearchText} />

        <Text style={styles.sectionTitle}>Check this out!</Text>
        <CheckThisOut cards={checkThisOutCards} />

        <View style={{ paddingBottom: 20 }}>
          {filteredPosts.map((post) => (
            <PostCard key={post.id} post={post} />
          ))}
        </View>
      </ScrollView>
    </>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff' },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: primaryGreen,
    padding: 12,
  },
});
