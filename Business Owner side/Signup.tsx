import { NativeStackScreenProps } from '@react-navigation/native-stack';
import React, { useState } from 'react';
import {
  Alert,
  SafeAreaView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';
import type { RootStackParamList } from '../types';
import { STORAGE_KEYS, storeItem } from '../utils/storage';

type Props = NativeStackScreenProps<RootStackParamList, 'Signup'>;

export default function Signup({ navigation }: Props) {
  const [fullName, setFullName] = useState('');
  const [username, setUsername] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  const handleSignUp = async () => {
    if (!fullName || !username || !email || !password || !confirmPassword) {
      Alert.alert('Please fill in all fields.');
      return;
    }

    if (password !== confirmPassword) {
      Alert.alert('Passwords do not match.');
      return;
    }

    try {
      const response = await fetch('http://192.168.0.101/NasugView-Backend/signup.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ fullName, username, email, password }),
      });

      const result = await response.json();
      console.log('Signup response:', result);

      if (result.success) {
        const userData = {
          fullName: result?.user?.fullName ?? fullName,
          username: result?.user?.username ?? username,
          profilePicture: result?.user?.profilePicture ?? null,
          email: result?.user?.email ?? email,
          coverPhoto: result?.user?.coverPhoto ?? null,
        };

        await storeItem(STORAGE_KEYS.USER_DATA, userData);
        Alert.alert('Success', result.message);

        navigation.reset({
          index: 0,
          routes: [{ name: 'Tabs' }],
        });
      } else {
        Alert.alert('Failed', result.message ?? 'Signup failed.');
      }
    } catch (error) {
      console.error('Sign up error:', error);
      Alert.alert('Error', 'Unable to connect to server.');
    }
  };

  return (
    <SafeAreaView style={styles.container}>
      <View style={styles.header}>
        <Text style={styles.headerText}>Join us!</Text>
        <Text style={styles.subText}>Create your NasugView account</Text>
      </View>

      <View style={styles.formContainer}>
        <Text style={styles.loginTitle}>Sign Up</Text>

        <TextInput
          placeholder="Full Name"
          placeholderTextColor="#888"
          value={fullName}
          onChangeText={setFullName}
          style={styles.input}
        />
        <TextInput
          placeholder="Username"
          placeholderTextColor="#888"
          value={username}
          onChangeText={setUsername}
          style={styles.input}
        />
        <TextInput
          placeholder="Email"
          placeholderTextColor="#888"
          value={email}
          onChangeText={setEmail}
          style={styles.input}
          keyboardType="email-address"
          autoCapitalize="none"
        />
        <TextInput
          placeholder="Password"
          placeholderTextColor="#888"
          value={password}
          onChangeText={setPassword}
          secureTextEntry
          style={styles.input}
        />
        <TextInput
          placeholder="Confirm Password"
          placeholderTextColor="#888"
          value={confirmPassword}
          onChangeText={setConfirmPassword}
          secureTextEntry
          style={styles.input}
        />

        <TouchableOpacity style={styles.loginButton} onPress={handleSignUp}>
          <Text style={styles.loginButtonText}>Sign Up</Text>
        </TouchableOpacity>

        <TouchableOpacity
          onPress={() => navigation.navigate('Login')}
          style={{ marginTop: 20 }}
        >
          <Text style={{ color: '#555' }}>
            Already have an account?{' '}
            <Text style={{ color: primaryGreen, fontWeight: 'bold' }}>Login</Text>
          </Text>
        </TouchableOpacity>
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
  loginTitle: {
    fontSize: 24,
    fontWeight: 'bold',
    color: primaryGreen,
    marginBottom: 20,
  },
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
    marginBottom: 15,
  },
  loginButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
