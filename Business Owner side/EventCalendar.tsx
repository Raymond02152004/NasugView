import { Ionicons } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import React, { useState } from 'react';
import {
    FlatList,
    Linking,
    Modal,
    Pressable,
    StyleSheet,
    Text,
    TouchableOpacity,
    View,
} from 'react-native';
import { Calendar } from 'react-native-calendars';

// ✅ Sample events
const eventDetails: { [date: string]: { title: string; type: string; time: string; location: string } } = {
  '2025-08-05': {
    title: 'Digital Marketing Seminar',
    type: 'Webinar',
    time: '2:00 PM - 4:00 PM',
    location: 'Zoom',
  },
  '2025-08-15': {
    title: 'Financial Literacy Workshop',
    type: 'In-person',
    time: '10:00 AM - 12:00 PM',
    location: 'Nasugbu Negosyo Center',
  },
  '2025-09-02': {
    title: 'Startup Pitch Day',
    type: 'In-person',
    time: '9:00 AM - 3:00 PM',
    location: 'Taal Convention Hall',
  },
  '2025-10-10': {
    title: 'Women in Business Forum',
    type: 'Webinar',
    time: '1:00 PM - 3:00 PM',
    location: 'Google Meet',
  },
  '2025-07-31': {
    title: 'E-Commerce Bootcamp',
    type: 'Online',
    time: '11:00 AM - 2:00 PM',
    location: 'MS Teams',
  },
};

// ✅ Generate green circled dates
const today = new Date();
const greenDates: { [date: string]: any } = {};
Object.keys(eventDetails).forEach((date) => {
  const eventDate = new Date(date);
  if (eventDate >= today) {
    greenDates[date] = {
      marked: true,
      dotColor: 'white',
      selected: true,
      selectedColor: '#1D9D65',
    };
  }
});

// ✅ Filter future events
const upcomingEvents = Object.keys(eventDetails)
  .filter((date) => new Date(date) >= today)
  .sort()
  .map((date) => ({
    date: new Date(date).toLocaleDateString('en-US', {
      month: 'long',
      day: 'numeric',
      year: 'numeric',
    }),
    title: eventDetails[date].title,
  }));

export default function EventCalendar() {
  const [selectedDate, setSelectedDate] = useState('');
  const [modalVisible, setModalVisible] = useState(false);
  const navigation = useNavigation();

  const handleDayPress = (day: any) => {
    const date = day.dateString;
    if (eventDetails[date]) {
      setSelectedDate(date);
      setModalVisible(true);
    }
  };

  const selectedEvent = eventDetails[selectedDate];

  const handleRegister = () => {
    const formURL = 'https://docs.google.com/forms/d/e/1FAIpQLSdZChAFOlf3xuo9eDnmtC3eFLXGYLcNT1D9cD5Nc26uxHOL-A/viewform?fbclid=IwY2xjawL000dleHRuA2FlbQIxMQABHkhm49BYhRfF8cK86_0M-oCHXEPBKLU9RV_M9WYS06tDL883sdUXqig61Sgu_aem_eLRSfgcV-jr30ys2A8Melg';
    Linking.openURL(formURL);
  };

  return (
    <View style={styles.container}>
      {/* 🔙 Back Button */}
      <TouchableOpacity style={styles.backButton} onPress={() => navigation.goBack()}>
        <Ionicons name="arrow-back" size={26} color="#1D9D65" />
        <Text style={styles.backText}>Back</Text>
      </TouchableOpacity>

      {/* 🟢 Title */}
      <Text style={styles.title}>Negosyo Center Events</Text>

      {/* 📅 Calendar */}
      <Calendar
        markedDates={greenDates}
        onDayPress={handleDayPress}
        theme={{
          selectedDayBackgroundColor: '#1D9D65',
          todayTextColor: '#1D9D65',
          arrowColor: '#1D9D65',
          textDayFontSize: 18,
          textMonthFontSize: 20,
          textDayHeaderFontSize: 16,
        }}
      />

      {/* 🗓 Upcoming Events Header */}
      <Text style={styles.upcomingHeader}>Upcoming Events</Text>

      {/* 📋 List of events */}
      <FlatList
        data={upcomingEvents}
        keyExtractor={(_, index) => index.toString()}
        renderItem={({ item }) => (
          <View style={styles.eventCard}>
            <Text style={styles.eventDate}>{item.date}</Text>
            <Text style={styles.eventTitle}>{item.title}</Text>
          </View>
        )}
        contentContainerStyle={{ paddingBottom: 30 }}
      />

      {/* 📌 Event Modal */}
      <Modal animationType="slide" transparent={true} visible={modalVisible}>
        <View style={styles.modalOverlay}>
          <View style={styles.modalContainer}>
            <Text style={styles.modalTitle}>{selectedEvent?.title}</Text>
            <Text style={styles.modalText}>Event Type: {selectedEvent?.type}</Text>
            <Text style={styles.modalText}>Time: {selectedEvent?.time}</Text>
            <Text style={styles.modalText}>Location: {selectedEvent?.location}</Text>

            <TouchableOpacity style={styles.registerBtn} onPress={handleRegister}>
              <Text style={styles.registerText}>Register</Text>
            </TouchableOpacity>

            <Pressable onPress={() => setModalVisible(false)}>
              <Text style={styles.closeText}>Close</Text>
            </Pressable>
          </View>
        </View>
      </Modal>
    </View>
  );
}

// ✅ Styles
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    padding: 16,
    paddingTop: 50,
  },
  backButton: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
  },
  backText: {
    marginLeft: 8,
    color: '#1D9D65',
    fontSize: 18,
    fontWeight: 'bold',
  },
  title: {
    fontSize: 26,
    fontWeight: 'bold',
    color: '#1D9D65',
    marginBottom: 12,
  },
  upcomingHeader: {
    fontSize: 22,
    fontWeight: 'bold',
    marginTop: 30,
    marginBottom: 16,
    color: '#1D9D65',
  },
  eventCard: {
    backgroundColor: '#F4F4F4',
    borderRadius: 10,
    padding: 14,
    marginBottom: 12,
  },
  eventDate: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1D9D65',
    marginBottom: 6,
  },
  eventTitle: {
    fontSize: 16,
    color: '#333',
  },
  modalOverlay: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0,0,0,0.4)',
  },
  modalContainer: {
    backgroundColor: '#fff',
    padding: 24,
    borderRadius: 12,
    width: '85%',
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 12,
    color: '#1D9D65',
  },
  modalText: {
    fontSize: 16,
    marginBottom: 6,
  },
  registerBtn: {
    marginTop: 18,
    backgroundColor: '#1D9D65',
    paddingVertical: 10,
    borderRadius: 8,
    alignItems: 'center',
  },
  registerText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
  closeText: {
    color: 'gray',
    marginTop: 14,
    fontSize: 15,
    textAlign: 'center',
  },
});
