import { create } from 'zustand';
import { UserProfile } from '../types';

interface AppState {
  user: UserProfile | null;
  setUser: (user: UserProfile | null) => void;
  isLoading: boolean;
  setIsLoading: (loading: boolean) => void;
  token: string | null;
  setToken: (token: string | null) => void;
}

export const useStore = create<AppState>((set) => ({
  user: { id: 1, uid: '1', name: 'Admin Demo', email: 'admin@demo.com', role: 'admin' },
  setUser: (user) => set({ user }),
  isLoading: false,
  setIsLoading: (loading) => set({ isLoading: loading }),
  token: localStorage.getItem('auth_token') || 'dummy-token',
  setToken: (token) => {
    if (token) {
      localStorage.setItem('auth_token', token);
    } else {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('auth_user');
    }
    set({ token });
  },
}));
