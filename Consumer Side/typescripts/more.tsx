import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useFocusEffect } from '@react-navigation/native';
import React, { useCallback, useState } from 'react';
import {
  Modal,
  Pressable,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';

export default function MoreScreen({ navigation }: any) {
  const [modalVisible, setModalVisible] = useState(false);
  const [username, setUsername] = useState('');

  useFocusEffect(
    useCallback(() => {
      const loadUsername = async () => {
        const storedUsername = await AsyncStorage.getItem('username');
        if (storedUsername) {
          setUsername(storedUsername);
        }
      };
      loadUsername();
    }, [])
  );

  const confirmLogout = () => {
    setModalVisible(false);
    navigation.replace('Login');
  };

  const goToProfile = () => {
    navigation.navigate('Profile');
  };

  const goToEventCalendar = () => {
    navigation.navigate('EventCalendar');
  };

  const goToUreviews = () => {
    if (username) {
      navigation.navigate('Ureviews', { username });
    } else {
      alert('Username not found.');
    }
  };

  return (
    <View style={styles.container}>
      {/* 👤 Profile Option */}
      <TouchableOpacity style={styles.option} onPress={goToProfile}>
        <Ionicons name="person-circle-outline" size={24} color="#008000" style={styles.icon} />
        <Text style={styles.optionText}>Profile</Text>
      </TouchableOpacity>

      {/* ❤️ Likes */}
      <TouchableOpacity style={styles.option}>
        <Ionicons name="heart-outline" size={24} color="#008000" style={styles.icon} />
        <Text style={styles.optionText}>Likes</Text>
      </TouchableOpacity>

      {/* 🔖 Favorites */}
      <TouchableOpacity style={styles.option}>
        <Ionicons name="bookmark-outline" size={24} color="#008000" style={styles.icon} />
        <Text style={styles.optionText}>Favorites</Text>
      </TouchableOpacity>

      {/* 🌟 Reviews */}
      <TouchableOpacity style={styles.option} onPress={goToUreviews}>
        <Ionicons name="star-outline" size={24} color="#008000" style={styles.icon} />
        <Text style={styles.optionText}>Reviews</Text>
      </TouchableOpacity>

      {/* 🗓️ Event Calendar */}
      <TouchableOpacity style={styles.option} onPress={goToEventCalendar}>
        <Ionicons name="calendar-outline" size={24} color="#008000" style={styles.icon} />
        <Text style={styles.optionText}>Event Calendar</Text>
      </TouchableOpacity>

      {/* 🌐 Language */}
      <TouchableOpacity style={styles.option}>
        <Ionicons name="globe-outline" size={24} color="#008000" style={styles.icon} />
        <Text style={styles.optionText}>Language</Text>
      </TouchableOpacity>

      {/* ⚙️ Settings */}
      <TouchableOpacity style={styles.option}>
        <Ionicons name="settings-outline" size={24} color="#008000" style={styles.icon} />
        <Text style={styles.optionText}>Settings</Text>
      </TouchableOpacity>

      {/* 🔓 Logout */}
      <TouchableOpacity
        style={styles.logoutButton}
        onPress={() => setModalVisible(true)}
      >
        <Ionicons name="log-out-outline" size={20} color="#fff" style={styles.icon} />
        <Text style={styles.logoutText}>Logout</Text>
      </TouchableOpacity>

      {/* 🚪 Logout Modal */}
      <Modal
        animationType="fade"
        transparent
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalOverlay}>
          <View style={styles.modalContent}>
            <Text style={styles.modalTitle}>Logout</Text>
            <Text style={styles.modalMessage}>Are you sure you want to log out?</Text>
            <View style={styles.modalButtons}>
              <Pressable
                style={[styles.modalButton, styles.cancelButton]}
                onPress={() => setModalVisible(false)}
              >
                <Text style={styles.cancelText}>Cancel</Text>
              </Pressable>
              <Pressable
                style={[styles.modalButton, styles.yesButton]}
                onPress={confirmLogout}
              >
                <Text style={styles.yesText}>Yes</Text>
              </Pressable>
            </View>
          </View>
        </View>
      </Modal>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    padding: 20,
    justifyContent: 'flex-start',
    backgroundColor: '#fff',
  },
  option: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 15,
    borderBottomWidth: 1,
    borderColor: '#eee',
  },
  optionText: {
    fontSize: 16,
    color: '#008000',
  },
  icon: {
    marginRight: 15,
  },
  logoutButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: 'green',
    paddingVertical: 12,
    paddingHorizontal: 20,
    borderRadius: 10,
    marginTop: 30,
  },
  logoutText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.35)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  modalContent: {
    width: '90%',
    backgroundColor: '#fff',
    borderRadius: 14,
    padding: 25,
    shadowColor: '#000',
    shadowOpacity: 0.25,
    shadowRadius: 10,
    elevation: 10,
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: '700',
    marginBottom: 10,
    color: '#27ae60',
    textAlign: 'center',
  },
  modalMessage: {
    fontSize: 16,
    color: '#333',
    textAlign: 'center',
    marginBottom: 25,
  },
  modalButtons: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  modalButton: {
    flex: 1,
    marginHorizontal: 5,
    paddingVertical: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  cancelButton: {
    backgroundColor: '#eee',
  },
  cancelText: {
    color: '#555',
    fontSize: 16,
    fontWeight: '600',
  },
  yesButton: {
    backgroundColor: 'green',
  },
  yesText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
});
