import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Booking } from '../types';
import { 
  TrendingUp, 
  Users, 
  Package as PackageIcon, 
  Car as CarIcon, 
  Calendar,
  ArrowUpRight,
  ArrowDownRight,
  MoreVertical,
  Download,
  Filter as FilterIcon,
  RefreshCcw,
  FileText
} from 'lucide-react';
import { useNavigate } from 'react-router-dom';
import { 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip, 
  ResponsiveContainer,
  AreaChart,
  Area
} from 'recharts';
import { motion } from 'framer-motion';
import { cn } from '../utils/cn';

export default function AdminDashboard() {
  const navigate = useNavigate();
  const [stats, setStats] = useState({
    totalBookings: 0,
    totalRevenue: 0,
    activePackages: 0,
    totalBlogs: 0
  });
  const [recentBookings, setRecentBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchStats();
  }, []);

  const fetchStats = async () => {
    setLoading(true);
    try {
      const isPublic = !localStorage.getItem('auth_token');
      const [bookingsRes, packagesRes, blogRes] = await Promise.all([
        api.get(isPublic ? '/bookings/public' : '/bookings'),
        api.get('/packages'),
        api.get(isPublic ? '/blogs' : '/blogs')
      ]);

      const bookings = Array.isArray(bookingsRes.data) ? bookingsRes.data : [];
      const packagesData = Array.isArray(packagesRes.data) ? packagesRes.data : [];
      const blogsData = Array.isArray(blogRes.data) ? blogRes.data : [];
      
      const revenue = bookings.reduce((acc: number, curr: any) => acc + (Number(curr.total_price) || 0), 0);

      setStats({
        totalBookings: bookings.length,
        totalRevenue: revenue,
        activePackages: packagesData.filter((d: any) => d.status === 'active').length,
        totalBlogs: blogsData.length
      });

      // Map snake_case to frontend expected format
      const mappedBookings = bookings.slice(0, 5).map((b: any) => ({
        ...b,
        startDate: b.start_date,
        totalPrice: Number(b.total_price),
        itemName: b.item_details?.name || (b.type === 'package' ? 'Paket Wisata' : 'Rental Mobil'),
        itemImage: b.item_details?.image,
        customerDetails: {
          name: b.customer_name,
        }
      }));
      setRecentBookings(mappedBookings);
    } catch (error) {
      console.error('Error fetching stats:', error);
    } finally {
      setLoading(false);
    }
  };

  const chartData = [
    { name: 'Sen', value: 400 },
    { name: 'Sel', value: 300 },
    { name: 'Rab', value: 600 },
    { name: 'Kam', value: 800 },
    { name: 'Jum', value: 500 },
    { name: 'Sab', value: 900 },
    { name: 'Min', value: 1100 },
  ];

  const StatCard = ({ title, value, icon: Icon, trend, color, delay, onClick }: any) => (
    <motion.div 
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ delay }}
      onClick={onClick}
      className="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group cursor-pointer"
    >
      <div className="flex justify-between items-start mb-6">
        <div className={cn("w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:rotate-6 shadow-lg shadow-slate-100/50", color)}>
          <Icon size={26} />
        </div>
        <div className={cn(
          "flex items-center space-x-1 px-3 py-1 rounded-full text-[10px] font-black tracking-tight uppercase",
          trend > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'
        )}>
          <span>{trend > 0 ? '+' : ''}{trend}%</span>
          {trend > 0 ? <ArrowUpRight size={12} /> : <ArrowDownRight size={12} />}
        </div>
      </div>
      <div>
        <p className="text-slate-400 font-bold text-[10px] uppercase tracking-[0.2em] mb-1.5 ml-1">{title}</p>
        <h3 className="text-2xl font-black text-slate-900 tracking-tight ml-1">{value}</h3>
      </div>
    </motion.div>
  );

  return (
    <div className="space-y-10 pb-12 animate-in fade-in duration-700">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-4xl font-black text-slate-900 tracking-tight mb-2">Pusat Utama</h2>
          <p className="text-slate-500 font-medium italic">"Teruslah berinovasi untuk memberikan kenyamanan terbaik bagi tamu Anda."</p>
        </div>
        <div className="flex items-center space-x-4">
          <button
            onClick={fetchStats}
            className="p-4 bg-white border border-slate-100 text-slate-400 hover:text-obaja-blue rounded-2xl transition-all shadow-sm hover:shadow-md"
          >
            <RefreshCcw size={20} className={loading ? 'animate-spin' : ''} />
          </button>
          <button
            onClick={() => {
              const data = JSON.stringify(recentBookings, null, 2);
              const blob = new Blob([data], { type: 'application/json' });
              const url = URL.createObjectURL(blob);
              const a = document.createElement('a');
              a.href = url;
              a.download = 'laporan-operasional.json';
              a.click();
              URL.revokeObjectURL(url);
            }}
            className="flex items-center space-x-2 px-10 py-4 bg-obaja-blue text-white rounded-2xl font-bold hover:bg-obaja-blue/90 transition-all shadow-xl shadow-blue-100 group"
          >
            <Download size={18} className="transition-transform group-hover:-translate-y-1" />
            <span>Unduh Laporan</span>
          </button>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <StatCard 
          title="Estimasi Omzet" 
          value={`Rp ${stats.totalRevenue.toLocaleString('id-ID')}`} 
          icon={TrendingUp} 
          trend={12.5}
          color="bg-obaja-blue text-white"
          delay={0.1}
          onClick={() => navigate('/admin/bookings')}
        />
        <StatCard 
          title="Total Reservasi" 
          value={stats.totalBookings} 
          icon={Calendar} 
          trend={8.2}
          color="bg-emerald-500 text-white"
          delay={0.2}
          onClick={() => navigate('/admin/bookings')}
        />
        <StatCard 
          title="Paket Aktif" 
          value={stats.activePackages} 
          icon={PackageIcon} 
          trend={-2.4}
          color="bg-amber-500 text-white"
          delay={0.3}
          onClick={() => navigate('/admin/packages')}
        />
        <StatCard 
          title="Artikel Blog" 
          value={stats.totalBlogs} 
          icon={FileText} 
          trend={15.3}
          color="bg-slate-900 text-white"
          delay={0.4}
          onClick={() => navigate('/admin/blog')}
        />
      </div>

      {/* Main Content Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
        {/* Chart Section */}
        <motion.div 
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.5 }}
          className="lg:col-span-8 bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 relative overflow-hidden group"
        >
          <div className="absolute top-0 right-0 p-8">
             <FilterIcon size={40} className="text-slate-50 opacity-0 group-hover:opacity-100 transition-opacity rotate-12" />
          </div>
          <div className="flex justify-between items-center mb-12 relative z-10">
            <div>
              <h3 className="text-2xl font-black text-slate-900 tracking-tight mb-1">Tren Pendapatan</h3>
              <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Aktivitas finansial 7 hari terakhir</p>
            </div>
            <div className="flex items-center space-x-3 bg-slate-50 p-1.5 rounded-2xl border border-slate-100">
               <button className="px-6 py-2 bg-white text-slate-900 rounded-xl text-xs font-black shadow-sm transition-all">7 HARI</button>
               <button className="px-6 py-2 text-slate-400 hover:text-slate-900 rounded-xl text-xs font-black transition-all">30 HARI</button>
            </div>
          </div>
          <div className="h-96 relative z-10">
            <ResponsiveContainer width="100%" height="100%">
              <AreaChart data={chartData}>
                <defs>
                  <linearGradient id="colorValue" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#005696" stopOpacity={0.15}/>
                    <stop offset="95%" stopColor="#005696" stopOpacity={0}/>
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                <XAxis 
                  dataKey="name" 
                  axisLine={false} 
                  tickLine={false} 
                  tick={{fill: '#94a3b8', fontSize: 11, fontWeight: 700}} 
                  dy={15}
                />
                <YAxis 
                  axisLine={false} 
                  tickLine={false} 
                  tick={{fill: '#94a3b8', fontSize: 11, fontWeight: 700}} 
                  dx={-10}
                />
                <Tooltip 
                  contentStyle={{
                    backgroundColor: '#fff', 
                    borderRadius: '24px', 
                    border: 'none', 
                    boxShadow: '0 25px 50px -12px rgb(0 0 0 / 0.15)',
                    padding: '20px'
                  }}
                  itemStyle={{fontWeight: '900', color: '#005696', fontSize: '14px'}}
                  labelStyle={{fontWeight: '900', color: '#0f172a', marginBottom: '8px', fontSize: '14px', letterSpacing: '0.025em'}}
                />
                <Area 
                  type="monotone" 
                  dataKey="value" 
                  stroke="#005696" 
                  strokeWidth={5} 
                  fillOpacity={1} 
                  fill="url(#colorValue)" 
                  animationDuration={2000}
                />
              </AreaChart>
            </ResponsiveContainer>
          </div>
        </motion.div>

        {/* Recent Activity Section */}
        <motion.div 
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.6 }}
          className="lg:col-span-4 bg-white p-10 rounded-[3rem] shadow-sm border border-slate-100 flex flex-col"
        >
          <div className="flex justify-between items-center mb-10">
            <h3 className="text-2xl font-black text-slate-900 tracking-tight">Katalog Terbaru</h3>
            <div className="p-2.5 bg-slate-50 rounded-xl text-slate-400">
              <MoreVertical size={18} />
            </div>
          </div>
          <div className="space-y-7 flex-1">
            {recentBookings.length > 0 ? recentBookings.map((booking, i) => (
              <div key={i} className="flex items-center justify-between group cursor-pointer">
                <div className="flex items-center space-x-4">
                  <div className={cn(
                    "w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-300 shadow-sm",
                    booking.type === 'package' ? "bg-blue-50 text-obaja-blue" : "bg-orange-50 text-obaja-orange"
                  )}>
                    {booking.type === 'package' ? <PackageIcon size={20} /> : <CarIcon size={20} />}
                  </div>
                  <div>
                    <p className="font-extrabold text-slate-900 text-sm group-hover:text-obaja-blue transition-colors">{booking.customerDetails.name}</p>
                    <div className="flex items-center space-x-2 mt-0.5">
                       <span className="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{booking.itemName}</span>
                       <span className="text-[10px] text-slate-200">•</span>
                       <span className="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{booking.startDate}</span>
                    </div>
                  </div>
                </div>
                <div className="text-right">
                  <p className="font-black text-slate-900 text-sm mb-1">Rp {booking.totalPrice.toLocaleString('id-ID')}</p>
                  <span className={cn(
                    "text-[8px] font-black uppercase tracking-[0.2em] px-2.5 py-1 rounded-lg border",
                    booking.status === 'confirmed' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
                    booking.status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-rose-50 text-rose-600 border-rose-100'
                  )}>
                    {booking.status}
                  </span>
                </div>
              </div>
            )) : (
              <div className="text-center py-20">
                <div className="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                  <RefreshCcw size={28} />
                </div>
                <p className="text-slate-400 font-bold text-sm">Tidak ada aktivitas terbaru.</p>
              </div>
            )}
          </div>
          <button
            onClick={() => navigate('/admin/bookings')}
            className="w-full mt-10 py-4 bg-slate-50 text-slate-500 hover:bg-obaja-blue hover:text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all border border-slate-100 hover:border-obaja-blue shadow-sm hover:shadow-lg"
          >
            LIHAT SELURUH AKTIVITAS
          </button>
        </motion.div>
      </div>
    </div>
  );
}
