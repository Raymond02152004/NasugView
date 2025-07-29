import { Ionicons } from '@expo/vector-icons';
import { NativeStackScreenProps } from '@react-navigation/native-stack';
import React, { useState } from 'react';
import {
    Alert,
    Dimensions,
    Image,
    ScrollView,
    StyleSheet,
    Text,
    TextInput,
    TouchableOpacity,
    View,
} from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';
import { MarketStackParamList } from '../tabs/MarketStackNavigator';

type Props = NativeStackScreenProps<MarketStackParamList, 'WriteReview'>;

const screenWidth = Dimensions.get('window').width;
const primaryGreen = '#1D9D65';

export default function WriteReviewScreen({ route, navigation }: Props) {
  const { business } = route.params;
  const [reviewText, setReviewText] = useState('');
  const [rating, setRating] = useState(0);

  const handleSubmit = () => {
    if (!reviewText.trim() || rating === 0) {
      Alert.alert('Error', 'Please write a review and select a rating.');
      return;
    }

    Alert.alert('Thank you!', 'Your review has been submitted.');
    // ✅ you could dispatch to context/backend here later

    navigation.navigate('MarketplaceHome');
  };

  return (
    <ScrollView style={{ backgroundColor: '#fff' }}>
      <View style={styles.header}>
        <TouchableOpacity onPress={() => navigation.goBack()} style={{ flexDirection: 'row', alignItems: 'center' }}>
          <Ionicons name="chevron-back" size={28} color={primaryGreen} />
          <Text style={styles.backLabel}>Back</Text>
        </TouchableOpacity>
      </View>

      <Image source={business.image} style={styles.bannerImage} />
      <View style={{ padding: 16 }}>
        <Text style={styles.title}>Review {business.name}</Text>

        <Text style={styles.label}>Your Rating</Text>
        <View style={{ flexDirection: 'row', marginVertical: 12 }}>
          {[1, 2, 3, 4, 5].map(star => (
            <TouchableOpacity key={star} onPress={() => setRating(star)}>
              <Icon
                name={star <= rating ? 'star' : 'star-o'}
                size={30}
                color={star <= rating ? '#FFD700' : '#ccc'}
                style={{ marginRight: 8 }}
              />
            </TouchableOpacity>
          ))}
        </View>

        <Text style={styles.label}>Your Review</Text>
        <TextInput
          multiline
          placeholder="Write something about your experience..."
          value={reviewText}
          onChangeText={setReviewText}
          style={styles.textArea}
        />

        <TouchableOpacity style={styles.submitButton} onPress={handleSubmit}>
          <Text style={{ color: '#fff', fontSize: 16 }}>Submit Review</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  header: { flexDirection: 'row', alignItems: 'center', padding: 12 },
  backLabel: { fontSize: 14, fontWeight: '600', color: primaryGreen, marginLeft: 4 },
  bannerImage: { width: screenWidth, height: 220, resizeMode: 'cover' },
  title: { fontSize: 22, fontWeight: 'bold', marginBottom: 16 },
  label: { fontSize: 16, fontWeight: '500', marginTop: 12 },
  textArea: {
    backgroundColor: '#f9f9f9',
    height: 120,
    borderRadius: 8,
    padding: 12,
    marginTop: 8,
    textAlignVertical: 'top',
    fontSize: 14,
  },
  submitButton: {
    backgroundColor: primaryGreen,
    paddingVertical: 14,
    borderRadius: 8,
    marginTop: 20,
    alignItems: 'center',
  },
});
