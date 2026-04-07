import { Link, useNavigate, useLocation } from 'react-router-dom';
import { Search, LogIn, Menu, X, Globe, Phone, Mail, LogOut, User, Lock, Eye, EyeOff, Moon, Sun } from 'lucide-react';
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
  const [isDark, setIsDark] = useState(() => document.documentElement.classList.contains('dark'));
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

  const toggleDark = () => {
    const html = document.documentElement;
    const newVal = !isDark;
    html.classList.toggle('dark', newVal);
    setIsDark(newVal);
    localStorage.setItem('wt-dark-mode', String(newVal));
  };

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

  type NavLink = { name: string; path: string; isExternal?: boolean };

  const isOutbound = location.pathname.startsWith('/outbound');
  const isDarkHeroPage = location.pathname === '/tour' || location.pathname === '/outbound' || location.pathname === '/tour/packages' || location.pathname === '/outbound/packages';
  
  const navLinks: NavLink[] = isOutbound ? [
    { name: 'Tentang Kami', path: '/outbound#tentangkami' },
    { name: 'Layanan', path: '/outbound#layanan' },
    { name: 'Klien', path: '/outbound#klien' },
    { name: 'Galeri', path: '/outbound/gallery' },
    { name: 'Pricelist', path: '/outbound/packages' },
    { name: 'Blog', path: '/outbound/blog' },
  ] : [
    { name: 'Tour Packages', path: '/tour/packages' },
    { name: 'Car Rental', path: '/tour/cars' },
    { name: 'Galeri Kamu', path: '/tour/gallery' },
    { name: 'Blog', path: '/tour/blog' },
  ];

  return (
    <header className="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
      {/* Top Bar (Optional, can be toggled) */}
      <div className={cn(
        "hidden sm:block bg-slate-900 text-white py-2 transition-all duration-300 overflow-hidden",
        isScrolled ? "h-0 opacity-0" : "h-auto opacity-100"
      )}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[11px] font-semibold tracking-wider uppercase">
          <div className="flex items-center space-x-6">
            <a href="tel:+6281323888207" className="flex items-center hover:text-toba-accent transition-colors">
              <Phone size={12} className="mr-2" />
              +62 813-2388-8207
            </a>
            <a href="mailto:outbound@wonderfultoba.com" className="flex items-center hover:text-toba-accent transition-colors">
              <Mail size={12} className="mr-2" />
              outbound@wonderfultoba.com
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
          ? "bg-white/95 backdrop-blur-md py-3 shadow-lg border-slate-100" 
          : isDarkHeroPage 
            ? "bg-transparent py-5 border-transparent" 
            : "bg-white py-5 border-slate-100"
      )}>
        <div className="max-w-7xl mx-auto px-4 lg:px-6 xl:px-8">
          <div className="flex justify-between items-center">
            {/* Logo */}
            <Link to="/" className="flex items-center space-x-3 group w-auto shrink-0 mr-8">
              <div className="w-12 h-12 bg-toba-green rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-lg shadow-toba-green/20 group-hover:scale-105 transition-transform shrink-0">
                W
              </div>
              <div className="flex flex-col whitespace-nowrap">
                <span className={cn(
                  "text-xl font-extrabold leading-none tracking-tight transition-colors",
                  (!isScrolled && isDarkHeroPage) ? "text-white" : "text-slate-900"
                )}>
                  WONDERFUL <span className="text-toba-green">TOBA</span> {isOutbound && <span className={(!isScrolled && isDarkHeroPage) ? "text-white" : "text-slate-900"}>OUTBOUND</span>}
                </span>
                <span className={cn(
                  "hidden sm:block text-[10px] font-bold tracking-[0.25em] uppercase mt-1 transition-colors",
                  (!isScrolled && isDarkHeroPage) ? "text-slate-300" : "text-slate-400"
                )}>
                  {isOutbound ? 'Provider Outbound Terbaik Sumut' : 'Sumatera Utara Travel Experience'}
                </span>
              </div>
            </Link>

            {/* Desktop Nav */}
            <div className="hidden lg:flex items-center space-x-8 shrink-0 w-max">
              {navLinks.map((link) => (
                <Link
                  key={link.path}
                  to={link.path}
                  className={cn(
                    "font-bold tracking-wide transition-all duration-200 whitespace-nowrap text-sm h-full flex items-center",
                    location.pathname === link.path 
                      ? "text-toba-green" 
                      : (!isScrolled && isDarkHeroPage) ? "text-white/80 hover:text-white drop-shadow-sm" : "text-slate-600 hover:text-toba-green"
                  )}
                >
                  {link.name}
                </Link>
              ))}
            </div>

            {/* Actions */}
            <div className="hidden lg:flex items-center space-x-6 shrink-0 ml-8">
              <a href="https://wa.me/6281323888207" target="_blank" rel="noreferrer" className={cn(
                "px-5 py-2.5 rounded-full font-bold text-sm tracking-wide transition-all shadow-lg flex items-center space-x-2 whitespace-nowrap",
                (!isScrolled && isDarkHeroPage) ? "bg-white text-toba-green hover:bg-slate-100" : "bg-toba-green text-white hover:bg-slate-900"
              )}>
                <Phone size={16} />
                <span>HUBUNGI KAMI</span>
              </a>

              <button
                onClick={toggleDark}
                className={cn(
                  "p-2.5 rounded-xl transition-all",
                  isDark ? "bg-slate-700 text-yellow-400 hover:bg-slate-600" :
                  (!isScrolled && isDarkHeroPage) ? "bg-white/10 text-white hover:bg-white/20" :
                  "bg-slate-100 text-slate-600 hover:bg-slate-200"
                )}
              >
                {isDark ? <Sun size={18} /> : <Moon size={18} />}
              </button>

              {user ? (
                <div className="flex items-center space-x-5">
                   <button onClick={() => navigate('/profile')} className="w-10 h-10 rounded-full overflow-hidden ring-2 ring-transparent hover:ring-toba-green transition-all shadow-sm">
                      <img src={user.photoURL || 'https://picsum.photos/seed/user/100'} alt="User" className="w-full h-full object-cover" />
                   </button>
                   <button onClick={handleLogout} className={cn("text-xs font-bold leading-none", (!isScrolled && isDarkHeroPage) ? "text-white/80 hover:text-white" : "text-slate-600 hover:text-rose-600")}>
                      Logout
                   </button>
                </div>
              ) : (
                <button
                  onClick={() => setShowLoginModal(true)}
                  className={cn(
                    "px-4 py-2 rounded-full text-sm font-bold flex items-center space-x-2 transition-all whitespace-nowrap",
                    (!isScrolled && isDarkHeroPage) ? "text-white hover:text-toba-green bg-white/10 hover:bg-white" : "text-slate-600 hover:text-toba-green"
                  )}
                >
                  <LogIn size={18} />
                  <span>LOGIN</span>
                </button>
              )}
            </div>

            {/* Mobile Menu Toggle */}
            <div className="lg:hidden flex items-center">
              <button onClick={() => setIsMenuOpen(!isMenuOpen)} className={cn("p-2 transition-colors", (!isScrolled && isDarkHeroPage) ? "text-white hover:text-toba-green" : "text-slate-600 hover:text-toba-green")}>
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
                onClick={() => setIsMenuOpen(false)}
                className={cn(
                  "block text-lg font-bold p-3 rounded-xl transition-all",
                  location.pathname === link.path ? "text-toba-green bg-toba-green/5" : "text-slate-600 hover:bg-slate-50"
                )}
              >
                {link.name}
              </Link>
            ))}
          </div>
        )}
      </nav>

      {/* Login Modal (Keep existing implementation) */}
      {showLoginModal && (
         <div className="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md">
           {/* ... Modal Content as before ... */}
           <div className="bg-white rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden p-8 relative">
              <button onClick={() => setShowLoginModal(false)} className="absolute top-6 right-6 p-2 text-slate-400 hover:text-slate-900">
                <X size={22} />
              </button>
              <h3 className="text-2xl font-black text-slate-900 mb-6">{isRegister ? 'Buat Akun' : 'Selamat Datang'}</h3>
              <form onSubmit={handleAuthSubmit} className="space-y-4">
                 {isRegister && <input required className="w-full p-4 bg-slate-50 rounded-2xl" placeholder="Nama Anda" value={form.name} onChange={e => setForm(f => ({ ...f, name: e.target.value }))} />}
                 <input required className="w-full p-4 bg-slate-50 rounded-2xl" placeholder="Email" type="email" value={form.email} onChange={e => setForm(f => ({ ...f, email: e.target.value }))} />
                 <input required className="w-full p-4 bg-slate-50 rounded-2xl" placeholder="Password" type="password" value={form.password} onChange={e => setForm(f => ({ ...f, password: e.target.value }))} />
                 <button className="w-full py-4 bg-toba-green text-white rounded-2xl font-bold shadow-lg" disabled={formLoading}>
                    {formLoading ? 'Proses...' : (isRegister ? 'Daftar' : 'Masuk')}
                 </button>
                 <button type="button" onClick={() => setIsRegister(!isRegister)} className="w-full text-center text-sm font-bold text-toba-green mt-2">
                    {isRegister ? 'Sudah punya akun? Login' : 'Belum punya akun? Daftar'}
                 </button>
              </form>
           </div>
         </div>
      )}
    </header>
  );
}
