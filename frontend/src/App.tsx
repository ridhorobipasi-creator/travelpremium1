import React, { useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { useStore } from './store/useStore';
import { UserProfile } from './types';
import { Toaster } from 'sonner';
import { HelmetProvider } from 'react-helmet-async';
import ErrorBoundary from './components/ErrorBoundary';
import api from './lib/api';

// Layouts
import PublicLayout from './components/PublicLayout';
import AdminLayout from './components/AdminLayout';

// Pages
import Home from './pages/Home';
import Packages from './pages/Packages';
import PackageDetail from './pages/PackageDetail';
import Cars from './pages/Cars';
import CarDetail from './pages/CarDetail';
import Blog from './pages/Blog';
import BlogDetail from './pages/BlogDetail';
import Profile from './pages/Profile';
import MyBookings from './pages/MyBookings';
import AdminDashboard from './pages/AdminDashboard';
import AdminPackages from './pages/AdminPackages';
import AdminCars from './pages/AdminCars';
import AdminBookings from './pages/AdminBookings';
import AdminUsers from './pages/AdminUsers';
import AdminCities from './pages/AdminCities';
import AdminPackageCreate from './pages/AdminPackageCreate';
import AdminCarCreate from './pages/AdminCarCreate';
import AdminBlogs from './pages/AdminBlogs';
import AdminBlogCreate from './pages/AdminBlogCreate';

export default function App() {
  const { setUser, setIsLoading, user, isLoading, token } = useStore();

  useEffect(() => {
    // On mount, restore user from token
    if (token) {
      api.get('/auth/me')
        .then((res) => {
          setUser(res.data as UserProfile);
        })
        .catch(() => {
          // Token expired/invalid
          setUser(null);
          useStore.getState().setToken(null);
        })
        .finally(() => setIsLoading(false));
    } else {
      setIsLoading(false);
    }
  }, []);

  const ProtectedRoute = ({ children, role }: { children: React.ReactNode; role?: string }) => {
    if (isLoading) return (
      <div className="min-h-screen flex items-center justify-center bg-slate-50">
        <div className="w-12 h-12 border-4 border-slate-200 border-t-toba-green rounded-full animate-spin" />
      </div>
    );
    
    if (!token && !user) return <Navigate to="/" />;
    if (role && user && user.role !== role && user.role !== 'admin') return <Navigate to="/" />;
    return <>{children}</>;
  };

  return (
    <HelmetProvider>
      <ErrorBoundary>
        <Router>
          <Toaster
            position="top-right"
            richColors
            toastOptions={{
              style: { borderRadius: '1rem', fontWeight: 600 },
            }}
          />
          <Routes>
            {/* Public Routes */}
            <Route element={<PublicLayout />}>
              <Route path="/" element={<Home />} />
              <Route path="/packages" element={<Packages />} />
              <Route path="/package/:slug" element={<PackageDetail />} />
              <Route path="/cars" element={<Cars />} />
              <Route path="/cars/:id" element={<CarDetail />} />
              <Route path="/blog" element={<Blog />} />
              <Route path="/blog/:id" element={<BlogDetail />} />
              <Route path="/profile" element={<Profile />} />
              <Route path="/my-bookings" element={<MyBookings />} />
            </Route>

            {/* Admin Routes */}
            <Route
              path="/admin"
              element={
                <ProtectedRoute role="staff">
                  <AdminLayout />
                </ProtectedRoute>
              }
            >
              <Route index element={<AdminDashboard />} />
              <Route path="packages" element={<AdminPackages />} />
              <Route path="create-package" element={<AdminPackageCreate />} />
              <Route path="edit-package/:id" element={<AdminPackageCreate />} />
              <Route path="cars" element={<AdminCars />} />
              <Route path="add-cars" element={<AdminCarCreate />} />
              <Route path="edit-car/:id" element={<AdminCarCreate />} />
              <Route path="blog" element={<AdminBlogs />} />
              <Route path="create-blog" element={<AdminBlogCreate />} />
              <Route path="edit-blog/:id" element={<AdminBlogCreate />} />
              <Route path="bookings" element={<AdminBookings />} />
              <Route path="users" element={<AdminUsers />} />
              <Route path="cities" element={<AdminCities />} />
            </Route>

            {/* Fallback */}
            <Route path="*" element={<Navigate to="/" />} />
          </Routes>
        </Router>
      </ErrorBoundary>
    </HelmetProvider>
  );
}
