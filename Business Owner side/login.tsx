import { NativeStackScreenProps } from '@react-navigation/native-stack';
import React, { useState } from 'react';
import {
  Alert,
  Image,
  SafeAreaView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View
} from 'react-native';
import type { RootStackParamList } from '../types';
import { STORAGE_KEYS, storeItem } from '../utils/storage';

type Props = NativeStackScreenProps<RootStackParamList, 'Login'>;

export default function Login({ navigation }: Props) {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const handleLogin = async () => {
    if (!username || !password) {
      Alert.alert('Please fill in both fields.');
      return;
    }

    try {
      const response = await fetch('http://192.168.0.101/NasugView-Backend/login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });

      const result = await response.json();

      if (result.success) {
        Alert.alert('Success', result.message);

        const userData = {
          fullName: result.user?.fullName || '',
          username: result.user?.username || username,
          profilePicture: result.user?.profilePicture || '',
          email: result.user?.email || '', // ✅ ADD THIS LINE
        };

        console.log('userData before storing:', userData);
        await storeItem(STORAGE_KEYS.USER_DATA, userData);

        navigation.reset({
          index: 0,
          routes: [{ name: 'Tabs' }],
        });

      } else {
        Alert.alert('Login Failed', result.message);
      }
    } catch (error) {
      console.error('Login error:', error);
      Alert.alert('Error', 'Unable to connect to server.');
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerText}>Hello!</Text>
        <Text style={styles.subText}>Welcome to NasugView</Text>
      </View>

      <View style={styles.formContainer}>
       <Image
  source={require('../../assets/images/logo.png')}
  style={styles.logo}
/>

        <TextInput
          placeholder="Username"
          placeholderTextColor="#888"
          value={username}
          onChangeText={setUsername}
          style={styles.input}
        />
        <TextInput
          placeholder="Password"
          placeholderTextColor="#888"
          value={password}
          onChangeText={setPassword}
          secureTextEntry
          style={styles.input}
        />

        <TouchableOpacity style={styles.loginButton} onPress={handleLogin}>
          <Text style={styles.loginButtonText}>Login</Text>
        </TouchableOpacity>

        <View style={styles.signUpRow}>
          <Text style={{ color: '#555' }}>Don’t have an account yet? </Text>
          <TouchableOpacity onPress={() => navigation.navigate('Signup')}>
            <Text style={styles.signUpText}>Sign Up</Text>
          </TouchableOpacity>
        </View>
      </View>
    </SafeAreaView>
  );
}

const primaryGreen = '#1D9D65';

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: primaryGreen },
  header: { flex: 1, justifyContent: 'center', paddingLeft: 25 },
  headerText: { fontSize: 36, color: '#fff', fontWeight: 'bold' },
  subText: { fontSize: 18, color: '#fff', marginTop: 8 },
  formContainer: {
    flex: 3,
    backgroundColor: '#fff',
    borderTopLeftRadius: 30,
    borderTopRightRadius: 30,
    padding: 25,
    alignItems: 'center',
  },
  loginTitle: { fontSize: 24, fontWeight: 'bold', color: primaryGreen, marginBottom: 20 },
  input: {
    width: '100%',
    borderWidth: 1,
    borderColor: '#ccc',
    backgroundColor: '#f9f9f9',
    color: '#333',
    paddingVertical: 12,
    paddingHorizontal: 15,
    borderRadius: 10,
    marginBottom: 15,
    fontSize: 16,
  },
  loginButton: {
    backgroundColor: primaryGreen,
    paddingVertical: 14,
    borderRadius: 10,
    width: '100%',
    alignItems: 'center',
    marginBottom: 20,
  },
  loginButtonText: { color: '#fff', fontSize: 18, fontWeight: '600' },
  signUpRow: { flexDirection: 'row', alignItems: 'center', marginTop: 5 },
  signUpText: { color: primaryGreen, fontWeight: 'bold' },

  logo: {
  width: 200,
  height: 200,
  marginBottom: -50, // Small space to "dikit" it sa TextInput
  resizeMode: 'contain',
    marginTop: -50, // Small space to "dikit" it sa TextInput

},

});
