import { useState, useEffect } from 'react';
import api from '../lib/api';
import { Booking } from '../types';
import { Search, Filter, CheckCircle, XCircle, Clock, Eye, Calendar, User, CreditCard, MoreHorizontal, X, Download } from 'lucide-react';
import { motion } from 'framer-motion';
import { cn } from '../utils/cn';
import { toast } from 'sonner';
import { useLocation } from 'react-router-dom';

export default function AdminBookings() {
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState(true);
  const [filterStatus, setFilterStatus] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedBooking, setSelectedBooking] = useState<any>(null);
  
  const location = useLocation();
  const category = location.pathname.includes('/outbound') ? 'outbound' : 'tour';

  useEffect(() => {
    fetchData();
  }, [category]);

  const fetchData = async () => {
    setLoading(true);
    try {
      // Pass scope to backend (Priority: Pemisahan Data)
      const res = await api.get(`/bookings?scope=${category}`);
      const mappedData = res.data.map((b: any) => ({
        ...b,
        startDate: b.start_date,
        endDate: b.end_date,
        totalPrice: Number(b.total_price),
        customerDetails: {
          name: b.customer_name,
          email: b.customer_email,
          phone: b.customer_phone
        }
      }));
      setBookings(mappedData);
    } catch (error) {
      console.error('Error fetching bookings:', error);
      toast.error('Gagal memuat reservasi');
    } finally {
      setLoading(false);
    }
  };

  /**
   * Priority 3: CSV Export for Corporate Clients
   * Generates a downloadable CSV of all filtered bookings.
   */
  const handleExportCSV = () => {
    if (filteredBookings.length === 0) {
      toast.error('Tidak ada data untuk diekspor');
      return;
    }

    const headers = ['ID', 'Customer Name', 'Email', 'Phone', 'Item Name', 'Start Date', 'End Date', 'Total Price', 'Status', 'Notes'];
    const rows = filteredBookings.map(b => [
      b.id,
      b.customerDetails.name,
      b.customerDetails.email,
      `'${b.customerDetails.phone}`, // Force string with single quote for excel
      b.itemName || 'N/A',
      b.startDate,
      b.endDate,
      b.totalPrice,
      b.status,
      (b as any).notes || ''
    ]);

    const csvContent = "data:text/csv;charset=utf-8," 
      + headers.join(",") + "\n"
      + rows.map(e => e.map(val => `"${val}"`).join(",")).join("\n");

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `Report_Bookings_${category}_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    toast.success(`Berhasil mengunduh laporan ${category}`);
  };

  const handleStatusUpdate = async (id: string | number, status: string) => {
    try {
      await api.put(`/bookings/${id}`, { status });
      toast.success('Status reservasi diperbarui');
      fetchData();
    } catch (error) {
      console.error('Error updating status:', error);
      toast.error('Gagal memperbarui status');
    }
  };

  const filteredBookings = bookings
    .filter(b => filterStatus === 'all' || b.status === filterStatus)
    .filter(b => {
      const name = b.customerDetails?.name || (b as any).customer_name || '';
      return name.toLowerCase().includes(searchQuery.toLowerCase());
    });

  return (
    <div className="space-y-10 pb-12 animate-in fade-in duration-500">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <h2 className="text-4xl font-bold text-slate-900 tracking-tight mb-2">
             Pemesanan {category === 'outbound' ? 'Corporate' : 'Tour'}
          </h2>
          <p className="text-slate-500 font-medium">Pantau dan kelola semua pesanan pelanggan pada modul {category}.</p>
        </div>
        <div className="flex items-center space-x-3">
          <button 
            onClick={handleExportCSV}
            className={cn(
                "flex items-center space-x-2 px-6 py-4 rounded-2xl font-bold text-sm text-white shadow-xl transition-all group",
                category === 'outbound' ? "bg-toba-green hover:bg-toba-green/90 shadow-toba-green/20" : "bg-obaja-blue hover:bg-obaja-blue/90 shadow-blue-100"
            )}
          >
            <Download size={18} className="group-hover:translate-y-0.5 transition-transform" />
            <span>Ekspor Laporan CSV</span>
          </button>
        </div>
      </div>

      {/* Tabs Filter Status */}
      <div className="flex items-center space-x-3 bg-white p-1.5 rounded-2xl border border-slate-100 shadow-sm w-fit">
        {[
          { id: 'all', label: 'Semua', color: 'bg-slate-900' },
          { id: 'pending', label: 'Menunggu', color: 'bg-amber-500' },
          { id: 'confirmed', label: 'Sukses', color: 'bg-emerald-500' },
          { id: 'cancelled', label: 'Dibatalkan', color: 'bg-rose-500' }
        ].map((tab) => (
          <button 
            key={tab.id}
            onClick={() => setFilterStatus(tab.id)}
            className={cn(
              "px-6 py-2.5 rounded-xl text-xs font-bold transition-all",
              filterStatus === tab.id ? `${tab.color} text-white shadow-lg` : "text-slate-400 hover:text-slate-600 hover:bg-slate-50"
            )}
          >
            {tab.label}
          </button>
        ))}
      </div>

      {/* Filters & Search */}
      <div className="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div className="relative w-full md:w-96">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input
            type="text"
            placeholder="Cari nama pelanggan..."
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-toba-green/30 font-medium transition-all"
          />
        </div>
        <div className="flex items-center space-x-3 w-full md:w-auto">
          <div className="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
             Total: {filteredBookings.length} Reservasi
          </div>
        </div>
      </div>
      
      {/* Table Container */}
      <div className="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50/50">
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Pelanggan</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Layanan</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Jadwal</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Total Bayar</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Status</th>
                <th className="px-8 py-6 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {loading ? (
                <tr>
                  <td colSpan={6} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className={cn("w-12 h-12 border-4 rounded-full animate-spin mb-4", category === 'outbound' ? "border-toba-green/20 border-t-toba-green" : "border-blue-100 border-t-obaja-blue")}></div>
                      <p className="text-slate-400 font-medium">Sinkronisasi reservasi {category}...</p>
                    </div>
                  </td>
                </tr>
              ) : filteredBookings.length > 0 ? filteredBookings.map((booking, index) => (
                <motion.tr 
                  initial={{ opacity: 0, y: 10 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: index * 0.05 }}
                  key={booking.id} 
                  className="hover:bg-slate-50/50 transition-colors group"
                >
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-4">
                      <div className={cn("w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 group-hover:text-white transition-all duration-300", category === 'outbound' ? "group-hover:bg-toba-green" : "group-hover:bg-obaja-blue")}>
                        <User size={18} />
                      </div>
                      <div>
                        <p className="font-bold text-slate-900 group-hover:text-toba-green transition-colors">{booking.customerDetails.name}</p>
                        <p className="text-xs text-slate-400 font-medium">{booking.customerDetails.email}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex flex-col gap-1.5">
                      <span className={cn(
                        "w-fit px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border",
                        booking.type === 'package' ? 'bg-blue-50 text-obaja-blue border-blue-100' : 'bg-orange-50 text-obaja-orange border-orange-100'
                      )}>
                        {booking.type === 'package' ? 'Paket' : 'Rental'}
                      </span>
                      <p className="text-sm font-black text-slate-700 truncate max-w-[150px]">{booking.itemName || 'N/A'}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex flex-col">
                      <div className="flex items-center space-x-1.5 text-slate-600 text-sm font-bold">
                        <Calendar size={14} className="text-toba-green" />
                        <span>{booking.startDate}</span>
                      </div>
                      <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 ml-5">s/d {booking.endDate}</p>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <p className="font-black text-slate-900">Rp {booking.totalPrice.toLocaleString('id-ID')}</p>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center space-x-2">
                      <span className={cn(
                        "px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest",
                        booking.status === 'confirmed' ? 'bg-emerald-50 text-emerald-600' : 
                        booking.status === 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600'
                      )}>
                        {booking.status === 'confirmed' ? 'Sukses' : booking.status === 'pending' ? 'Menunggu' : 'Batal'}
                      </span>
                    </div>
                  </td>
                  <td className="px-8 py-6 text-right">
                    <div className="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      {booking.status === 'pending' && (
                        <>
                          <button 
                            onClick={() => handleStatusUpdate(booking.id, 'confirmed')}
                            className="p-2.5 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all border border-transparent hover:border-emerald-100"
                            title="Konfirmasi Pesanan"
                          >
                            <CheckCircle size={18} />
                          </button>
                        </>
                      )}
                      <button
                        onClick={() => setSelectedBooking(booking)}
                        className="p-2.5 text-slate-400 hover:text-toba-green hover:bg-emerald-50 rounded-xl transition-all border border-transparent hover:border-emerald-100"
                      >
                        <Eye size={18} />
                      </button>
                    </div>
                  </td>
                </motion.tr>
              )) : (
                <tr>
                  <td colSpan={6} className="px-8 py-20 text-center">
                    <div className="flex flex-col items-center">
                      <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-200">
                        <Calendar size={32} />
                      </div>
                      <p className="text-slate-400 font-medium">Bilik reservasi {category} kosong.</p>
                    </div>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Detail Modal */}
      {selectedBooking && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md">
          <motion.div
            initial={{ opacity: 0, scale: 0.9, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            className="bg-white rounded-[3rem] w-full max-w-lg overflow-hidden shadow-2xl"
          >
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <h3 className="text-2xl font-bold text-slate-900">Detail Reservasi</h3>
              <button onClick={() => setSelectedBooking(null)} className="p-2 hover:bg-white rounded-xl text-slate-400 hover:text-slate-900 transition-all">
                <X size={22} />
              </button>
            </div>
            <div className="p-8 space-y-5">
              <div className="grid grid-cols-2 gap-4">
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                  <p className="font-bold text-slate-900">{selectedBooking.customerDetails.name}</p>
                </div>
                <div className="bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Telepon</p>
                  <p className="font-bold text-slate-900">{selectedBooking.customerDetails.phone || '-'}</p>
                </div>
                <div className="col-span-2 bg-slate-50 rounded-2xl p-4">
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</p>
                  <p className="font-bold text-slate-900">{selectedBooking.customerDetails.email}</p>
                </div>
                <div className="col-span-2 bg-toba-green/5 rounded-2xl p-4">
                    <p className="text-[10px] font-bold text-toba-green uppercase tracking-widest mb-1">Produk/Paket</p>
                    <p className="font-black text-slate-900 text-lg">{selectedBooking.itemName}</p>
                </div>
              </div>
              <div className="bg-slate-900 rounded-2xl p-5 flex justify-between items-center">
                <p className="font-bold text-slate-400">Total Reservasi</p>
                <p className="text-2xl font-black text-white">Rp {selectedBooking.totalPrice.toLocaleString('id-ID')}</p>
              </div>
            </div>
          </motion.div>
        </div>
      )}
    </div>
  );
}
