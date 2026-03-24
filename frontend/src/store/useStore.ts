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
  user: null,
  setUser: (user) => set({ user }),
  isLoading: true,
  setIsLoading: (loading) => set({ isLoading: loading }),
  token: localStorage.getItem('auth_token'),
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
