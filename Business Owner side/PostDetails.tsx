import { useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import React, { useState } from 'react';
import {
  Dimensions,
  Image,
  NativeScrollEvent,
  NativeSyntheticEvent,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import { RootStackParamList } from '../_layout';

const screenWidth = Dimensions.get('window').width;
const primaryGreen = '#1D9D65';

export default function Home() {
  const [activeIndex, setActiveIndex] = useState(0);
  const navigation = useNavigation<NativeStackNavigationProp<RootStackParamList>>();

  const handleScroll = (event: NativeSyntheticEvent<NativeScrollEvent>) => {
    const slide = Math.ceil(event.nativeEvent.contentOffset.x / (200 + 12));
    setActiveIndex(slide);
  };

  const postData = [
    {
      id: 1,
      user: 'Lindsey G.',
      caption: "10/10 for me!!!! Y'all should try this!!",
      images: [
        require('../../assets/images/Bulalo5.webp'),
        require('../../assets/images/bulalo.jpeg'),
        require('../../assets/images/3.png'),
      ],
    },
    {
      id: 2,
      user: 'Sheila A.',
      caption: 'Best bulalo I’ve had this year!',
      images: [
        require('../../assets/images/bulalo.jpeg'),
        require('../../assets/images/3.png'),
        require('../../assets/images/bulalo.jpeg'),
      ],
    },
    {
      id: 3,
      user: 'Jam M.',
      caption: 'Solid spot for late-night cravings!',
      images: [
        require('../../assets/images/3.png'),
        require('../../assets/images/bulalo.jpeg'),
        require('../../assets/images/3.png'),
      ],
    },
  ];

  return (
    <ScrollView style={styles.container}>
      {/* Top Logo and Search */}
      <View style={styles.topBar}>
        <Image source={require('../../assets/images/logo.png')} style={styles.logo} />
        <TextInput
          placeholder="Search for..."
          placeholderTextColor="#555"
          style={styles.searchBox}
        />
      </View>

      {/* Section Title */}
      <Text style={styles.sectionTitle}>Check this out!</Text>

      {/* Horizontal Cards */}
      <ScrollView
        horizontal
        showsHorizontalScrollIndicator={false}
        onScroll={handleScroll}
        scrollEventThrottle={16}
        decelerationRate="fast"
        snapToInterval={200 + 12}
        snapToAlignment="start"
        contentContainerStyle={{ paddingRight: 12 }}
      >
        {[1, 2, 3, 4, 5].map((_, index) => (
          <View key={index} style={styles.card}>
            <Image source={require('../../assets/images/bulalo.jpeg')} style={styles.cardImage} />
            <Text style={styles.cardTitle}>Sample Place {index + 1}</Text>
            <Text style={styles.cardSubtitle}>📍 Nasugbu, Batangas</Text>
          </View>
        ))}
      </ScrollView>

      {/* Pagination dots */}
      <View style={styles.pagination}>
        {[0, 1, 2, 3, 4].map((_, i) => (
          <View
            key={i}
            style={[
              styles.dot,
              {
                width: activeIndex === i ? 12 : 8,
                height: activeIndex === i ? 12 : 8,
                backgroundColor: activeIndex === i ? primaryGreen : '#ccc',
              },
            ]}
          />
        ))}
      </View>

      {/* Scrollable Posts */}
      <View style={{ paddingBottom: 20 }}>
        {postData.map((post) => (
          <TouchableOpacity
            key={post.id}
            style={styles.postCard}
            onPress={() => navigation.navigate('PostDetails', { post })}
          >
            <View style={styles.postImages}>
              <Image source={post.images[0]} style={styles.postMainImage} />
              <View style={styles.postSubImages}>
                <Image source={post.images[1]} style={styles.postSubImage} />
                <Image source={post.images[2]} style={styles.postSubImage} />
              </View>
            </View>
            <View style={styles.postFooter}>
              <Text style={styles.userText}>{post.user} ⭐️</Text>
              <Text style={styles.captionText}>{post.caption}</Text>
              <View style={styles.iconsRow}>
                <Text style={styles.icon}>❤️</Text>
                <Text style={styles.icon}>💬</Text>
                <Text style={styles.icon}>🔖</Text>
              </View>
            </View>
          </TouchableOpacity>
        ))}
      </View>
    </ScrollView>
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
  logo: { width: 160, height: 70, resizeMode: 'contain' },
  searchBox: {
    flex: 1,
    borderWidth: 2,
    borderColor: '#ccc',
    borderRadius: 20,
    paddingHorizontal: 12,
    paddingVertical: 8,
    marginLeft: 10,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: primaryGreen,
    padding: 12,
  },
  card: {
    width: 190,
    marginLeft: 12,
    borderRadius: 12,
    overflow: 'hidden',
    borderWidth: 2,
    borderColor: '#ddd',
  },
  cardImage: { width: '100%', height: 150 },
  cardTitle: { fontSize: 16, fontWeight: 'bold', padding: 8 },
  cardSubtitle: {
    fontSize: 12,
    color: '#555',
    paddingHorizontal: 8,
    paddingBottom: 8,
  },
  pagination: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginVertical: 10,
  },
  dot: {
    borderRadius: 50,
    marginHorizontal: 4,
  },
  postCard: {
    margin: 12,
    backgroundColor: '#fff',
    borderRadius: 12,
    borderWidth: 1,
    borderColor: '#ddd',
    overflow: 'hidden',
  },
  postImages: {
    flexDirection: 'row',
  },
  postMainImage: {
    flex: 2,
    height: 150,
    borderRightWidth: 4,
    borderColor: '#ddd',
  },
  postSubImages: {
    flex: 1,
    flexDirection: 'column',
  },
  postSubImage: {
    flex: 1,
    height: 75,
    borderBottomWidth: 4,
    borderColor: '#ddd',
  },
  postFooter: {
    padding: 12,
  },
  userText: {
    fontWeight: 'bold',
    fontSize: 14,
    marginBottom: 6,
  },
  captionText: {
    fontSize: 14,
    marginBottom: 10,
  },
  iconsRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  icon: {
    fontSize: 20,
  },
});
