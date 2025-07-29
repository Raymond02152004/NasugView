import { BottomTabNavigationProp } from '@react-navigation/bottom-tabs';
import { CompositeNavigationProp, useNavigation } from '@react-navigation/native';
import { NativeStackNavigationProp } from '@react-navigation/native-stack';
import React from 'react';
import { ScrollView, StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';
import { MoreStackParamList, RootStackParamList, TabParamList } from '../types';

type NotificationsScreenNavigationProp = CompositeNavigationProp<
  NativeStackNavigationProp<RootStackParamList, 'Tabs'>,
  CompositeNavigationProp<
    BottomTabNavigationProp<TabParamList, 'More'>,
    NativeStackNavigationProp<MoreStackParamList>
  >
>;

export default function Notifications() {
  const navigation = useNavigation<NotificationsScreenNavigationProp>();

  return (
    <ScrollView style={styles.container}>
      <Text style={styles.header}>Notifications</Text>

      {/* Today Section */}
      <Text style={styles.sectionTitle}>Today</Text>

      {/* Events Notification */}
      <View style={styles.notificationCard}>
        <View style={styles.cardHeader}>
          <Icon name="calendar" size={18} color="#1D9D65" />
          <Text style={styles.cardTitle}> Events</Text>
          <View style={styles.dot} />
          <Text style={styles.timeText}>23 min</Text>
        </View>
        <Text style={styles.cardMessage}>
          Don’t miss out! A new webinar event is just around the corner. Take this opportunity to
          gain valuable insights and skills.
        </Text>
        <TouchableOpacity onPress={() => navigation.navigate('EventCalendar')}>
  <Text style={styles.linkText}>Open Calendar</Text>
</TouchableOpacity>

      </View>

      {/* Reminder Notification */}
      <View style={styles.notificationItem}>
        <View style={styles.itemHeader}>
          <Icon name="bell-o" size={18} color="#1D9D65" />
          <Text style={styles.itemTitle}> Reminder!</Text>
          <Text style={styles.timeText}>2 hr</Text>
        </View>
        <Text style={styles.itemMessage}>
          You’ve got a training/webinar event to attend on April 12, 2025 at 4PM. See you there!
        </Text>
        <TouchableOpacity>
          <Text style={styles.linkText}>See Details</Text>
        </TouchableOpacity>
      </View>

      {/* Yesterday Section */}
      <Text style={styles.sectionTitle}>Yesterday</Text>

      <View style={styles.notificationItem}>
        <View style={styles.itemHeader}>
          <Icon name="bell-o" size={18} color="#1D9D65" />
          <Text style={styles.itemTitle}> Reminder!</Text>
          <Text style={styles.timeText}>21 hr</Text>
        </View>
        <Text style={styles.itemMessage}>
          You’ve got a training/webinar event to attend on April 12, 2025 at 4PM. See you there!
        </Text>
        <TouchableOpacity>
          <Text style={styles.linkText}>See Details</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff', padding: 16 },
  header: { fontSize: 22, fontWeight: 'bold', marginBottom: 16 },
  sectionTitle: { fontSize: 16, fontWeight: 'bold', marginTop: 16, marginBottom: 8 },
  notificationCard: {
    backgroundColor: '#F6F6F6',
    padding: 14,
    borderRadius: 10,
    marginBottom: 14,
  },
  cardHeader: { flexDirection: 'row', alignItems: 'center' },
  cardTitle: { fontSize: 16, fontWeight: 'bold', marginLeft: 6 },
  dot: {
    width: 8,
    height: 8,
    backgroundColor: 'red',
    borderRadius: 4,
    marginLeft: 6,
  },
  timeText: { marginLeft: 'auto', color: '#888', fontSize: 12 },
  cardMessage: { marginTop: 6, fontSize: 14, color: '#333', marginBottom: 10 },
  linkText: { color: '#1D9D65', fontWeight: '500', marginTop: 4 },
  notificationItem: { marginBottom: 14 },
  itemHeader: { flexDirection: 'row', alignItems: 'center' },
  itemTitle: { fontSize: 16, fontWeight: 'bold', marginLeft: 6 },
  itemMessage: { marginTop: 6, fontSize: 14, color: '#333', marginBottom: 4 },
});
