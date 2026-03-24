import { useState, useEffect } from 'react';
import { useParams, useNavigate, Link } from 'react-router-dom';
import api from '../lib/api';
import { BlogPost } from '../types';
import { motion } from 'framer-motion';
import { ArrowLeft, Calendar, User, Tag } from 'lucide-react';
import { Helmet } from 'react-helmet-async';

// Fallback posts (same as Blog.tsx)
const fallbackPosts: BlogPost[] = [
  { id: 'f1', title: '10 Alasan Mengapa Danau Toba Wajib Masuk Bucket List Anda', content: 'Danau Toba adalah danau vulkanik terbesar di dunia yang terletak di Sumatera Utara. Dengan luas 1.145 km² dan kedalaman hingga 505 meter, danau ini menyimpan keindahan alam yang luar biasa. Di tengahnya terdapat Pulau Samosir yang kaya akan budaya Batak. Setiap sudut danau ini menawarkan pemandangan yang memukau, dari tebing-tebing curam hingga hamparan air biru yang tenang. Budaya Batak yang kaya juga menjadi daya tarik tersendiri, dengan rumah adat Bolon, tari Tor-Tor, dan musik gondang yang masih lestari hingga kini.', authorId: 'admin', category: 'Destinasi', tags: ['danautoba', 'sumut', 'wisata'], image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800', createdAt: '2026-03-01T00:00:00Z' },
  { id: 'f2', title: 'Panduan Lengkap Trekking di Bukit Lawang untuk Pemula', content: 'Bukit Lawang adalah pintu masuk menuju Taman Nasional Gunung Leuser, salah satu hutan hujan tropis tertua di dunia. Di sini Anda bisa bertemu orangutan Sumatera liar dalam habitat aslinya. Panduan ini akan membantu Anda mempersiapkan perjalanan trekking yang aman dan menyenangkan. Pastikan membawa perlengkapan yang tepat, termasuk sepatu trekking, jas hujan, dan air minum yang cukup. Selalu ikuti panduan dari guide lokal yang berpengalaman untuk keselamatan Anda.', authorId: 'admin', category: 'Panduan', tags: ['bukitlawang', 'trekking', 'orangutan'], image: 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800', createdAt: '2026-02-20T00:00:00Z' },
  { id: 'f3', title: 'Mandi Lumpur Bersama Gajah Sumatera di Tangkahan', content: 'Tangkahan adalah surga tersembunyi di Langkat, Sumatera Utara. Kawasan ekowisata ini menawarkan pengalaman unik berinteraksi langsung dengan gajah Sumatera yang jinak. Anda bisa memandikan gajah di sungai jernih dan menikmati keindahan hutan tropis yang masih alami. Tangkahan juga dikenal sebagai "The Hidden Paradise" karena keindahan alamnya yang belum banyak terjamah wisatawan. Sungai Batang Serangan yang jernih mengalir di tengah hutan lebat menjadi tempat bermain yang sempurna bagi gajah-gajah jinak di sini.', authorId: 'admin', category: 'Petualangan', tags: ['tangkahan', 'gajah', 'ekowisata'], image: 'https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800', createdAt: '2026-02-10T00:00:00Z' },
  { id: 'f4', title: 'Kuliner Khas Batak yang Harus Dicoba Saat ke Sumut', content: 'Sumatera Utara tidak hanya kaya akan keindahan alam, tetapi juga memiliki kekayaan kuliner yang menggugah selera. Dari arsik ikan mas, saksang, hingga bika ambon yang manis, setiap hidangan menceritakan kekayaan budaya Batak yang telah diwariskan turun-temurun. Arsik adalah masakan ikan mas yang dimasak dengan bumbu kuning khas Batak, sementara saksang adalah olahan daging yang kaya rempah. Jangan lupa mencicipi naniura, ikan mas mentah yang dimasak dengan asam jungga, hidangan khas yang unik dan lezat.', authorId: 'admin', category: 'Kuliner', tags: ['kuliner', 'batak', 'sumut'], image: 'https://images.unsplash.com/photo-1555400038-63f5ba517a47?auto=format&fit=crop&q=80&w=800', createdAt: '2026-01-28T00:00:00Z' },
];

export default function BlogDetail() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [post, setPost] = useState<BlogPost | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!id) return;
    // Check fallback first for locally defined f1, f2, etc.
    const fallback = fallbackPosts.find(p => p.id === id);
    if (fallback) {
      setPost(fallback);
      setLoading(false);
      return;
    }
    // Fetch from API
    const fetch = async () => {
      try {
        const res = await api.get(`/posts/${id}`);
        setPost(res.data);
      } catch {
        navigate('/blog');
      } finally {
        setLoading(false);
      }
    };
    fetch();
  }, [id, navigate]);

  if (loading) return (
    <div className="min-h-screen flex items-center justify-center bg-slate-50">
      <div className="w-12 h-12 border-4 border-slate-200 border-t-toba-green rounded-full animate-spin" />
    </div>
  );

  if (!post) return null;

  return (
    <>
      <Helmet>
        <title>{post.title} – Wonderful Toba Blog</title>
        <meta name="description" content={post.content.slice(0, 160)} />
      </Helmet>

      <div className="bg-slate-50 min-h-screen pb-24">
        {/* Hero Image */}
        <div className="relative h-[50vh] overflow-hidden">
          <img src={post.image} alt={post.title} className="w-full h-full object-cover" referrerPolicy="no-referrer" />
          <div className="absolute inset-0 bg-gradient-to-b from-slate-900/30 to-slate-900/70" />
          <div className="absolute bottom-0 left-0 right-0 p-8 max-w-4xl mx-auto">
            <span className="inline-block px-3 py-1 bg-toba-green text-white text-xs font-bold uppercase tracking-wider rounded-full mb-4">
              {post.category}
            </span>
            <h1 className="text-3xl md:text-5xl font-black text-white leading-tight">{post.title}</h1>
          </div>
        </div>

        <div className="max-w-4xl mx-auto px-4 mt-10">
          {/* Back */}
          <Link to="/blog" className="inline-flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-8 transition-colors">
            <ArrowLeft size={18} /> Kembali ke Blog
          </Link>

          <motion.article initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }}
            className="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-slate-100">
            {/* Meta */}
            <div className="flex flex-wrap items-center gap-5 text-slate-400 text-sm mb-8 pb-8 border-b border-slate-100">
              <span className="flex items-center gap-1.5">
                <Calendar size={14} className="text-toba-green" />
                {new Date(post.createdAt).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}
              </span>
              <span className="flex items-center gap-1.5">
                <User size={14} className="text-toba-green" /> Admin
              </span>
              <div className="flex gap-2 flex-wrap">
                {post.tags.map(tag => (
                  <span key={tag} className="flex items-center gap-1 text-xs font-bold text-toba-green bg-toba-green/8 px-3 py-1 rounded-full">
                    <Tag size={10} /> #{tag}
                  </span>
                ))}
              </div>
            </div>

            {/* Content */}
            <div className="prose prose-slate max-w-none">
              {post.content.split('\n').map((para, i) => (
                <p key={i} className="text-slate-600 leading-relaxed font-medium mb-4 text-lg">{para}</p>
              ))}
            </div>
          </motion.article>

          {/* Related posts */}
          <div className="mt-10">
            <h3 className="text-xl font-black text-slate-900 mb-5">Artikel Lainnya</h3>
            <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
              {fallbackPosts.filter(p => p.id !== id).slice(0, 3).map(p => (
                <Link key={p.id} to={`/blog/${p.id}`}
                  className="bg-white rounded-[1.5rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-md transition-all group">
                  <div className="h-36 overflow-hidden">
                    <img src={p.image} alt={p.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" referrerPolicy="no-referrer" />
                  </div>
                  <div className="p-5">
                    <span className="text-[10px] font-bold text-toba-green uppercase tracking-wider">{p.category}</span>
                    <h4 className="font-black text-slate-900 text-sm mt-1 line-clamp-2 group-hover:text-toba-green transition-colors">{p.title}</h4>
                  </div>
                </Link>
              ))}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
