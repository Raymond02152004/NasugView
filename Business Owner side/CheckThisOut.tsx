import React, { useState } from 'react';
import {
  Dimensions,
  Image,
  NativeScrollEvent,
  NativeSyntheticEvent,
  ScrollView,
  StyleSheet,
  Text,
  View,
} from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';

const screenWidth = Dimensions.get('window').width;

type Card = {
  id: number;
  title: string;
  rating: number;
  reviews: number;
  address: string;
  image: any;
};

type CheckThisOutProps = {
  cards: Card[];
};

export default function CheckThisOut({ cards }: CheckThisOutProps) {
  const [activeIndex, setActiveIndex] = useState(0);

  const renderStars = (rating: number) => {
    const stars = [];
    for (let i = 1; i <= 5; i++) {
      stars.push(
        <Icon
          key={i}
          name={rating >= i ? 'star' : rating >= i - 0.5 ? 'star-half-full' : 'star-o'}
          size={14}
          color="#FFD700"
          style={{ marginRight: 2 }}
        />
      );
    }
    return <View style={{ flexDirection: 'row' }}>{stars}</View>;
  };

  const handleScroll = (event: NativeSyntheticEvent<NativeScrollEvent>) => {
    const slide = Math.round(event.nativeEvent.contentOffset.x / (150 + 12));
    setActiveIndex(slide);
  };

  return (
    <>
      <ScrollView
        horizontal
        onScroll={handleScroll}
        scrollEventThrottle={16}
        decelerationRate="fast"
        snapToInterval={150 + 12}
        snapToAlignment="start"
        contentContainerStyle={{ paddingRight: 12 }}
        showsHorizontalScrollIndicator={false}
      >
        {cards.map((card) => (
          <View key={card.id} style={styles.card}>
            <Image source={card.image} style={styles.cardImage} />
            <View style={styles.cardTextBlock}>
              <Text style={styles.placeTitle}>{card.title}</Text>
              <View style={styles.ratingLine}>
                {renderStars(card.rating)}
                <Text style={{ fontSize: 12, color: '#777', marginLeft: 4 }}>
                  {card.rating.toFixed(1)}
                </Text>
                <Text style={{ fontSize: 12, color: '#777', marginLeft: 4 }}>
                  {card.reviews} Reviews
                </Text>
              </View>
              <Text style={styles.placeAddress}>{card.address}</Text>
            </View>
          </View>
        ))}
      </ScrollView>

      <View style={styles.pagination}>
        {cards.map((_, i) => {
          const distance = Math.abs(i - activeIndex);
          let dotSize = 4;
          let dotColor = '#ccc';

          if (distance === 0) {
            dotSize = 10;
            dotColor = '#1D9D65';
          } else if (distance === 1) {
            dotSize = 8;
          } else if (distance === 2) {
            dotSize = 6;
          }

          return (
            <View
              key={`dot-${i}`}
              style={{
                width: dotSize,
                height: dotSize,
                borderRadius: 50,
                backgroundColor: dotColor,
                marginHorizontal: 3,
              }}
            />
          );
        })}
      </View>
    </>
  );
}

const styles = StyleSheet.create({
  card: {
    width: 150,
    marginLeft: 12,
    borderRadius: 12,
    overflow: 'hidden',
    borderWidth: 2,
    borderColor: '#fff',
    backgroundColor: '#fff',
  },
  cardImage: {
    width: '100%',
    height: 110,
  },
  cardTextBlock: {
    padding: 8,
  },
  placeTitle: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#333',
  },
  ratingLine: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 2,
    flexWrap: 'wrap',
  },
  placeAddress: {
    fontSize: 11,
    color: '#555',
  },
  pagination: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginVertical: 8,
  },
});
