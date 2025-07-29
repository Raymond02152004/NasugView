import React, { useState } from 'react';
import {
  FlatList,
  Image,
  ImageSourcePropType,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';

const primaryGreen = '#1D9D65';

type Review = {
  id: string;
  user: string;
  rating: number;
  comment: string;
  images?: ImageSourcePropType[];
};

const reviewsData: Review[] = [
  {
    id: '1',
    user: 'g******e',
    rating: 5,
    comment: 'Masarap Dito',
    images: [
      require('../../assets/images/bulalo.jpg'),
      require('../../assets/images/koffie.jpg'),
    ],
  },
  {
    id: '2',
    user: 's*******e',
    rating: 3.5,
    comment: 'Ang tabang ng ibang food, pero masarap naman ang bulalo',
  },
];

export default function BusinessReviewsScreen() {
  const [selectedFilter, setSelectedFilter] = useState('All');
  const [recommend, setRecommend] = useState<string | null>(null);

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

  const renderReviewItem = ({ item }: { item: Review }) => (
    <View style={styles.reviewItem}>
      <View style={{ flexDirection: 'row', alignItems: 'center' }}>
        <Image
          source={require('../../assets/images/store.jpg')}
          style={styles.userAvatar}
        />
        <Text style={styles.userName}>{item.user}</Text>
      </View>
      <View style={{ marginVertical: 4 }}>{renderStars(item.rating)}</View>
      <Text style={styles.comment}>{item.comment}</Text>
      {item.images && (
        <View style={styles.imageRow}>
          {item.images.map((img, idx) => (
            <Image key={idx} source={img} style={styles.reviewImage} />
          ))}
        </View>
      )}
    </View>
  );

  return (
    <ScrollView style={styles.container}>
      {/* Tabs */}
      <View style={styles.tabs}>
        <Text style={[styles.tab, styles.activeTab]}>Info</Text>
        <Text style={[styles.tab, { borderBottomColor: primaryGreen }]}>Reviews</Text>
        <Text style={styles.tab}>More like this</Text>
      </View>

      {/* Recommend */}
      <Text style={styles.recommendLabel}>Do you recommend this business?</Text>
      <View style={styles.recommendBtns}>
        <TouchableOpacity
          style={[
            styles.recommendBtn,
            recommend === 'Yes' && styles.activeRecommendBtn,
          ]}
          onPress={() => setRecommend('Yes')}
        >
          <Text
            style={[
              styles.recommendText,
              recommend === 'Yes' && { color: 'white' },
            ]}
          >
            Yes
          </Text>
        </TouchableOpacity>
        <TouchableOpacity
          style={[
            styles.recommendBtn,
            recommend === 'No' && styles.activeRecommendBtn,
          ]}
          onPress={() => setRecommend('No')}
        >
          <Text
            style={[
              styles.recommendText,
              recommend === 'No' && { color: 'white' },
            ]}
          >
            No
          </Text>
        </TouchableOpacity>
      </View>

      {/* Overall Ratings */}
      <View style={styles.ratings}>
        <Text style={styles.sectionTitle}>Reviews</Text>
        <Text style={styles.overallScore}>4.5</Text>
        <View style={{ flexDirection: 'row', alignItems: 'center', marginVertical: 6 }}>
          {renderStars(4.5)}
          <Text style={{ marginLeft: 8, color: '#777' }}>2.5k Reviews</Text>
        </View>

        {[5, 4, 3, 2, 1].map(score => (
          <View key={score} style={styles.barRow}>
            <Text>{score}</Text>
            <View style={styles.ratingBarContainer}>
              <View
                style={[
                  styles.ratingBar,
                  { width: score === 5 ? '90%' : score === 4 ? '70%' : score === 3 ? '30%' : '10%' },
                ]}
              />
            </View>
          </View>
        ))}
      </View>

      {/* Filters */}
      <View style={styles.filters}>
        {['All', 'Good Service', 'Excellent Quality'].map(filter => (
          <TouchableOpacity
            key={filter}
            onPress={() => setSelectedFilter(filter)}
            style={[
              styles.filterBtn,
              selectedFilter === filter && styles.activeFilterBtn,
            ]}
          >
            <Text
              style={[
                styles.filterText,
                selectedFilter === filter && { color: 'white' },
              ]}
            >
              {filter}
            </Text>
          </TouchableOpacity>
        ))}
      </View>

      {/* Reviews */}
      <FlatList
        data={reviewsData}
        renderItem={renderReviewItem}
        keyExtractor={item => item.id}
        scrollEnabled={false}
        contentContainerStyle={{ paddingHorizontal: 16, paddingBottom: 20 }}
      />

      {/* See More */}
      <TouchableOpacity style={styles.seeMore}>
        <Text style={{ color: primaryGreen, fontWeight: 'bold' }}>See More Reviews</Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff' },
  tabs: { flexDirection: 'row', padding: 14, justifyContent: 'space-around' },
  tab: { fontSize: 15, color: '#555', paddingBottom: 4 },
  activeTab: {
    borderBottomWidth: 2,
    borderBottomColor: primaryGreen,
    color: primaryGreen,
    fontWeight: 'bold',
  },
  recommendLabel: { marginLeft: 16, marginTop: 12, color: '#333' },
  recommendBtns: { flexDirection: 'row', marginTop: 8, marginLeft: 16 },
  recommendBtn: {
    borderWidth: 1,
    borderColor: '#ccc',
    borderRadius: 8,
    paddingVertical: 5,
    paddingHorizontal: 18,
    marginRight: 10,
  },
  activeRecommendBtn: { backgroundColor: primaryGreen, borderColor: primaryGreen },
  recommendText: { color: '#555' },
  ratings: { padding: 16 },
  sectionTitle: { fontSize: 17, fontWeight: 'bold' },
  overallScore: { fontSize: 28, color: primaryGreen, marginTop: 6 },
  barRow: { flexDirection: 'row', alignItems: 'center', marginVertical: 4 },
  ratingBarContainer: {
    flex: 1,
    backgroundColor: '#ddd',
    borderRadius: 10,
    overflow: 'hidden',
    height: 6,
    marginLeft: 8,
  },
  ratingBar: { backgroundColor: primaryGreen, height: '100%' },
  filters: { flexDirection: 'row', paddingHorizontal: 16, marginBottom: 10 },
  filterBtn: {
    borderWidth: 1,
    borderColor: primaryGreen,
    borderRadius: 20,
    paddingVertical: 5,
    paddingHorizontal: 12,
    marginRight: 8,
  },
  activeFilterBtn: { backgroundColor: primaryGreen },
  filterText: { fontSize: 13, color: primaryGreen },
  reviewItem: {
    marginBottom: 14,
    backgroundColor: '#f8f8f8',
    padding: 12,
    borderRadius: 10,
  },
  userAvatar: { width: 36, height: 36, borderRadius: 18, marginRight: 10 },
  userName: { fontWeight: 'bold', color: '#333' },
  comment: { color: '#333', marginTop: 4 },
  imageRow: { flexDirection: 'row', marginTop: 6 },
  reviewImage: { width: 60, height: 60, borderRadius: 8, marginRight: 6 },
  seeMore: { alignItems: 'center', marginBottom: 20 },
});
