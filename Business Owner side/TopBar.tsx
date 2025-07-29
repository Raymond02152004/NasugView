import React from 'react';
import { Image, StyleSheet, TextInput, View } from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';

type TopBarProps = {
  searchText: string;
  onSearchTextChange: (text: string) => void;
};

const TopBar: React.FC<TopBarProps> = ({ searchText, onSearchTextChange }) => {
  return (
    <View style={styles.topBar}>
      <Image source={require('../../assets/images/logo.png')} style={styles.logo} />
      <View style={styles.searchContainer}>
        <TextInput
          placeholder="Search for"
          placeholderTextColor="#555"
          style={styles.searchBox}
          value={searchText}
          onChangeText={onSearchTextChange}
        />
        <Icon name="search" size={18} color="#555" style={styles.searchIcon} />
      </View>
    </View>
  );
};

export default TopBar;

const styles = StyleSheet.create({
  topBar: {
    flexDirection: 'row',
    alignItems: 'center',
    padding: 12,
    marginTop: 10,
    backgroundColor: '#fff',
  },
  logo: {
    width: 160,
    height: 70,
    resizeMode: 'contain',
  },
  searchContainer: {
    flex: 1.5,
    flexDirection: 'row',
    borderWidth: 2,
    borderColor: '#ccc',
    borderRadius: 20,
    paddingHorizontal: 14,
    alignItems: 'center',
    marginLeft: 4,
  },
  searchBox: {
    flex: 1,
    paddingVertical: 6,
    color: '#333',
  },
  searchIcon: {
    marginLeft: 6,
  },
});
