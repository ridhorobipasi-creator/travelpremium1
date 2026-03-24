import { useState, useEffect } from 'react';
import api from '../lib/api';
import { City } from '../types';
import { useNavigate, useParams } from 'react-router-dom';
import { 
  Package as PackageIcon, Save, DollarSign, Calendar, MapPin, 
  ArrowLeft, FileText, CheckCircle, Globe, LayoutList, Image as ImageIcon,
  Map, Camera
} from 'lucide-react';
import { useForm, useFieldArray } from 'react-hook-form';
import { toast } from 'sonner';

export default function AdminPackageCreate() {
  const { id } = useParams();
  const [cities, setCities] = useState<City[]>([]);
  const [activeTab, setActiveTab] = useState('info');
  const navigate = useNavigate();

  const { register, handleSubmit, control, reset } = useForm<any>({
    defaultValues: {
      status: 'active',
      is_featured: false,
      sort_order: 0,
      pricing_details: [{ pax: '', price_per_person: '' }],
      itinerary: [{ day: '', title: '', description: '' }],
      translations: { en: {}, ms: {} },
      includes: '',
      excludes: ''
    }
  });

  const { fields: pricingFields, append: appendPricing, remove: removePricing } = useFieldArray({
    control,
    name: "pricing_details"
  });

  const { fields: itineraryFields, append: appendItinerary, remove: removeItinerary } = useFieldArray({
    control,
    name: "itinerary"
  });

  useEffect(() => {
    api.get('/cities').then(res => setCities(res.data)).catch(console.error);
    
    if (id) {
      api.get(`/packages/${id}`).then(res => {
        const pkg = res.data;
        reset({
          ...pkg,
          includes: pkg.includes?.join(', ') || '',
          excludes: pkg.excludes?.join(', ') || '',
          image: pkg.images?.[0] || '',
        });
      }).catch(err => {
        console.error('Error fetching package:', err);
        toast.error('Gagal mengambil data paket');
      });
    }
  }, [id, reset]);

  const onSubmit = async (data: any) => {
    try {
      const includesArray = data.includes ? data.includes.split(',').map((s: string) => s.trim()) : [];
      const excludesArray = data.excludes ? data.excludes.split(',').map((s: string) => s.trim()) : [];
      
      const payload = {
        name: data.name,
        location_tag: data.location_tag,
        pre_order_info: data.pre_order_info,
        duration: data.duration,
        city_id: data.city_id,
        short_description: data.short_description,
        description: data.description,
        price: Number(data.price), // Fallback legacy
        child_price: Number(data.child_price),
        price_display: data.price_display,
        pricing_details: data.pricing_details.filter((p: any) => p.pax && p.price_per_person),
        itinerary: data.itinerary.filter((i: any) => i.day && i.title),
        itinerary_text: data.itinerary_text,
        drone_price: Number(data.drone_price),
        drone_location: data.drone_location,
        includes: includesArray,
        excludes: excludesArray,
        notes: data.notes,
        status: data.status,
        is_featured: data.is_featured,
        sort_order: Number(data.sort_order),
        meta_title: data.meta_title,
        meta_description: data.meta_description,
        translations: data.translations,
        images: [data.image || 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'],
      };

      if (id) {
        await api.put(`/packages/${id}`, payload);
        toast.success('Paket Wisata berhasil diperbarui');
      } else {
        await api.post('/packages', payload);
        toast.success('Paket Wisata baru berhasil ditambahkan');
      }
      navigate('/admin/packages');
    } catch (error) {
      console.error('Error saving package:', error);
      toast.error('Gagal menyimpan paket wisata');
    }
  };

  const tabs = [
    { id: 'info', label: 'Identitas Paket', icon: <FileText size={18} /> },
    { id: 'harga', label: 'Harga', icon: <DollarSign size={18} /> },
    { id: 'itinerary', label: 'Itinerary', icon: <Map size={18} /> },
    { id: 'fasilitas', label: 'Fasilitas & Catatan', icon: <CheckCircle size={18} /> },
    { id: 'seo', label: 'Status & SEO', icon: <Globe size={18} /> },
    { id: 'bahasa', label: 'Terjemahan', icon: <LayoutList size={18} /> },
  ];

  return (
    <div className="max-w-6xl mx-auto pb-12">
      <button onClick={() => navigate('/admin/packages')} className="flex items-center gap-2 text-slate-500 hover:text-obaja-blue font-bold mb-6 transition-colors">
        <ArrowLeft size={18} /> Kembali ke Manajemen Paket
      </button>

      <div className="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-slate-100">
        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
          <div>
            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-2">
              {id ? 'Edit Paket Wisata' : 'Tambah Paket Wisata'}
            </h2>
            <p className="text-slate-500 font-medium">Buat paket wisata menarik dengan fitur lengkap.</p>
          </div>
          <button
            onClick={handleSubmit(onSubmit)}
            className="bg-obaja-blue text-white px-10 py-4 rounded-2xl font-bold hover:bg-obaja-blue/90 transition-all shadow-xl shadow-blue-100 flex items-center space-x-2"
          >
            <Save size={20} />
            <span>{id ? 'Simpan Perubahan' : 'Simpan Paket Wisata'}</span>
          </button>
        </div>

        {/* Tab Navigation */}
        <div className="flex overflow-x-auto hide-scrollbar border-b border-slate-100 mb-8 space-x-2">
          {tabs.map(tab => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`flex items-center space-x-2 px-6 py-4 border-b-2 font-bold transition-all whitespace-nowrap ${activeTab === tab.id ? 'border-obaja-blue text-obaja-blue' : 'border-transparent text-slate-400 hover:text-slate-600'}`}
            >
              {tab.icon}
              <span>{tab.label}</span>
            </button>
          ))}
        </div>

        <form className="space-y-8">
          {/* IDENTITAS PAKET */}
          {activeTab === 'info' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Paket</label>
                  <div className="relative">
                    <PackageIcon className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      {...register('name', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900"
                      placeholder="Contoh: Explore Danau Toba 3D2N"
                    />
                  </div>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Kota Destinasi</label>
                  <div className="relative">
                    <MapPin className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <select
                      {...register('city_id', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 appearance-none"
                    >
                      <option value="">Pilih Kota</option>
                      {cities.map(city => (
                        <option key={city.id} value={city.id}>{city.name}</option>
                      ))}
                    </select>
                  </div>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Durasi</label>
                  <div className="relative">
                    <Calendar className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      {...register('duration', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900"
                      placeholder="3 Hari 2 Malam"
                    />
                  </div>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Tag Lokasi (Badge atas foto)</label>
                  <input
                    {...register('location_tag')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900"
                    placeholder="Contoh: Samosir, Sumatera Utara"
                  />
                </div>
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Pre-order Info (Opsional)</label>
                <input
                  {...register('pre_order_info')}
                  className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Contoh: Booking 7 hari sebelum..."
                />
              </div>

              <div className="space-y-3 pt-4 border-t border-slate-100">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Deskripsi Singkat</label>
                <textarea
                  {...register('short_description')}
                  rows={2}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Tampil ringkas di bawah harga..."
                />
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Deskripsi Lengkap</label>
                <textarea
                  {...register('description')}
                  rows={6}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Penjelasan lengkap mengenai perjalanan ini..."
                />
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">URL Cover Image</label>
                <div className="relative">
                  <ImageIcon className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                  <input
                    {...register('image')}
                    className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium text-slate-900"
                    placeholder="https://example.com/tour.jpg"
                  />
                </div>
              </div>
            </div>
          )}

          {/* HARGA */}
          {activeTab === 'harga' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="bg-slate-50 p-6 rounded-2xl space-y-4 mb-6">
                <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-2">Tabel Harga per Orang</h4>
                {pricingFields.map((field, index) => (
                  <div key={field.id} className="flex gap-4">
                    <div className="flex-1">
                      <input
                        {...register(`pricing_details.${index}.pax`)}
                        placeholder="Jumlah Orang (mis. 2)"
                        className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                    </div>
                    <div className="flex-1">
                      <input
                        {...register(`pricing_details.${index}.price_per_person`)}
                        placeholder="Harga / Orang (Rp)"
                        className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                    </div>
                    <button type="button" onClick={() => removePricing(index)} className="px-4 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors font-bold">Hapus</button>
                  </div>
                ))}
                <button type="button" onClick={() => appendPricing({ pax: '', price_per_person: '' })} className="text-obaja-blue font-bold text-sm bg-blue-50/50 px-6 py-3 rounded-xl hover:bg-blue-100 transition-colors">
                  + Tambah Baris Harga
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Harga Dasar (Default)</label>
                  <div className="relative">
                    <DollarSign className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      type="number"
                      {...register('price', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900"
                      placeholder="500000"
                    />
                  </div>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Harga Anak (8 Thn Kebawah)</label>
                  <div className="relative">
                    <DollarSign className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      type="number"
                      {...register('child_price')}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900"
                      placeholder="250000"
                    />
                  </div>
                </div>
              </div>
              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Teks Harga Custom</label>
                <input
                  {...register('price_display')}
                  className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Mulai dari Rp 500.000 / pax"
                />
              </div>
            </div>
          )}

          {/* ITINERARY */}
          {activeTab === 'itinerary' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="bg-slate-50 p-6 rounded-2xl space-y-4">
                <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-2">Jadwal Harian</h4>
                {itineraryFields.map((field, index) => (
                  <div key={field.id} className="bg-white p-4 rounded-xl border border-slate-100 flex flex-col gap-3">
                    <div className="flex justify-between items-center">
                      <span className="font-bold text-slate-900">Hari {index + 1}</span>
                      <button type="button" onClick={() => removeItinerary(index)} className="text-rose-500 font-bold text-xs p-2">Hapus Hari</button>
                    </div>
                    <div className="grid grid-cols-4 gap-4">
                      <input
                        {...register(`itinerary.${index}.day`)}
                        placeholder="Hari ke-"
                        className="col-span-1 px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                      <input
                        {...register(`itinerary.${index}.title`)}
                        placeholder="Judul / Destinasi Utama"
                        className="col-span-3 px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-obaja-blue font-medium"
                      />
                    </div>
                    <textarea
                      {...register(`itinerary.${index}.description`)}
                      placeholder="Jelaskan aktivitas hari ini secara detail..."
                      rows={2}
                      className="w-full px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-obaja-blue font-medium"
                    />
                  </div>
                ))}
                <button type="button" onClick={() => appendItinerary({ day: '', title: '', description: '' })} className="text-obaja-blue font-bold text-sm bg-blue-50/50 px-6 py-3 rounded-xl hover:bg-blue-100 transition-colors mt-2 text-center w-full">
                  + Tambah Hari
                </button>
              </div>

              <div className="space-y-3 pt-4 border-t border-slate-100">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Atau Isi Itinerary Teks Bebas</label>
                <textarea
                  {...register('itinerary_text')}
                  rows={4}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Gunakan kolom ini jika tidak ingin menggunakan format per hari..."
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100 mt-6 md:mt-8!">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1 flex items-center gap-2"><Camera size={14}/> Biaya Tambahan Drone</label>
                  <input
                    type="number"
                    {...register('drone_price')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                    placeholder="1500000"
                  />
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Lokasi Drone</label>
                  <input
                    {...register('drone_location')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                    placeholder="Bukit Holbung, Samosir"
                  />
                </div>
              </div>
            </div>
          )}

          {/* FASILITAS */}
          {activeTab === 'fasilitas' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Sudah Termasuk (pisah koma)</label>
                  <input
                    {...register('includes')}
                    className="w-full px-5 py-4 bg-emerald-50 text-emerald-900 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 font-medium"
                    placeholder="Hotel bintang 4, Makan 3x sehari..."
                  />
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Tidak Termasuk (pisah koma)</label>
                  <input
                    {...register('excludes')}
                    className="w-full px-5 py-4 bg-rose-50 text-rose-900 border-none rounded-2xl focus:ring-2 focus:ring-rose-500 font-medium"
                    placeholder="Tiket pesawat, Tip guide..."
                  />
                </div>
              </div>
              <div className="space-y-3 pt-4 border-t border-slate-100">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Catatan Penting (Notes)</label>
                <textarea
                  {...register('notes')}
                  rows={4}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                  placeholder="Peserta minimal 2 orang, anak di atas 8 tahun bayar penuh..."
                />
              </div>
            </div>
          )}

          {/* SEO & STATUS */}
          {activeTab === 'seo' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Status Publikasi</label>
                  <select
                    {...register('status')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900 appearance-none"
                  >
                    <option value="active">Aktif (Tampil di Web)</option>
                    <option value="inactive">Nonaktif (Draft)</option>
                  </select>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Urutan Tampil (Sort Order)</label>
                  <input
                    type="number"
                    {...register('sort_order')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-bold text-slate-900"
                    placeholder="0"
                  />
                </div>
              </div>

              <div className="flex items-center space-x-3 bg-slate-50 p-6 rounded-2xl">
                <input
                  type="checkbox"
                  {...register('is_featured')}
                  id="is_featured"
                  className="w-5 h-5 text-obaja-blue focus:ring-obaja-blue border-slate-300 rounded"
                />
                <label htmlFor="is_featured" className="font-bold text-slate-900">Jadikan Paket Unggulan (Tampil di Home)</label>
              </div>

              <div className="pt-4 border-t border-slate-100 space-y-4">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Meta Title (SEO)</label>
                  <input
                    {...register('meta_title')}
                    className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                    placeholder="Paket Wisata Danau Toba Terbaik..."
                  />
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Meta Description (SEO)</label>
                  <textarea
                    {...register('meta_description')}
                    rows={2}
                    className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-obaja-blue font-medium"
                    placeholder="Nikmati liburan ke Samosir dengan fasilitas lengkap..."
                  />
                </div>
              </div>
            </div>
          )}

          {/* TERJEMAHAN */}
          {activeTab === 'bahasa' && (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 animate-in fade-in slide-in-from-bottom-4">
              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇺🇸</span>
                  <h3 className="font-bold text-slate-900">English (EN)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.en.name')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Package Name (EN)"
                  />
                  <textarea
                    {...register('translations.en.short_description')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Short Description (EN)"
                  />
                  <textarea
                    {...register('translations.en.notes')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Notes (EN)"
                  />
                </div>
              </div>

              <div className="space-y-6 bg-slate-50 p-6 rounded-3xl">
                <div className="flex items-center space-x-2 border-b border-slate-200 pb-4">
                  <span className="text-2xl">🇲🇾</span>
                  <h3 className="font-bold text-slate-900">Malaysia (MS)</h3>
                </div>
                <div className="space-y-4">
                  <input
                    {...register('translations.ms.name')}
                    className="w-full px-4 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Nama Pakej (MS)"
                  />
                  <textarea
                    {...register('translations.ms.short_description')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Penerangan Ringkas (MS)"
                  />
                  <textarea
                    {...register('translations.ms.notes')}
                    rows={2}
                    className="w-full p-4 bg-white border border-slate-100 rounded-xl font-medium"
                    placeholder="Nota (MS)"
                  />
                </div>
              </div>
            </div>
          )}
        </form>
      </div>
    </div>
  );
}
