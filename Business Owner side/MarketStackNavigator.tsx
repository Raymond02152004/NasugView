import { createNativeStackNavigator } from '@react-navigation/native-stack';
import React from 'react';
import BusinessDetailsScreen from '../tabs/BusinessDetails';
import MarketplaceScreen from '../tabs/marketplace';
import WriteReviewScreen from '../tabs/WriteReview'; // ✅ added import

export type MarketStackParamList = {
  MarketplaceHome: undefined;
  BusinessDetails: { business: any };
  WriteReview: { business: any }; // ✅ added screen type definition
};

const MarketStack = createNativeStackNavigator<MarketStackParamList>();

export default function MarketStackNavigator() {
  return (
    <MarketStack.Navigator>
      <MarketStack.Screen
        name="MarketplaceHome"
        component={MarketplaceScreen}
        options={{ headerShown: false }}
      />
      <MarketStack.Screen
        name="BusinessDetails"
        component={BusinessDetailsScreen}
        options={{ headerShown: false }}
      />
      <MarketStack.Screen
        name="WriteReview"
        component={WriteReviewScreen}
        options={{ headerShown: false }}
      />

    </MarketStack.Navigator>
  );
}
