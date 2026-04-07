import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import HeroSlider from '../components/HeroSlider';
import { MapPin, Star, ArrowRight, Shield, Clock, Globe, CheckCircle } from 'lucide-react';
import { Link } from 'react-router-dom';
import { Helmet } from 'react-helmet-async';
import api from '../lib/api';
import { fallbackPackages, fallbackBlogs } from '../utils/fallbackData';

const testimonials = [
  {
    name: 'Andi Pratama',
    role: 'Traveler dari Jakarta',
    avatar: 'https://i.pravatar.cc/100?img=11',
    text: 'Pengalaman luar biasa! Danau Toba jauh lebih indah dari yang saya bayangkan. Pelayanan tim Wonderful Toba sangat profesional.',
    rating: 5,
  },
  {
    name: 'Sari Dewi',
    role: 'Fotografer Alam',
    avatar: 'https://i.pravatar.cc/100?img=5',
    text: 'Bukit Lawang adalah surga bagi pecinta alam. Bertemu orangutan liar di habitat aslinya adalah pengalaman yang tak terlupakan.',
    rating: 5,
  },
  {
    name: 'Budi Santoso',
    role: 'Keluarga dari Surabaya',
    avatar: 'https://i.pravatar.cc/100?img=15',
    text: 'Paket keluarga sangat worth it! Anak-anak sangat senang dengan gajah di Tangkahan. Pasti akan kembali lagi.',
    rating: 5,
  },
];

export default function Home() {
  const [featuredPackages, setFeaturedPackages] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchFeatured = async () => {
      try {
        const res = await api.get('/packages?scope=tour');
        setFeaturedPackages(Array.isArray(res.data) && res.data.length > 0 ? res.data.slice(0, 3) : fallbackPackages.slice(0, 3));
      } catch (err) {
        console.warn('API fetch error, using fallback packages:', err);
        setFeaturedPackages(fallbackPackages.slice(0, 3));
      } finally {
        setLoading(false);
      }
    };
    fetchFeatured();
  }, []);

  return (
    <div className="bg-white overflow-hidden">
      <Helmet>
        <title>Wonderful Toba – Wisata Sumatera Utara</title>
        <meta name="description" content="Temukan keindahan Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang bersama Wonderful Toba." />
      </Helmet>
      
      <HeroSlider />

      {/* Featured Packages - Optimized for Mobile */}
      <section className="py-16 md:py-24">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 md:mb-16 gap-6">
            <div className="max-w-2xl">
              <div className="flex items-center space-x-2 mb-3 md:mb-4">
                <div className="h-px w-6 md:w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-[10px] md:text-xs uppercase tracking-[0.3em]">Destinasi Unggulan</span>
              </div>
              <h2 className="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                Paket Wisata <span className="text-toba-green">Terpopuler</span>
              </h2>
            </div>
            <Link to="/tour/packages" className="flex items-center space-x-3 text-xs md:text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0">
              <span>Lihat Semua</span>
              <div className="w-8 h-8 md:w-10 md:h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all shadow-sm">
                <ArrowRight size={16} />
              </div>
            </Link>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            {featuredPackages.map((pkg, i) => (
              <motion.div key={pkg.id} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="group">
                <div className="relative h-[300px] sm:h-[350px] md:h-[420px] rounded-[2rem] overflow-hidden mb-4 premium-shadow">
                  <img src={pkg.image} alt={pkg.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" referrerPolicy="no-referrer" />
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent" />
                  <div className="absolute top-4 left-4 md:top-6 md:left-6">
                    <div className="bg-white/90 backdrop-blur-md px-2 py-1 md:px-3 md:py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                      <Star size={11} className="text-amber-400 fill-amber-400" />
                      <span className="font-black text-slate-900 text-[10px] md:text-xs">{pkg.rating || 5.0}</span>
                    </div>
                  </div>
                  <div className="absolute bottom-4 left-4 right-4 md:bottom-6 md:left-6 md:right-6">
                    <div className="flex items-center text-toba-accent text-[9px] md:text-[10px] font-black uppercase tracking-[0.2em] mb-1.5 md:mb-2">
                      <MapPin size={11} className="mr-1.5 shrink-0" />
                      <span className="truncate">{pkg.location}</span>
                    </div>
                    <h3 className="text-lg md:text-2xl font-black text-white mb-3 md:mb-4 tracking-tight leading-tight line-clamp-2">{pkg.name}</h3>
                    <div className="flex justify-between items-end">
                      <div>
                        <p className="text-slate-400 text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5">Durasi</p>
                        <p className="text-white font-bold text-xs md:text-sm">{pkg.duration}</p>
                      </div>
                      <div className="text-right">
                        <p className="text-slate-400 text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5">Mulai Dari</p>
                        <p className="text-lg md:text-xl font-black text-white">
                          <span className="text-[10px] md:text-xs font-bold text-toba-accent mr-1">Rp</span>
                          {pkg.price?.toLocaleString('id-ID')}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <Link to={`/tour/package/${pkg.slug}`} className="flex items-center justify-center gap-2 w-full py-3 md:py-3.5 bg-slate-50 hover:bg-toba-green hover:text-white text-slate-700 rounded-2xl font-bold text-xs md:text-sm transition-all duration-300">
                  Lihat Detail <ArrowRight size={14} />
                </Link>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Why Us Section - Reordered for Mobile */}
      <section className="py-20 md:py-24 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 md:gap-20 items-center">
            
            {/* Content first on Mobile */}
            <div className="order-2 lg:order-1">
              <div className="flex items-center space-x-2 mb-4 md:mb-5">
                <div className="h-px w-6 md:w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-[10px] md:text-xs uppercase tracking-[0.3em]">Mengapa Kami</span>
              </div>
              <h2 className="text-3xl md:text-5xl font-black text-slate-900 mb-6 md:mb-8 tracking-tight leading-tight">
                Pengalaman Wisata <br /><span className="text-toba-green">Terbaik di Sumut</span>
              </h2>
              <div className="space-y-5 md:space-y-6 mb-8 md:mb-10">
                {[
                  { icon: Shield, title: 'Aman & Terpercaya', desc: 'Keselamatan Anda prioritas kami. Asuransi perjalanan lengkap dan pembayaran aman.', color: 'text-toba-green bg-toba-green/8' },
                  { icon: Clock, title: 'Dukungan 24/7', desc: 'Tim kami siap membantu kapan saja selama perjalanan Anda berlangsung.', color: 'text-emerald-600 bg-emerald-50' },
                  { icon: Globe, title: 'Jaringan Luas', desc: 'Akses ke destinasi eksklusif dan pengalaman lokal melalui jaringan mitra kami.', color: 'text-blue-600 bg-blue-50' },
                ].map((item, i) => (
                  <motion.div key={i} initial={{ opacity: 0, x: 20 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="flex items-start space-x-4 md:space-x-5">
                    <div className="w-10 h-10 md:w-12 md:h-12 rounded-2xl flex items-center justify-center shrink-0 bg-white shadow-sm">
                      <item.icon size={20} className={item.color.split(' ')[0]} />
                    </div>
                    <div>
                      <h4 className="text-base md:text-lg font-black text-slate-900 mb-0.5 md:mb-1 tracking-tight">{item.title}</h4>
                      <p className="text-slate-500 font-medium leading-relaxed text-xs md:text-sm">{item.desc}</p>
                    </div>
                  </motion.div>
                ))}
              </div>
              <div className="flex flex-wrap gap-2 md:gap-3">
                {['Guide Lokal', 'Harga Pas', 'Fleksibel', 'Private Tour'].map(tag => (
                  <span key={tag} className="flex items-center gap-1.5 px-3 py-1.5 bg-white border border-slate-100 text-slate-600 rounded-full text-[10px] md:text-xs font-bold shadow-sm">
                    <CheckCircle size={12} className="text-toba-green" /> {tag}
                  </span>
                ))}
              </div>
            </div>

            {/* Images - Hidden on very small screens, grid on tablets/desktop */}
            <div className="relative order-1 lg:order-2 hidden sm:block">
              <div className="grid grid-cols-2 gap-3 md:gap-4">
                <div className="space-y-3 md:space-y-4 pt-8 md:pt-10">
                  <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800" alt="Lake Toba" className="rounded-[1.5rem] md:rounded-[2rem] shadow-xl w-full h-32 md:h-48 object-cover" />
                  <img src="https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&q=80&w=800" alt="Waterfall" className="rounded-[1.5rem] md:rounded-[2rem] shadow-xl w-full h-32 md:h-48 object-cover" />
                </div>
                <div className="space-y-3 md:space-y-4">
                  <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800" alt="Nature" className="rounded-[1.5rem] md:rounded-[2rem] shadow-xl w-full h-32 md:h-48 object-cover" />
                  <img src="https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800" alt="Activity" className="rounded-[1.5rem] md:rounded-[2rem] shadow-xl w-full h-32 md:h-48 object-cover" />
                </div>
              </div>
              <div className="absolute -bottom-6 -right-6 bg-slate-900 text-white p-6 rounded-[2rem] shadow-2xl hidden lg:block border border-white/10">
                <p className="text-3xl font-black mb-0.5">15+</p>
                <p className="text-[10px] font-bold uppercase tracking-widest opacity-60">Tahun Pengalaman</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Blog & News Section */}
      <section className="py-20 md:py-24 bg-white border-t border-slate-100">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 md:mb-16 gap-6">
            <div className="max-w-2xl">
              <div className="flex items-center space-x-2 mb-3 md:mb-4">
                <div className="h-px w-6 md:w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-[10px] md:text-xs uppercase tracking-[0.3em]">Wawasan & Artikel</span>
              </div>
              <h2 className="text-3xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                Jurnal <span className="text-toba-green">Perjalanan</span>
              </h2>
            </div>
          </div>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            {fallbackBlogs.filter(b => b.category === 'tour').slice(0,3).map((blog, i) => (
              <motion.div key={i} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} className="group">
                <div className="h-48 md:h-56 rounded-[2rem] overflow-hidden mb-5 shadow-sm border border-slate-100">
                  <img src={blog.image} alt={blog.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                </div>
                <p className="text-[10px] font-bold text-slate-400 mb-2 uppercase tracking-widest">{blog.date}</p>
                <h3 className="text-lg md:text-xl font-bold text-slate-900 mb-2 leading-snug group-hover:text-toba-green transition-colors line-clamp-2">{blog.title}</h3>
                <p className="text-xs md:text-sm text-slate-500 line-clamp-2">{blog.excerpt}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section - Fixed Padding for Small Screens */}
      <section className="py-16 md:py-24 px-4 bg-white">
        <div className="max-w-7xl mx-auto bg-slate-900 rounded-[2.5rem] md:rounded-[3rem] p-8 md:p-20 relative overflow-hidden shadow-2xl border border-white/5">
           <div className="absolute inset-0 opacity-10">
              <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000" alt="BG" className="w-full h-full object-cover" />
           </div>
           <div className="relative z-10 text-center max-w-2xl mx-auto">
              <h2 className="text-3xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">
                Jelajahi Sumatera Utara Sekarang!
              </h2>
              <p className="text-sm md:text-lg text-slate-400 mb-8 md:mb-10 font-medium">
                Dapatkan pengalaman liburan tak terlupakan dengan layanan premium dan guide terpercaya.
              </p>
              <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                 <Link to="/tour/packages" className="w-full sm:w-auto bg-toba-green text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-toba-green/20">
                   Lihat Paket
                 </Link>
                 <a href="https://wa.me/6281323888207" className="w-full sm:w-auto bg-white/10 backdrop-blur-md text-white border border-white/20 px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest">
                   Hubungi WA
                 </a>
              </div>
           </div>
        </div>
      </section>
    </div>
  );
}
