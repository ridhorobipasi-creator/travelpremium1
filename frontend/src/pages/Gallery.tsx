import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Gallery } from '../types';
import { motion, AnimatePresence } from 'framer-motion';
import { Image as ImageIcon, Maximize2, X, Filter, Camera } from 'lucide-react';
import { cn } from '../utils/cn';
import { useLocation } from 'react-router-dom';
import Skeleton from '../components/Skeleton';

export default function GalleryPage() {
  const [items, setItems] = useState<Gallery[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedImg, setSelectedImg] = useState<Gallery | null>(null);
  const [activeTab, setActiveTab] = useState('all');
  const location = useLocation();

  const category = location.pathname.includes('/outbound') ? 'outbound' : 'tour';

  useEffect(() => {
    fetchData();
  }, [category]);

  const fetchData = async () => {
    setLoading(true);
    try {
      const res = await api.get(`/galleries?scope=${category}`);
      setItems(res.data);
    } catch (error) {
      console.error('Error fetching gallery:', error);
    } finally {
      setLoading(false);
    }
  };

  const categories = ['all', ...Array.from(new Set(items.map(i => i.category).filter(Boolean)))];

  const filteredItems = activeTab === 'all' 
    ? items 
    : items.filter(i => i.category === activeTab);

  return (
    <div className="pt-32 pb-20 min-h-screen bg-white dark:bg-slate-950">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Section Header */}
        <div className="text-center mb-16 space-y-4">
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="inline-flex items-center space-x-2 px-4 py-2 bg-toba-green/10 text-toba-green rounded-full text-xs font-black uppercase tracking-widest"
          >
            <Camera size={14} />
            <span>Dokumentasi {category === 'outbound' ? 'Corporate' : 'Wisata'}</span>
          </motion.div>
          <motion.h1 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.1 }}
            className="text-4xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tight"
          >
            Momen Terbaik <span className="text-toba-green">Kami</span>
          </motion.h1>
          <motion.p 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.2 }}
            className="text-slate-500 max-w-2xl mx-auto font-medium"
          >
            Koleksi visual autentik dari perjalanan dan kegiatan yang telah kami laksanakan bersama ribuan pelanggan setia.
          </motion.p>
        </div>

        {/* Category Filter */}
        <div className="flex flex-wrap items-center justify-center gap-3 mb-12">
          {categories.map((cat, i) => (cat && (
            <button
              key={i}
              onClick={() => setActiveTab(cat)}
              className={cn(
                "px-6 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest transition-all border",
                activeTab === cat 
                  ? "bg-slate-900 text-white border-slate-900 shadow-xl shadow-slate-200 dark:shadow-none" 
                  : "bg-white text-slate-400 border-slate-100 hover:border-slate-200 hover:text-slate-600 dark:bg-slate-900 dark:border-slate-800"
              )}
            >
              {cat}
            </button>
          )))}
        </div>

        {/* Grid Gallery */}
        <div className="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
          {loading ? (
             Array(8).fill(0).map((_, i) => (
               <Skeleton key={i} height="h-64" className="rounded-[2rem]" />
             ))
          ) : filteredItems.length > 0 ? (
            filteredItems.map((item, index) => (
              <motion.div
                layout
                initial={{ opacity: 0, scale: 0.9 }}
                animate={{ opacity: 1, scale: 1 }}
                key={item.id}
                className="group relative rounded-[2rem] overflow-hidden bg-slate-100 dark:bg-slate-900 cursor-zoom-in break-inside-avoid"
                onClick={() => setSelectedImg(item)}
              >
                <img 
                  src={item.image_path.startsWith('http') ? item.image_path : `${import.meta.env.VITE_API_BASE_URL.replace('/api', '')}/storage/${item.image_path}`}
                  alt={item.title}
                  className="w-full h-auto transition-transform duration-700 group-hover:scale-110"
                />
                
                {/* Overlay */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                  <p className="text-white font-black text-lg tracking-tight leading-tight mb-1">{item.title}</p>
                  <p className="text-white/60 text-[10px] font-bold uppercase tracking-widest">{item.category || 'General'}</p>
                  <div className="absolute top-6 right-6 w-10 h-10 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-white">
                    <Maximize2 size={18} />
                  </div>
                </div>
              </motion.div>
            ))
          ) : (
            <div className="col-span-full py-20 text-center">
              <div className="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                <ImageIcon size={32} />
              </div>
              <p className="text-slate-400 font-bold uppercase tracking-widest">Belum ada foto di galeri ini.</p>
            </div>
          )}
        </div>
      </div>

      {/* Lightbox Modal */}
      <AnimatePresence>
        {selectedImg && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-[100] bg-slate-950/95 flex items-center justify-center p-4 md:p-10"
            onClick={() => setSelectedImg(null)}
          >
             <button 
               className="absolute top-8 right-8 p-3 bg-white/10 text-white rounded-2xl hover:bg-white/20 transition-all z-[110]"
               onClick={() => setSelectedImg(null)}
             >
                <X size={24} />
             </button>

             <motion.img
               initial={{ scale: 0.9, y: 20 }}
               animate={{ scale: 1, y: 0 }}
               exit={{ scale: 0.9, y: 20 }}
               src={selectedImg.image_path.startsWith('http') ? selectedImg.image_path : `${import.meta.env.VITE_API_BASE_URL.replace('/api', '')}/storage/${selectedImg.image_path}`}
               className="max-w-full max-h-full object-contain rounded-3xl shadow-2xl"
               onClick={(e) => e.stopPropagation()}
             />

             <div className="absolute bottom-10 left-10 right-10 text-center text-white pointer-events-none">
                <h3 className="text-2xl font-black mb-1">{selectedImg.title}</h3>
                <p className="text-white/60 text-xs font-bold uppercase tracking-widest">{selectedImg.category}</p>
             </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
