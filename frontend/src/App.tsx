import React, { useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { useStore } from './store/useStore';
import { UserProfile } from './types';
import { Toaster } from 'sonner';
import { HelmetProvider } from 'react-helmet-async';
import ErrorBoundary from './components/ErrorBoundary';
import api from './lib/api';
import PWAInstallPrompt from './components/PWAInstallPrompt';

// Layouts
import PublicLayout from './components/PublicLayout';
import AdminLayout from './components/AdminLayout';

// Pages
import Landing from './pages/Landing';
import Outbound from './pages/Outbound';
import Home from './pages/Home';
import Packages from './pages/Packages';
import PackageDetail from './pages/PackageDetail';
import Cars from './pages/Cars';
import CarDetail from './pages/CarDetail';
import Blog from './pages/Blog';
import BlogDetail from './pages/BlogDetail';
import Profile from './pages/Profile';
import MyBookings from './pages/MyBookings';
import AdminPortal from './pages/AdminPortal';
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
import NotFound from './pages/NotFound';
import OutboundPackages from './pages/OutboundPackages';

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
          // setUser(null);
          // useStore.getState().setToken(null);
          console.warn('Backend /auth/me failed, but keeping Demo Admin user active');
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
          <PWAInstallPrompt />
          <Routes>
            {/* Public Routes */}
            <Route path="/" element={<Landing />} />
            <Route element={<PublicLayout />}>
              {/* Tour Scope */}
              <Route path="/tour" element={<Home />} />
              <Route path="/tour/packages" element={<Packages category="tour" />} />
              <Route path="/tour/package/:slug" element={<PackageDetail />} />
              <Route path="/tour/cars" element={<Cars />} />
              <Route path="/tour/cars/:id" element={<CarDetail />} />
              <Route path="/tour/blog" element={<Blog category="tour" />} />
              <Route path="/tour/blog/:id" element={<BlogDetail />} />

              {/* Outbound Scope */}
              <Route path="/outbound" element={<Outbound />} />
              <Route path="/outbound/packages" element={<OutboundPackages />} />
              <Route path="/outbound/package/:slug" element={<PackageDetail />} />
              <Route path="/outbound/blog" element={<Blog category="outbound" />} />
              <Route path="/outbound/blog/:id" element={<BlogDetail />} />

              {/* Alias for outbond typos */}
              <Route path="/outbond" element={<Navigate to="/outbound" replace />} />
              <Route path="/outbond/packages" element={<Navigate to="/outbound/packages" replace />} />
              <Route path="/outbond/blog" element={<Navigate to="/outbound/blog" replace />} />

              {/* Shared Scope */}
              <Route path="/profile" element={<Profile />} />
              <Route path="/my-bookings" element={<MyBookings />} />
            </Route>

            {/* Admin Portal (Super Dashboard Gateway) */}
            <Route 
              path="/admin" 
              element={
                <ProtectedRoute role="staff">
                  <AdminPortal />
                </ProtectedRoute>
              } 
            />

            {/* Admin Tour Routes */}
            <Route
              path="/admin/tour"
              element={
                <ProtectedRoute role="staff">
                  <AdminLayout category="tour" />
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

            {/* Admin Outbound Routes */}
            <Route
              path="/admin/outbound"
              element={
                <ProtectedRoute role="staff">
                  <AdminLayout category="outbound" />
                </ProtectedRoute>
              }
            >
              <Route index element={<AdminDashboard />} />
              <Route path="packages" element={<AdminPackages />} />
              <Route path="create-package" element={<AdminPackageCreate />} />
              <Route path="edit-package/:id" element={<AdminPackageCreate />} />
              <Route path="blog" element={<AdminBlogs />} />
              <Route path="create-blog" element={<AdminBlogCreate />} />
              <Route path="edit-blog/:id" element={<AdminBlogCreate />} />
              <Route path="bookings" element={<AdminBookings />} />
              <Route path="users" element={<AdminUsers />} />
              <Route path="cities" element={<AdminCities />} />
            </Route>

            {/* 404 */}
            <Route path="*" element={<NotFound />} />
          </Routes>
        </Router>
      </ErrorBoundary>
    </HelmetProvider>
  );
}
