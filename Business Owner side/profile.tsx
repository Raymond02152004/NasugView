import { Ionicons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import * as ImagePicker from 'expo-image-picker';
import React, { useEffect, useState } from 'react';
import {
  ActivityIndicator,
  Dimensions,
  FlatList,
  Image,
  Linking,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View
} from 'react-native';
import MapView, { Marker, UrlTile } from 'react-native-maps';
import Icon from 'react-native-vector-icons/FontAwesome';
import { getItem, STORAGE_KEYS } from '../utils/storage';
import PostCard from './PostCard'; // Adjust path based on your folder structure

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

<View style={{ padding: 16 }}>
  <Text style={{ fontSize: 20, fontWeight: 'bold', marginBottom: 12 }}>Check This Out</Text>

  <FlatList
    data={checkThisOutCards}
    horizontal
    keyExtractor={(item) => item.id.toString()}
    showsHorizontalScrollIndicator={false}
    renderItem={({ item }) => (
      <View style={{
        width: 200,
        marginRight: 16,
        backgroundColor: '#fff',
        borderRadius: 8,
        overflow: 'hidden',
        shadowColor: '#000',
        shadowOpacity: 0.1,
        shadowOffset: { width: 0, height: 2 },
        shadowRadius: 6,
        elevation: 3,
      }}>
        <Image source={item.image} style={{ width: '100%', height: 120 }} />
        <Text style={{ fontSize: 16, fontWeight: 'bold', marginVertical: 4, marginHorizontal: 8 }}>{item.title}</Text>
        <Text style={{ fontSize: 14, color: '#666', marginHorizontal: 8 }}>{item.address}</Text>
        <Text style={{ fontSize: 14, fontWeight: '600', margin: 8 }}>⭐ {item.rating} ({item.reviews} reviews)</Text>
      </View>
    )}
  />
</View>

const screenWidth = Dimensions.get('window').width;
const primaryGreen = '#1D9D65';

interface ProfileData {
  full_name: string;
  profile_img: string | null;
  cover_photo: string | null;
  rating: 4.5, // ✅ default rating
  username: string;


}
const renderStars = (rating: number) => {
  const stars = [];
  const roundedRating = Math.round(rating * 2) / 2; // rounds to nearest 0.5

  for (let i = 1; i <= 5; i++) {
    if (i <= roundedRating) {
      stars.push(
        <Icon key={i} name="star" size={14} color="#FFD700" style={{ marginRight: 2 }} />
      );
    } else if (i - 0.5 === roundedRating) {
      stars.push(
        <Icon key={i} name="star-half-full" size={14} color="#FFD700" style={{ marginRight: 2 }} />
      );
    } else {
      stars.push(
        <Icon key={i} name="star-o" size={14} color="#FFD700" style={{ marginRight: 2 }} />
      );
    }
  }

  return <View style={{ flexDirection: 'row', marginLeft: 5 }}>{stars}</View>;
};



const tabs = ['Posts', 'Info', 'Reviews', 'More like this'];

const Profile = () => {
  const [profile, setProfile] = useState<ProfileData | null>(null);
  const [loading, setLoading] = useState(true);
const [posts, setPosts] = useState<any[]>([]);
  const [newPost, setNewPost] = useState('');

  const [selectedTab, setSelectedTab] = useState('Info');
  const navigation = useNavigation<any>();
const [postImages, setPostImages] = useState<string[]>([]);
const [imageLoading, setImageLoading] = useState<boolean[]>([]);
const handleImageLoad = (index: number) => {
  setImageLoading((prev) => {
    const updated = [...prev];
    updated[index] = false;
    return updated;
  });
};

const handlePickPostImage = async () => {
  const result = await ImagePicker.launchImageLibraryAsync({
    mediaTypes: ImagePicker.MediaTypeOptions.Images,
    allowsMultipleSelection: true,
    quality: 1,
    selectionLimit: 5, // ✅ optional limit
  });

  if (!result.canceled && result.assets.length > 0) {
  const newUris = result.assets.map((asset) => asset.uri);
  setPostImages((prev) => [...prev, ...newUris]);
  setImageLoading((prev) => [...prev, ...new Array(newUris.length).fill(true)]);
}

};



const handleChangeCoverPhoto = async () => {
  const permissionResult = await ImagePicker.requestMediaLibraryPermissionsAsync();
  if (!permissionResult.granted) {
    alert('Permission to access media library is required!');
    return;
  }

  const result = await ImagePicker.launchImageLibraryAsync({
    mediaTypes: ImagePicker.MediaTypeOptions.Images,
    allowsEditing: true,
    aspect: [3, 2],
    quality: 1,
  });

  if (!result.canceled && result.assets && result.assets[0].uri) {
    const selectedImageUri = result.assets[0].uri;

    if (!profile?.username) {
      alert('Profile not loaded. Try again later.');
      return;
    }

    // Create new FormData fresh every time
    const formData = new FormData();

    formData.append('username', profile.username);
    formData.append('cover_photo', {
      uri: selectedImageUri,
      name: 'cover.jpg',
      type: 'image/jpeg',
    } as any);

    try {
      const response = await fetch(
        'http://192.168.0.101/NasugView-Backend/update_cover_photo.php',
        {
          method: 'POST',
          body: formData,
        }
      );

      const data = await response.json();

      if (data.success) {
        // Add cache buster query param to force image refresh
        const timestamp = new Date().getTime();
        setProfile((prev) =>
          prev
            ? {
                ...prev,
                cover_photo: `${data.cover_photo}?t=${timestamp}`, // cache buster
              }
            : null
        );

        alert('Cover photo updated successfully!');

        // Clear image picker cache to prevent Android upload bug
        if (Platform.OS === 'android') {
          ImagePicker.getPendingResultAsync && (await ImagePicker.getPendingResultAsync()); // clears picker state if supported
        }
      } else {
        alert('Failed to update cover photo.');
      }
    } catch (error) {
      console.error('Upload error:', error);
      alert('Error uploading photo.');
    }
  }
};


  const fetchProfile = async () => {
    const userData = await getItem(STORAGE_KEYS.USER_DATA);
    if (!userData || !userData.email) {
      console.warn('No email found in storage.');
      setLoading(false);
      return;
    }

    try {
      const res = await fetch(
        `http://192.168.0.101/NasugView-Backend/get_profile.php?email=${encodeURIComponent(
          userData.email
        )}`
      );
      const data = await res.json();
      if (data.success) {
        setProfile(data.data);
      } else {
        console.warn('Failed to load profile: Server responded with failure.');
      }
    } catch (e) {
      console.error('Fetch error:', e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
  fetchProfile();
}, []);

useEffect(() => {
  if (profile?.username) {
    fetchPosts();
  }
}, [profile]);

const fetchPosts = async () => {
  if (!profile?.username) return;

  try {
    const response = await fetch(
      `http://192.168.0.101/NasugView-Backend/get_posts.php?username=${profile.username}`
    );
    const data = await response.json();

    if (data.success && data.posts) {
      setPosts(data.posts); // ← update posts state
    } else {
      console.warn('No posts found.');
    }
  } catch (error) {
    console.error('Error fetching posts:', error);
  }
};

  if (loading) {
    return (
      <View style={styles.center}>
        <ActivityIndicator size="large" color="#000" />
      </View>
    );
  }

  if (!profile) {
    return (
      <View style={styles.center}>
        <Text>No profile data found.</Text>
      </View>
    );
  }

  return (
    <ScrollView style={{ backgroundColor: '#fff' }}>
      {/* Top Bar */}
      <View style={styles.topBar}>
        <TouchableOpacity
          onPress={() => navigation.goBack()}
          style={{ flexDirection: 'row', alignItems: 'center' }}
        >
          <Ionicons name="chevron-back" size={28} color={primaryGreen} />
          <Text style={styles.backLabel}>Back</Text>
        </TouchableOpacity>
      </View>

      {/* Banner Image */}
     <TouchableOpacity onPress={handleChangeCoverPhoto} activeOpacity={0.9}>
  <View style={{ position: 'relative' }}>
    {profile.cover_photo ? (
<Image
source={{ uri: profile.cover_photo }}
  style={styles.bannerImage}
  resizeMode="cover"
/>


    ) : (
      <View
        style={[
          styles.bannerImage,
          { backgroundColor: '#ccc', justifyContent: 'center', alignItems: 'center' },
        ]}
      >
        <Text style={{ color: '#fff' }}>No cover photo</Text>
      </View>
    )}
    <View style={styles.imageOverlay} />
    <View style={styles.imageTextContainer}>
      <Text style={styles.title}>{profile.full_name}</Text>
      <View style={styles.ratingRow}>
        <Text style={styles.ratingBadge}>4.8 RATING</Text>
        {renderStars(4.8)}
      </View>
      <Text style={styles.addressText}>Brgy. Bucana, Nasugbu, Batangas</Text>
    </View>
  </View>
</TouchableOpacity>


      {/* Tabs */}
      <View style={styles.tabsContainer}>
        {tabs.map((tab) => (
          <TouchableOpacity
            key={tab}
            style={[styles.tabItem]}
            onPress={() => setSelectedTab(tab)}
          >
            <Text
              style={[
                styles.tabText,
                selectedTab === tab && styles.activeTabText,
              ]}
            >
              {tab}
            </Text>
            {selectedTab === tab && <View style={styles.activeIndicator} />}
          </TouchableOpacity>
        ))}
      </View>

      {/* Tab Content */}
      <View style={{ padding: 16 }}>
       {selectedTab === 'Posts' && (
  <ScrollView showsVerticalScrollIndicator={false} style={{ padding: 16 }}>
    {/* Post Input Section */}
    <View style={{
      backgroundColor: '#fff',
      borderRadius: 12,
      padding: 12,
      marginBottom: 16,
      shadowColor: '#000',
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.1,
      shadowRadius: 4,
      elevation: 2,
    }}>
      <View style={{ flexDirection: 'row', alignItems: 'center', marginBottom: 10 }}>
        <Image
          source={{ uri: profile?.cover_photo || 'https://via.placeholder.com/40' }} // ✅ Use cover photo as profile
          style={{ width: 40, height: 40, borderRadius: 20, marginRight: 10 }}
        />
        <Text style={{ fontSize: 16, fontWeight: '500' }}>
          {profile?.full_name ? `What's on your mind, ${profile.full_name}?` : "What's on your mind?"}
        </Text>
      </View>

      <TextInput
        placeholder="Write something..."
        value={newPost}
        onChangeText={setNewPost}
        multiline
        style={{
          borderColor: '#ddd',
          borderWidth: 1,
          borderRadius: 8,
          padding: 10,
          minHeight: 80,
          textAlignVertical: 'top',
          backgroundColor: '#f9f9f9',
        }}
      />

      <TouchableOpacity style={styles.postButton} onPress={handlePickPostImage}>
  <Text style={styles.postButtonText}>Image</Text>
</TouchableOpacity>

<View
  style={{
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 8,
    marginTop: 10,
  }}
>
  {postImages.map((uri, idx) => (
    <View
      key={idx}
      style={{
        width: 100,
        height: 100,
        position: 'relative',
        borderRadius: 8,
        overflow: 'hidden',
        backgroundColor: '#eee',
        justifyContent: 'center',
        alignItems: 'center',
      }}
    >
      {imageLoading[idx] && (
        <View
          style={{
            ...StyleSheet.absoluteFillObject,
            backgroundColor: 'rgba(255,255,255,0.6)',
            justifyContent: 'center',
            alignItems: 'center',
            zIndex: 1,
          }}
        >
          <ActivityIndicator size="small" color="#1D9D65" />
        </View>
      )}
      <Image
        source={{ uri }}
        style={{ width: '100%', height: '100%' }}
        onLoad={() => handleImageLoad(idx)}
          onError={() => console.log(`❌ Failed to load image at index ${idx}: ${uri}`)}
        resizeMode="cover"
      />
    </View>
  ))}
</View>



      <TouchableOpacity
        style={{
          backgroundColor: '#1877F2',
          paddingVertical: 10,
          borderRadius: 8,
          marginTop: 12,
          alignItems: 'center',
        }}
        onPress={async () => {
          if (!newPost.trim() && !postImages) {
            alert('Write something or add a photo');
            return;
          }

          if (!profile?.username) {
            alert('Profile not loaded yet.');
            return;
          }

          const formData = new FormData();
          formData.append('username', profile.username);
          formData.append('content', newPost);

          postImages.forEach((uri, index) => {
            formData.append(`images[]`, {
              uri,
              name: `post_${index}.jpg`,
              type: 'image/jpeg',
            } as any);
          });


          try {
            const res = await fetch('http://192.168.0.101/NasugView-Backend/add_post.php', {
              method: 'POST',
              body: formData,
            });

            const data = await res.json();
            if (data.success) {
              alert('Posted successfully!');
              setNewPost('');
              setPostImages([]);

            setTimeout(() => {
                fetchPosts();
              }, 1000);
            } else {
              alert('Post failed.');
            }
          } catch (error) {
            console.error('Post error:', error);
            alert('Error posting content.');
          }
        }}
      >
        <Text style={{ color: '#fff', fontWeight: 'bold' }}>Post</Text>
      </TouchableOpacity>
    </View>

    {/* Post Feed */}
{posts.map((post, index) => (
  <PostCard
    key={index}
    post={{
      id: index,
      user: post.username,
      caption: post.content,
     images: post.image
  ? post.image.split(',').map((img: string) => {
      // Trim whitespace and double uploads
      let cleaned = img.trim(); // remove any leading/trailing space
      cleaned = cleaned.replace(/^.*\/NasugView-Backend\//, ''); // remove full URL
      cleaned = cleaned.replace(/^uploads\/uploads\//, 'uploads/'); // remove double uploads
      cleaned = cleaned.replace(/^\/?uploads\//, 'uploads/'); // ensure only one uploads/
      
      const finalUri = `http://192.168.0.101/NasugView-Backend/${cleaned}?t=${Date.now()}`;
      console.log('🧼 Cleaned image URI:', finalUri); // ⬅️ Debug log
      return { uri: finalUri };
    })
  : [],




      date: post.created_at || 'Just now',
    }}
  />
))}

  </ScrollView>
)}


        {selectedTab === 'Info' && (
          <>
            <Text style={styles.sectionTitle}>Info</Text>
            <Text>Open from 9:00 AM to 10:00 PM, Monday to Friday</Text>

            <Text style={styles.sectionTitle}>Call</Text>
            <TouchableOpacity style={styles.callButton} onPress={() => Linking.openURL('tel:09123456789')}>
              <Icon name="phone" size={16} color="#fff" />
              <Text style={{ color: '#fff', marginLeft: 8 }}>0912-345-6789</Text>
            </TouchableOpacity>

            <Text style={styles.sectionTitle}>Location</Text>
            <MapView
              style={{ width: '100%', height: 200, borderRadius: 12, marginTop: 8 }}
              initialRegion={{
                latitude: 14.06553,
                longitude: 120.63178,
                latitudeDelta: 0.01,
                longitudeDelta: 0.01,
              }}
            >
              <UrlTile
                urlTemplate="https://c.tile.openstreetmap.org/{z}/{x}/{y}.png"
                maximumZ={19}
                flipY={false}
              />
              <Marker
                coordinate={{ latitude: 14.06553, longitude: 120.63178 }}
                title="Lolas Bulalo"
                description="Brgy. Bucana, Nasugbu, Batangas"
            />
            </MapView>

          </>
        )}

        {selectedTab === 'Reviews' && (
            <>
              <Text style={styles.sectionTitle}>Reviews</Text>
              <View style={styles.reviewCard}>
                <Text style={styles.reviewerName}>Juan Dela Cruz</Text>
                <Text style={styles.reviewText}>Masarap ang bulalo! Sulit sa presyo at mabait ang staff.</Text>
              </View>
              <View style={styles.reviewCard}>
                <Text style={styles.reviewerName}>Maria Santos</Text>
                <Text style={styles.reviewText}>Malinis ang lugar at mabilis ang service. Highly recommended!</Text>
              </View>
            </>

        )}

        {selectedTab === 'More like this' && (
          <Text style={{ marginTop: 8 }}>
            Suggestions for similar local eateries will go here. Soon!
          </Text>
        )}
      </View>
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  center: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  topBar: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 12,
    backgroundColor: '#fff',
  },
  backLabel: {
    fontSize: 14,
    fontWeight: '600',
    color: primaryGreen,
    marginLeft: 4,
  },
  bannerImage: {
    width: screenWidth,
    height: 240,
  },
  imageOverlay: {
    position: 'absolute',
    top: 0,
    left: 0,
    width: screenWidth,
    height: 240,
    backgroundColor: 'rgba(0,0,0,0.25)',
  },
  imageTextContainer: {
    position: 'absolute',
    bottom: 16,
    left: 16,
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#fff',
  },
  addressText: {
    color: '#eee',
    fontSize: 14,
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    marginVertical: 8,
  },
  callButton: {
    flexDirection: 'row',
    backgroundColor: primaryGreen,
    padding: 10,
    borderRadius: 8,
    alignItems: 'center',
    marginVertical: 8,
  },
  tabsContainer: {
    flexDirection: 'row',
    borderBottomWidth: 1,
    borderColor: '#ccc',
    backgroundColor: '#fff',
  },
  tabItem: {
    flex: 1,
    alignItems: 'center',
    paddingVertical: 12,
  },
  tabText: {
    fontSize: 14,
    color: '#555',
  },
  activeTabText: {
    fontWeight: 'bold',
    color: primaryGreen,
  },
  activeIndicator: {
    marginTop: 4,
    height: 3,
    width: '60%',
    backgroundColor: primaryGreen,
    borderRadius: 2,
  },
  reviewCard: {
  backgroundColor: '#f9f9f9',
  padding: 12,
  borderRadius: 10,
  marginBottom: 12,
  shadowColor: '#000',
  shadowOpacity: 0.1,
  shadowOffset: { width: 0, height: 1 },
  shadowRadius: 3,
  elevation: 2,
},
reviewerName: {
  fontWeight: 'bold',
  fontSize: 14,
  marginBottom: 4,
},
reviewText: {
  fontSize: 14,
  color: '#333',
},
ratingRow: {
  flexDirection: 'row',
  alignItems: 'center',
  marginTop: 4,
},

ratingBadge: {
  backgroundColor: '#FFD700',
  color: '#000',
  paddingHorizontal: 8,
  paddingVertical: 2,
  borderRadius: 8,
  fontWeight: 'bold',
  fontSize: 12,
},
postInput: {
  borderWidth: 1,
  borderColor: '#ccc',
  borderRadius: 8,
  padding: 10,
  minHeight: 60,
  marginBottom: 8,
  textAlignVertical: 'top',
},
  container: {
    padding: 15,
    backgroundColor: '#fff',
    flex: 1,
  },
  postBox: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    backgroundColor: '#f0f2f5',
    borderRadius: 10,
    padding: 10,
    marginBottom: 10,
  },
  profilePic: {
    width: 45,
    height: 45,
    borderRadius: 22.5,
    marginRight: 10,
  },
  input: {
    flex: 1,
    minHeight: 40,
    maxHeight: 120,
    paddingTop: 10,
  },
  postButton: {
    backgroundColor: '#1877F2',
    paddingVertical: 10,
    borderRadius: 8,
    alignItems: 'center',
    marginBottom: 15,
  },
  postButtonText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  postCard: {
    backgroundColor: '#fff',
    padding: 15,
    marginBottom: 10,
    borderRadius: 10,
    elevation: 1,
    shadowColor: '#ccc',
    shadowOpacity: 0.1,
    shadowOffset: { width: 0, height: 1 },
  },
  postHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  profilePicSmall: {
    width: 35,
    height: 35,
    borderRadius: 17.5,
    marginRight: 8,
  },
  posterName: {
    fontWeight: 'bold',
    fontSize: 15,
  },
  postContent: {
    fontSize: 15,
  },
postImage: {
  width: '100%',
  height: 200,
  borderRadius: 8,
  marginTop: 10,
},


});

export default Profile;
