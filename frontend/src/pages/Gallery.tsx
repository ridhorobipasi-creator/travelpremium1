import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Gallery } from '../types';
import { motion, AnimatePresence } from 'framer-motion';
import { Image as ImageIcon, Maximize2, X, Filter, Camera } from 'lucide-react'; // FIXED: lucide-react
import { cn } from '../utils/cn';
import { useLocation } from 'react-router-dom';
import { SkeletonBox } from '../components/Skeleton'; // FIXED: Named Import

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
    <div className="pt-32 pb-20 min-h-screen bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Section Header */}
        <div className="text-center mb-16 space-y-4">
          <motion.div 
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="inline-flex items-center space-x-2 px-4 py-2 bg-toba-green/10 text-toba-green rounded-full text-xs font-black uppercase tracking-widest"
          >
            <Camera size={14} />
            <span>Dokumentasi {category === 'outbound' ? 'Corporate' : 'Wisata'}</span>
          </motion.div>
          <h1 className="text-4xl md:text-6xl font-black text-slate-900 tracking-tight">
            Momen Terbaik <span className="text-toba-green">Kami</span>
          </h1>
        </div>

        {/* Grid Gallery */}
        <div className="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
          {loading ? (
             Array(8).fill(0).map((_, i) => (
               <SkeletonBox key={i} className="h-64" />
             ))
          ) : filteredItems.length > 0 ? (
            filteredItems.map((item) => (
              <motion.div
                layout
                key={item.id}
                className="group relative rounded-[2rem] overflow-hidden bg-slate-100 cursor-zoom-in break-inside-avoid"
                onClick={() => setSelectedImg(item)}
              >
                <img 
                  src={item.image_path.startsWith('http') ? item.image_path : `${import.meta.env.VITE_API_BASE_URL.replace('/api', '')}/storage/${item.image_path}`}
                  alt={item.title}
                  className="w-full h-auto transition-transform duration-700 group-hover:scale-110"
                />
              </motion.div>
            ))
          ) : (
            <div className="col-span-full py-20 text-center text-slate-400 font-bold uppercase tracking-widest">
              Belum ada foto di galeri ini.
            </div>
          )}
        </div>
      </div>

      <AnimatePresence>
        {selectedImg && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 z-[100] bg-slate-950/95 flex items-center justify-center p-10"
            onClick={() => setSelectedImg(null)}
          >
             <button className="absolute top-8 right-8 p-3 bg-white/10 text-white rounded-2xl" onClick={() => setSelectedImg(null)}>
                <X size={24} />
             </button>
             <img
               src={selectedImg.image_path.startsWith('http') ? selectedImg.image_path : `${import.meta.env.VITE_API_BASE_URL.replace('/api', '')}/storage/${selectedImg.image_path}`}
               className="max-w-full max-h-full object-contain rounded-3xl"
             />
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
