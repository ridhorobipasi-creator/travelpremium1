export interface Province {
  id: string;
  name: string;
  description?: string;
  image?: string;
}

export interface City {
  id: string;
  provinceId: string;
  name: string;
  description?: string;
  image?: string;
}

export interface Destination {
  id: string;
  cityId: string;
  name: string;
  description?: string;
  category?: string;
  images?: string[];
  location?: { lat: number; lng: number };
}

export interface Package {
  id: string;
  name: string;
  description: string;
  price: number;
  duration: string;
  cityId: string;
  includes: string[];
  excludes: string[];
  images: string[];
  status: 'active' | 'inactive';
}

export interface Car {
  id: string;
  name: string;
  type: string;
  pricePerDay: number;
  status: 'available' | 'booked';
  image: string;
  seats?: number;
  fuel?: string;
  transmission?: string;
}

export interface Booking {
  id: string;
  userId: string;
  type: 'package' | 'car' | 'custom';
  itemId: string;
  startDate: string;
  endDate: string;
  status: 'pending' | 'confirmed' | 'cancelled';
  totalPrice: number;
  customerDetails: {
    name: string;
    phone: string;
    email: string;
  };
  itemName?: string;
  itemImage?: string;
  createdAt: string;
}

export interface UserProfile {
  uid: string;
  email: string;
  role: 'admin' | 'staff' | 'user';
  name?: string;
  photoURL?: string;
}

export interface BlogPost {
  id: string;
  title: string;
  content: string;
  authorId: string;
  category: string;
  tags: string[];
  image: string;
  createdAt: string;
}
