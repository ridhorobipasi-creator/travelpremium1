import { Link, useNavigate, useLocation } from 'react-router-dom';
import { Search, LogIn, Menu, X, Globe, Phone, Mail, LogOut, User, Lock, Eye, EyeOff } from 'lucide-react';
import { useState, useEffect } from 'react';
import { useStore } from '../store/useStore';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import api from '../lib/api';

export default function Navbar() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [showLoginModal, setShowLoginModal] = useState(false);
  const [isRegister, setIsRegister] = useState(false);
  const [formLoading, setFormLoading] = useState(false);
  const [showPass, setShowPass] = useState(false);
  const [form, setForm] = useState({ name: '', email: '', password: '' });
  const { user, setUser, setToken } = useStore();
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    const handleScroll = () => setIsScrolled(window.scrollY > 20);
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  useEffect(() => {
    setIsMenuOpen(false);
  }, [location.pathname]);

  const handleAuthSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setFormLoading(true);
    try {
      const endpoint = isRegister ? '/auth/register' : '/auth/login';
      const payload = isRegister
        ? { name: form.name, email: form.email, password: form.password }
        : { email: form.email, password: form.password };
      const res = await api.post(endpoint, payload);
      setToken(res.data.token);
      setUser(res.data.user);
      setShowLoginModal(false);
      setForm({ name: '', email: '', password: '' });
      toast.success(isRegister ? 'Akun berhasil dibuat!' : 'Selamat datang kembali!');
    } catch (err: any) {
      if (err.response?.data?.errors) {
        const errors = err.response.data.errors;
        const firstError = Object.values(errors)[0] as string[];
        toast.error(firstError[0]);
      } else {
        const msg = err.response?.data?.message || 'Terjadi kesalahan. Coba lagi.';
        toast.error(msg);
      }
    } finally {
      setFormLoading(false);
    }
  };

  const handleLogout = async () => {
    try { await api.post('/auth/logout'); } catch {}
    setUser(null);
    setToken(null);
    toast.success('Berhasil keluar.');
    navigate('/');
    setIsMenuOpen(false);
  };

  const navLinks = [
    { name: 'Home', path: '/' },
    { name: 'Tour Packages', path: '/packages' },
    { name: 'Car Rental', path: '/cars' },
    { name: 'Blog', path: '/blog' },
  ];

  return (
    <header className="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
      {/* Top Bar */}
      <div className={cn(
        "bg-slate-900 text-white py-2 transition-all duration-300 overflow-hidden",
        isScrolled ? "h-0 opacity-0" : "h-auto opacity-100"
      )}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[11px] font-semibold tracking-wider uppercase">
          <div className="flex items-center space-x-6">
            <a href="tel:+6281234567890" className="flex items-center hover:text-toba-accent transition-colors">
              <Phone size={12} className="mr-2" />
              +62 812 3456 7890
            </a>
            <a href="mailto:info@wonderfultoba.com" className="flex items-center hover:text-toba-accent transition-colors">
              <Mail size={12} className="mr-2" />
              info@wonderfultoba.com
            </a>
          </div>
          <div className="flex items-center space-x-6">
            <span className="flex items-center">
              <Globe size={12} className="mr-2" />
              ID | EN
            </span>
          </div>
        </div>
      </div>

      {/* Main Nav */}
      <nav className={cn(
        "transition-all duration-300 border-b",
        isScrolled 
          ? "bg-white/90 backdrop-blur-md py-3 shadow-lg border-slate-100" 
          : "bg-white py-5 border-transparent"
      )}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center">
            {/* Logo */}
            <Link to="/" className="flex items-center space-x-3 group">
              <div className="w-10 h-10 bg-toba-green rounded-lg flex items-center justify-center text-white font-black text-xl shadow-lg shadow-toba-green/20 group-hover:scale-105 transition-transform">
                W
              </div>
              <div className="flex flex-col">
                <span className="text-xl font-extrabold text-slate-900 leading-none tracking-tight">
                  WONDERFUL <span className="text-toba-green">TOBA</span>
                </span>
                <span className="text-[10px] font-bold text-slate-400 tracking-[0.2em] uppercase mt-1">
                  Sumatera Utara Travel Experience
                </span>
              </div>
            </Link>

            {/* Desktop Nav */}
            <div className="hidden lg:flex items-center space-x-10">
              {navLinks.map((link) => (
                <Link
                  key={link.path}
                  to={link.path}
                  className={cn(
                    "text-sm font-bold tracking-wide transition-all duration-200",
                    location.pathname === link.path 
                      ? "text-toba-green" 
                      : "text-slate-600 hover:text-toba-green"
                  )}
                >
                  {link.name}
                </Link>
              ))}
            </div>

            {/* Desktop Actions */}
            <div className="hidden lg:flex items-center space-x-6">
              <button className="text-slate-400 hover:text-toba-green transition-colors" aria-label="Cari">
                <Search size={20} />
              </button>
              <div className="h-6 w-px bg-slate-200" />
              {user ? (
                <div className="flex items-center space-x-4">
                  {user.role !== 'user' && (
                    <Link
                      to="/admin"
                      className="text-xs font-extrabold text-toba-green hover:text-toba-green/80 bg-toba-green/5 px-4 py-2 rounded-full transition-all"
                    >
                      DASHBOARD
                    </Link>
                  )}
                  <button
                    onClick={() => navigate('/profile')}
                    className="w-10 h-10 rounded-full overflow-hidden ring-2 ring-slate-100 ring-offset-2 hover:ring-toba-green transition-all"
                    aria-label="Profil saya"
                  >
                    <img src={user.photoURL || 'https://picsum.photos/seed/user/100'} alt="User" referrerPolicy="no-referrer" />
                  </button>
                  <button onClick={handleLogout} className="flex items-center space-x-1 text-xs font-bold text-slate-500 hover:text-rose-600 transition-colors" aria-label="Keluar">
                    <LogOut size={16} />
                  </button>
                </div>
              ) : (
                <div className="flex items-center space-x-4">

                  <button
                    onClick={() => setShowLoginModal(true)}
                    className="bg-toba-green text-white px-8 py-3 rounded-full text-sm font-bold hover:bg-slate-900 transition-all shadow-xl shadow-toba-green/20 flex items-center space-x-2"
                  >
                    <LogIn size={18} />
                    <span>LOGIN</span>
                  </button>
                </div>
              )}
            </div>

            {/* Mobile Menu Toggle */}
            <div className="lg:hidden flex items-center">
              <button
                onClick={() => setIsMenuOpen(!isMenuOpen)}
                className="p-2 text-slate-600 hover:text-toba-green transition-colors"
                aria-label={isMenuOpen ? 'Tutup menu' : 'Buka menu'}
              >
                {isMenuOpen ? <X size={28} /> : <Menu size={28} />}
              </button>
            </div>
          </div>
        </div>

        {/* Mobile Nav */}
        {isMenuOpen && (
          <div className="lg:hidden fixed inset-x-0 top-full bg-white border-t border-slate-100 p-6 space-y-4 shadow-2xl z-50">
            {navLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                className={cn(
                  "block text-lg font-bold p-3 rounded-xl transition-all",
                  location.pathname === link.path 
                    ? "text-toba-green bg-toba-green/5" 
                    : "text-slate-600 hover:bg-slate-50"
                )}
              >
                {link.name}
              </Link>
            ))}

            <div className="border-t border-slate-100 pt-4 space-y-3">
              {user ? (
                <>
                  <button
                    onClick={() => { navigate('/profile'); setIsMenuOpen(false); }}
                    className="w-full flex items-center space-x-3 p-3 rounded-xl text-slate-700 hover:bg-slate-50 font-bold transition-all"
                  >
                    <User size={20} className="text-toba-green" />
                    <span>Profil Saya</span>
                  </button>
                  {user.role !== 'user' && (
                    <Link
                      to="/admin"
                      className="flex items-center space-x-3 p-3 rounded-xl text-toba-green bg-toba-green/5 font-bold transition-all"
                    >
                      <span>Dashboard Admin</span>
                    </Link>
                  )}
                  <button
                    onClick={handleLogout}
                    className="w-full flex items-center justify-center space-x-2 bg-rose-50 text-rose-600 p-4 rounded-2xl font-bold border border-rose-100"
                  >
                    <LogOut size={20} />
                    <span>KELUAR</span>
                  </button>
                </>
              ) : (
                <button
                  onClick={() => { setShowLoginModal(true); setIsMenuOpen(false); }}
                  className="w-full flex items-center justify-center space-x-2 bg-toba-green text-white p-4 rounded-2xl font-bold"
                >
                  <LogIn size={20} />
                  <span>MASUK</span>
                </button>
              )}
            </div>
          </div>
        )}
      </nav>

      {/* Login / Register Modal */}
      {showLoginModal && (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md">
          <div className="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center">
              <div>
                <h3 className="text-2xl font-black text-slate-900">{isRegister ? 'Buat Akun' : 'Selamat Datang'}</h3>
                <p className="text-sm text-slate-500 font-medium mt-1">{isRegister ? 'Daftar untuk mulai memesan' : 'Masuk ke akun Wonderful Toba Anda'}</p>
              </div>
              <button onClick={() => setShowLoginModal(false)} className="p-2 hover:bg-slate-50 rounded-xl text-slate-400 hover:text-slate-900 transition-all">
                <X size={22} />
              </button>
            </div>
            <form onSubmit={handleAuthSubmit} className="p-8 space-y-5">
              {isRegister && (
                <div className="space-y-2">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                  <div className="relative">
                    <User className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={18} />
                    <input required value={form.name} onChange={e => setForm(f => ({ ...f, name: e.target.value }))}
                      className="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900"
                      placeholder="Nama Anda" />
                  </div>
                </div>
              )}
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</label>
                <div className="relative">
                  <Mail className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={18} />
                  <input required type="email" value={form.email} onChange={e => setForm(f => ({ ...f, email: e.target.value }))}
                    className="w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900"
                    placeholder="email@contoh.com" />
                </div>
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Password</label>
                <div className="relative">
                  <Lock className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={18} />
                  <input required type={showPass ? 'text' : 'password'} value={form.password} onChange={e => setForm(f => ({ ...f, password: e.target.value }))}
                    className="w-full pl-11 pr-12 py-3.5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium text-slate-900"
                    placeholder="••••••••" />
                  <button type="button" onClick={() => setShowPass(v => !v)} className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    {showPass ? <EyeOff size={18} /> : <Eye size={18} />}
                  </button>
                </div>
              </div>
              <button type="submit" disabled={formLoading}
                className="w-full py-4 bg-toba-green text-white rounded-2xl font-bold hover:bg-toba-green/90 transition-all shadow-lg shadow-toba-green/20 disabled:opacity-60">
                {formLoading ? 'Memproses...' : (isRegister ? 'Daftar Sekarang' : 'Masuk')}
              </button>
              <p className="text-center text-sm text-slate-500">
                {isRegister ? 'Sudah punya akun?' : 'Belum punya akun?'}{' '}
                <button type="button" onClick={() => setIsRegister(v => !v)} className="font-bold text-toba-green hover:underline">
                  {isRegister ? 'Masuk' : 'Daftar'}
                </button>
              </p>
            </form>
          </div>
        </div>
      )}
    </header>
  );
}
