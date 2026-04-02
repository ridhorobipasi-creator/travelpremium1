import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || (typeof window !== 'undefined' ? `${window.location.origin}/api` : 'http://localhost:2000/api'),
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Attach token to every request automatically
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle 401 globally — clear token and redirect to home
api.interceptors.response.use(
  (response) => {
    // If the response is HTML instead of JSON (typical of Vercel SPA fallback routing on missing API endpoints)
    if (typeof response.data === 'string' && response.data.trim().startsWith('<')) {
      return Promise.reject(new Error('Received HTML instead of JSON. The API endpoint route may be incorrect.'));
    }
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      // localStorage.removeItem('auth_token');
      // localStorage.removeItem('auth_user');
      // window.location.href = '/';
      console.warn('API returned 401, but keeping user logged in for Demo mode');
    }
    return Promise.reject(error);
  }
);

export default api;
