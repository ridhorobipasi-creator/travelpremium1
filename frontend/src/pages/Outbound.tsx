import { useState, useEffect } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Helmet } from 'react-helmet-async';
import { ArrowRight, MapPin, Target, Users, Sparkles, Smile, Compass, Navigation, Swords, Play } from 'lucide-react';
import { Link } from 'react-router-dom';
import { cn } from '../utils/cn';

export default function Outbound() {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [activeVideoTab, setActiveVideoTab] = useState(0);

  const heroImages = [
    '/assets/images/2023/10/001-1.jpg',
    '/assets/images/2023/10/002-1.jpg',
    '/assets/images/2023/10/003-1.jpg',
    '/assets/images/2023/10/004.jpg',
    '/assets/images/2023/10/006.jpg',
    '/assets/images/2023/10/009-1.jpg',
  ];

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % heroImages.length);
    }, 5000);
    return () => clearInterval(timer);
  }, []);

  const services = [
    { title: 'TEAM BUILDING', desc: 'Respek & Kolaborasi', icon: Users, image: '/assets/images/2023/10/A11-Team-Building.jpg', detail: 'Membangun respek dan menghargai perbedaan untuk meraih tujuan bersama.' },
    { title: 'FUN GAMES', desc: 'Seru & Aktif', icon: Smile, image: '/assets/images/2023/10/A12-Fun-Games.jpg', detail: 'Dipimpin Game Master profesional untuk menciptakan suasana seru yang bermakna.' },
    { title: 'GATHERING', desc: 'Kebersamaan Staf', icon: Sparkles, image: '/assets/images/2023/10/A12-Gathering.jpg', detail: 'Susunan acara gathering profesional untuk rewards pegawai terbaik perusahaan.' },
  ];

  const videos = [
    { title: 'Highlight', url: 'https://www.youtube.com/embed/J59m32QV0rM' },
    { title: 'Event', url: 'https://www.youtube.com/embed/4x8p17rAqSA' },
  ];

  const lokasiOutboundData = [
    { title: 'Marianna Resort', img: '/assets/images/2023/10/00-Marianna-Resort-Samosir-wonderfultoba_outbound-outbound_medan.jpg' },
    { title: 'The Hill Sibolangit', img: '/assets/images/2023/10/01-The-Hill-Resort-Sibolangit-wonderfultoba_outbound-outbound_medan.jpg' },
    { title: 'Grand Mutiara', img: '/assets/images/2023/10/02-Grand-Mutiara-Hotel-Berastagi-wonderfultoba_outbound-outbound_medan.jpg' },
    { title: 'Niagara Parapat', img: '/assets/images/2023/10/07-Hotel-Niagara-Parapat-wonderfultoba_outbound-outbound_medan.jpg' },
  ];

  const clients = [
    'Mandiri-taspen-wondefultoba-outbound-medan.png',
    'USU-wonderfultoba-outbound-medan.png',
    'Charoen-pokphand-wonderfultoba-outbound-medan.png',
    'Pelindo-1-wonderfultoba-outbound-medan.png',
    'Hyundai-wonderfultoba-outbound-medan.png',
    'bmw-wonderfultoba-outbound-medan.png'
  ].map(img => `/assets/images/2023/10/${img}`);

  return (
    <div className="font-sans bg-slate-50 min-h-screen overflow-hidden">
      <Helmet>
        <title>Wonderful Toba Outbound - Provider Terbaik Sumut</title>
      </Helmet>

      {/* Hero Section - Highly Responsive */}
      <section className="relative min-h-[500px] h-[80dvh] md:h-screen flex items-center justify-center">
        <div className="absolute inset-0 z-0">
          <AnimatePresence mode="wait">
            <motion.img
              key={currentSlide}
              src={heroImages[currentSlide]}
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              className="w-full h-full object-cover"
            />
          </AnimatePresence>
          <div className="absolute inset-0 bg-slate-900/60 transition-colors" />
        </div>
        
        <div className="relative z-10 max-w-4xl mx-auto px-6 text-center">
          <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}>
             <img src="/assets/images/2023/09/Logo-Wonderful-Toba-Outbound-White-1.png" alt="Logo" className="h-20 sm:h-28 md:h-36 mx-auto mb-8 drop-shadow-2xl" />
             <p className="text-base sm:text-xl md:text-2xl text-white font-medium mb-10 leading-relaxed shadow-sm">
                Solusi team building korporat <span className="text-toba-green font-black">profesional & berdampak</span> di Sumatera Utara.
             </p>
             <div className="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <Link to="/outbound/packages" className="w-full sm:w-auto bg-toba-green text-white px-8 py-3.5 md:py-4 rounded-full font-black text-xs md:text-sm tracking-widest uppercase shadow-2xl">
                  LIHAT PRICELIST
                </Link>
                <a href="https://wa.me/6281323888207" className="w-full sm:w-auto bg-white/10 backdrop-blur-md border border-white/40 text-white px-8 py-3.5 md:py-4 rounded-full font-black text-xs md:text-sm tracking-widest uppercase">
                  HUBUNGI KAMI
                </a>
             </div>
          </motion.div>
        </div>
      </section>

      {/* Services Section - Bento Grid Optimized */}
      <section className="py-20 px-4 bg-white">
        <div className="max-w-7xl mx-auto">
          <div className="text-center mb-12 sm:mb-16">
            <h2 className="text-3xl md:text-6xl font-black text-slate-900 leading-tight">Layanan <span className="text-toba-green uppercase">Andalan</span></h2>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {services.map((svc, i) => (
              <motion.div key={i} className="group relative rounded-[2rem] overflow-hidden bg-slate-900 aspect-[4/5] sm:aspect-auto sm:h-[450px]">
                <img src={svc.image} className="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-700" alt={svc.title} />
                <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent" />
                <div className="absolute bottom-0 p-8">
                  <div className="bg-toba-green w-10 h-10 rounded-xl flex items-center justify-center mb-4 text-white">
                    <svc.icon size={20} />
                  </div>
                  <h3 className="text-xl md:text-2xl font-black text-white mb-2 leading-tight">{svc.title}</h3>
                  <p className="text-slate-300 text-xs md:text-sm leading-relaxed">{svc.detail}</p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* Venues - Masonry style mobile fix */}
      <section className="py-20 px-4 bg-slate-50">
        <div className="max-w-7xl mx-auto">
          <div className="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
            <h2 className="text-3xl md:text-5xl font-black text-slate-900 leading-tight">Lokasi <span className="text-teal-500 underline decoration-toba-green">Premium</span></h2>
            <Link to="/outbound/packages" className="text-xs md:text-sm font-black text-toba-green uppercase flex items-center gap-2">Pilih Venue Lain <ArrowRight size={14}/></Link>
          </div>
          
          <div className="columns-2 md:columns-4 gap-4 space-y-4">
             {lokasiOutboundData.map((lokasi, i) => (
               <div key={i} className="relative rounded-2xl overflow-hidden break-inside-avoid shadow-lg bg-slate-200">
                  <img src={lokasi.img} alt={lokasi.title} className="w-full h-auto" />
                  <div className="absolute inset-0 bg-slate-900/40" />
                  <div className="absolute bottom-3 left-3 flex items-center gap-2">
                     <div className="bg-white/80 backdrop-blur p-1 rounded-full"><MapPin size={10} className="text-toba-green" /></div>
                     <span className="text-[10px] font-black text-white uppercase drop-shadow-lg">{lokasi.title}</span>
                  </div>
               </div>
             ))}
          </div>
        </div>
      </section>

      {/* Clients Section */}
      <section className="py-16 bg-white border-y border-slate-100">
         <div className="max-w-7xl mx-auto px-6">
            <div className="grid grid-cols-3 md:grid-cols-6 gap-8 items-center opacity-40">
               {clients.map((c, i) => <img key={i} src={c} className="h-6 md:h-10 object-contain mx-auto grayscale" alt="Client" />)}
            </div>
         </div>
      </section>

      {/* Final CTA - Aggressive mobile design */}
      <section className="py-12 md:py-24 px-4 bg-white">
         <div className="max-w-4xl mx-auto bg-toba-green rounded-[3rem] p-8 md:p-16 text-center text-white relative shadow-2xl">
            <Sparkles className="absolute top-10 left-10 opacity-20" size={60} />
            <h2 className="text-3xl md:text-5xl font-black mb-6 leading-tight tracking-tighter">Bikin Event Kantor<br/>Jadi Legendaris!</h2>
            <p className="text-sm md:text-lg mb-8 font-medium">Konsultasikan kebutuhan team building Anda dengan tim ahli Wonderful Toba.</p>
            <a href="https://wa.me/6281323888207" className="inline-flex bg-white text-toba-green px-10 py-4 rounded-full font-black text-sm uppercase shadow-xl hover:scale-105 transition-all">
                CHAT WHATSAPP <ArrowRight className="ml-2" />
            </a>
         </div>
      </section>

    </div>
  );
}
