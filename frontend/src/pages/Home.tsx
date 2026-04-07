import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import Hero from '../components/Hero';
import HeroSlider from '../components/HeroSlider';
import { MapPin, Star, ArrowRight, Shield, Clock, Heart, Globe, Award, Compass, CheckCircle } from 'lucide-react';
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
        const res = await api.get('/packages');
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
    <div className="bg-white">
      <Helmet>
        <title>Wonderful Toba – Wisata Sumatera Utara</title>
        <meta name="description" content="Temukan keindahan Danau Toba, Samosir, Berastagi, Tangkahan, dan Bukit Lawang bersama Wonderful Toba. Paket wisata dan rental mobil terpercaya di Sumatera Utara." />
      </Helmet>
      <HeroSlider />

      {/* Featured Packages */}
      <section className="py-24">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div className="max-w-2xl">
              <div className="flex items-center space-x-2 mb-4">
                <div className="h-px w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Destinasi Unggulan</span>
              </div>
              <h2 className="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                Paket Wisata <span className="text-toba-green">Terpopuler</span>
              </h2>
            </div>
            <Link to="/packages" className="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0">
              <span>Lihat Semua</span>
              <div className="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all">
                <ArrowRight size={18} />
              </div>
            </Link>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {featuredPackages.map((pkg, i) => (
              <motion.div key={pkg.id} initial={{ opacity: 0, y: 30 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="group cursor-pointer">
                <div className="relative h-[420px] rounded-[2rem] overflow-hidden mb-6 premium-shadow">
                  <img src={pkg.image} alt={pkg.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" referrerPolicy="no-referrer" />
                  <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent" />
                  <div className="absolute top-6 left-6">
                    <div className="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl flex items-center space-x-1.5 shadow-lg">
                      <Star size={13} className="text-amber-400 fill-amber-400" />
                      <span className="font-black text-slate-900 text-xs">{pkg.rating}</span>
                    </div>
                  </div>
                  <div className="absolute bottom-6 left-6 right-6">
                    <div className="flex items-center text-toba-accent text-[10px] font-black uppercase tracking-[0.2em] mb-2">
                      <MapPin size={11} className="mr-1.5" />{pkg.location}
                    </div>
                    <h3 className="text-2xl font-black text-white mb-4 tracking-tight leading-tight">{pkg.name}</h3>
                    <div className="flex justify-between items-end">
                      <div>
                        <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Durasi</p>
                        <p className="text-white font-bold text-sm">{pkg.duration}</p>
                      </div>
                      <div className="text-right">
                        <p className="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-0.5">Mulai Dari</p>
                        <p className="text-xl font-black text-white">
                          <span className="text-xs font-bold text-toba-accent mr-1">Rp</span>
                          {pkg.price.toLocaleString('id-ID')}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
                <Link to="/packages" className="flex items-center justify-center gap-2 w-full py-3.5 bg-slate-50 hover:bg-toba-green hover:text-white text-slate-700 rounded-2xl font-bold text-sm transition-all duration-300 group-hover:bg-toba-green group-hover:text-white">
                  Lihat Detail <ArrowRight size={16} />
                </Link>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Why Us Section */}
      <section className="py-24 bg-slate-50">
        <div className="max-w-7xl mx-auto px-4">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div className="relative">
              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-4 pt-10">
                  <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800" alt="Danau Toba" className="rounded-[1.5rem] shadow-xl w-full h-48 object-cover" />
                  <img src="https://images.unsplash.com/photo-1501854140801-50d01698950b?auto=format&fit=crop&q=80&w=800" alt="Sipiso-piso" className="rounded-[1.5rem] shadow-xl w-full h-48 object-cover" />
                </div>
                <div className="space-y-4">
                  <img src="https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800" alt="Bukit Lawang" className="rounded-[1.5rem] shadow-xl w-full h-48 object-cover" />
                  <img src="https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800" alt="Tangkahan" className="rounded-[1.5rem] shadow-xl w-full h-48 object-cover" />
                </div>
              </div>
              <div className="absolute -bottom-8 -right-8 bg-toba-green text-white p-8 rounded-[2rem] shadow-2xl hidden md:block">
                <p className="text-4xl font-black mb-1">15+</p>
                <p className="text-xs font-bold uppercase tracking-widest opacity-80">Tahun Melayani</p>
              </div>
            </div>

            <div>
              <div className="flex items-center space-x-2 mb-5">
                <div className="h-px w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Mengapa Kami</span>
              </div>
              <h2 className="text-4xl md:text-5xl font-black text-slate-900 mb-8 tracking-tight leading-tight">
                Pengalaman Wisata <br /><span className="text-toba-green">Terbaik di Sumut</span>
              </h2>
              <div className="space-y-6 mb-10">
                {[
                  { icon: Shield, title: 'Aman & Terpercaya', desc: 'Keselamatan Anda prioritas kami. Asuransi perjalanan lengkap dan pembayaran aman.', color: 'text-toba-green bg-toba-green/8' },
                  { icon: Clock, title: 'Dukungan 24/7', desc: 'Tim kami siap membantu kapan saja selama perjalanan Anda berlangsung.', color: 'text-emerald-600 bg-emerald-50' },
                  { icon: Globe, title: 'Jaringan Luas', desc: 'Akses ke destinasi eksklusif dan pengalaman lokal melalui jaringan mitra kami.', color: 'text-blue-600 bg-blue-50' },
                ].map((item, i) => (
                  <motion.div key={i} initial={{ opacity: 0, x: 20 }} whileInView={{ opacity: 1, x: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }} className="flex items-start space-x-5">
                    <div className={`w-13 h-13 w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 ${item.color}`}>
                      <item.icon size={24} />
                    </div>
                    <div>
                      <h4 className="text-lg font-black text-slate-900 mb-1 tracking-tight">{item.title}</h4>
                      <p className="text-slate-500 font-medium leading-relaxed text-sm">{item.desc}</p>
                    </div>
                  </motion.div>
                ))}
              </div>
              <div className="flex flex-wrap gap-3">
                {['Guide Berpengalaman', 'Harga Transparan', 'Itinerary Fleksibel', 'Grup Kecil'].map(tag => (
                  <span key={tag} className="flex items-center gap-1.5 px-4 py-2 bg-toba-green/8 text-toba-green rounded-full text-xs font-bold">
                    <CheckCircle size={13} /> {tag}
                  </span>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Blog & News Section */}
      <section className="py-24 bg-white border-t border-slate-100">
        <div className="max-w-7xl mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-8">
            <div className="max-w-2xl">
              <div className="flex items-center space-x-2 mb-4">
                <div className="h-px w-8 bg-toba-green" />
                <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Wawasan & Artikel</span>
              </div>
              <h2 className="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                Jurnal <span className="text-toba-green">Perjalanan Kami</span>
              </h2>
            </div>
            <Link to="/tour/blog" className="flex items-center space-x-3 text-sm font-black text-slate-900 uppercase tracking-widest hover:text-toba-green transition-colors group shrink-0">
              <span>Semua Artikel</span>
              <div className="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center group-hover:bg-toba-green group-hover:text-white transition-all">
                <ArrowRight size={18} />
              </div>
            </Link>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {fallbackBlogs.filter(b => b.category === 'tour').map((blog, i) => (
              <motion.div key={i} initial={{ opacity: 0, scale: 0.95 }} whileInView={{ opacity: 1, scale: 1 }} viewport={{ once: true }} className="group">
                <div className="h-56 rounded-[2rem] overflow-hidden mb-6 shadow-md">
                  <img src={blog.image} alt={blog.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                </div>
                <p className="text-xs font-bold text-slate-400 mb-3 uppercase tracking-widest">{blog.date}</p>
                <h3 className="text-xl font-bold text-slate-900 mb-3 leading-snug group-hover:text-toba-green transition-colors">{blog.title}</h3>
                <p className="text-sm text-slate-500 line-clamp-2">{blog.excerpt}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Testimonials */}
      <section className="py-24">
        <div className="max-w-7xl mx-auto px-4">
          <div className="text-center mb-16">
            <div className="flex items-center justify-center space-x-2 mb-4">
              <div className="h-px w-8 bg-toba-green" />
              <span className="text-toba-green font-black text-xs uppercase tracking-[0.3em]">Testimoni</span>
              <div className="h-px w-8 bg-toba-green" />
            </div>
            <h2 className="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
              Kata Mereka <span className="text-toba-green">Tentang Kami</span>
            </h2>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {testimonials.map((t, i) => (
              <motion.div key={i} initial={{ opacity: 0, y: 20 }} whileInView={{ opacity: 1, y: 0 }} viewport={{ once: true }} transition={{ delay: i * 0.1 }}
                className="bg-white border border-slate-100 rounded-[2rem] p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                <div className="flex gap-1 mb-5">
                  {Array.from({ length: t.rating }).map((_, j) => (
                    <Star key={j} size={16} className="text-amber-400 fill-amber-400" />
                  ))}
                </div>
                <p className="text-slate-600 leading-relaxed mb-6 font-medium italic">"{t.text}"</p>
                <div className="flex items-center gap-3 pt-5 border-t border-slate-50">
                  <img src={t.avatar} alt={t.name} className="w-12 h-12 rounded-full object-cover" referrerPolicy="no-referrer" />
                  <div>
                    <p className="font-black text-slate-900 text-sm">{t.name}</p>
                    <p className="text-xs text-slate-400 font-medium">{t.role}</p>
                  </div>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-24 px-4">
        <div className="max-w-7xl mx-auto bg-slate-900 rounded-[3rem] p-12 md:p-20 relative overflow-hidden shadow-2xl">
          <div className="absolute inset-0 opacity-20 pointer-events-none">
            <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000" alt="Danau Toba" className="w-full h-full object-cover" />
          </div>
          <div className="absolute inset-0 bg-gradient-to-r from-slate-900/80 to-slate-900/40" />
          <div className="relative z-10 text-center max-w-3xl mx-auto">
            <h2 className="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight">
              Siap Menjelajahi <br /><span className="text-toba-accent">Keindahan Sumut?</span>
            </h2>
            <p className="text-lg text-slate-300 mb-10 font-medium leading-relaxed">
              Bergabunglah dengan ribuan wisatawan yang telah merasakan keajaiban Sumatera Utara bersama kami.
            </p>
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
              <Link to="/tour/packages" className="bg-toba-green text-white px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-toba-accent transition-all shadow-2xl shadow-toba-green/20">
                Lihat Paket Wisata
              </Link>
              <Link to="/cars" className="bg-white/10 backdrop-blur-md text-white border border-white/20 px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-white/20 transition-all">
                Sewa Kendaraan
              </Link>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
