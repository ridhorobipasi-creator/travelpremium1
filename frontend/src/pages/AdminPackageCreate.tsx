import { useState, useEffect } from 'react';
import api from '../lib/api';
import { City } from '../types';
import { useNavigate, useParams, useLocation } from 'react-router-dom';
import { 
  Package as PackageIcon, Save, DollarSign, Calendar, MapPin, 
  ArrowLeft, FileText, CheckCircle, Globe, LayoutList, Image as ImageIcon,
  Map, Camera, Upload
} from 'lucide-react';
import { useForm, useFieldArray } from 'react-hook-form';
import { toast } from 'sonner';

export default function AdminPackageCreate() {
  const { id } = useParams();
  const location = useLocation();
  const [cities, setCities] = useState<City[]>([]);
  const [activeTab, setActiveTab] = useState('info');
  const [previewImage, setPreviewImage] = useState<string | null>(null);
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const navigate = useNavigate();

  // Determine scope from URL (Priority: Scope-aware Dashboard)
  const category = location.pathname.includes('/outbound') ? 'outbound' : 'tour';
  const basePath = category === 'outbound' ? '/admin/outbound' : '/admin/tour';

  const { register, handleSubmit, control, reset, setValue } = useForm<any>({
    defaultValues: {
      status: 'active',
      is_featured: false,
      sort_order: 0,
      scope: category, // Pre-assign scope
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
          scope: pkg.scope || category,
          includes: pkg.includes?.join(', ') || '',
          excludes: pkg.excludes?.join(', ') || '',
        });
        if (pkg.images?.[0]) {
            const imgUrl = pkg.images[0].startsWith('http') ? pkg.images[0] : `${import.meta.env.VITE_API_BASE_URL.replace('/api', '')}/storage/${pkg.images[0]}`;
            setPreviewImage(imgUrl);
        }
      }).catch(err => {
        console.error('Error fetching package:', err);
        toast.error('Gagal mengambil data paket');
      });
    }
  }, [id, reset, category]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setSelectedFile(file);
      setPreviewImage(URL.createObjectURL(file));
      toast.info('Foto dipilih, akan diproses menjadi WebP saat disimpan.');
    }
  };

  const onSubmit = async (data: any) => {
    const loadingToast = toast.loading(id ? 'Memperbarui paket...' : 'Menyimpan paket baru...');
    try {
      const includesArray = data.includes ? data.includes.split(',').map((s: string) => s.trim()) : [];
      const excludesArray = data.excludes ? data.excludes.split(',').map((s: string) => s.trim()) : [];
      
      // Use FormData for File Upload (Priority: Image Optimizer Support)
      const formData = new FormData();
      formData.append('name', data.name);
      formData.append('scope', data.scope);
      formData.append('city_id', data.city_id);
      formData.append('duration', data.duration);
      formData.append('price', String(data.price));
      formData.append('status', data.status);
      formData.append('description', data.description || '');
      formData.append('short_description', data.short_description || '');
      formData.append('location_tag', data.location_tag || '');
      formData.append('price_display', data.price_display || '');
      formData.append('is_featured', data.is_featured ? '1' : '0');
      formData.append('sort_order', String(data.sort_order));

      // Nested data handling
      formData.append('pricing_details', JSON.stringify(data.pricing_details.filter((p: any) => p.pax && p.price_per_person)));
      formData.append('itinerary', JSON.stringify(data.itinerary.filter((i: any) => i.day && i.title)));
      formData.append('includes', JSON.stringify(includesArray));
      formData.append('excludes', JSON.stringify(excludesArray));
      formData.append('translations', JSON.stringify(data.translations));

      if (selectedFile) {
        formData.append('image_file', selectedFile);
      }

      // Method spoofing for PUT if needed by Laravel
      if (id) {
        formData.append('_method', 'PUT');
        await api.post(`/packages/${id}`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        toast.success('Paket berhasil diperbarui dengan foto WebP!');
      } else {
        await api.post('/packages', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });
        toast.success('Paket baru berhasil ditambahkan!');
      }
      toast.dismiss(loadingToast);
      navigate(`${basePath}/packages`);
    } catch (error: any) {
      toast.dismiss(loadingToast);
      console.error('Error saving package:', error);
      toast.error(error.response?.data?.message || 'Gagal menyimpan paket wisata');
    }
  };

  const tabs = [
    { id: 'info', label: 'Identitas Paket', icon: <FileText size={18} /> },
    { id: 'harga', label: 'Harga', icon: <DollarSign size={18} /> },
    { id: 'itinerary', label: 'Itinerary', icon: <Map size={18} /> },
    { id: 'fasilitas', label: 'Fasilitas & Catatan', icon: <CheckCircle size={18} /> },
    { id: 'seo', label: 'Status & SEO', icon: <Globe size={18} /> },
  ];

  return (
    <div className="max-w-6xl mx-auto pb-12 animate-in fade-in duration-500">
      <button onClick={() => navigate(`${basePath}/packages`)} className="flex items-center gap-2 text-slate-500 hover:text-toba-green font-bold mb-6 transition-colors">
        <ArrowLeft size={18} /> Kembali ke Manajemen (Dashboard {category.toUpperCase()})
      </button>

      <div className="bg-white rounded-[3rem] p-8 md:p-12 shadow-sm border border-slate-100 relative overflow-hidden">
        {/* Scope context indicator */}
        <div className={cn("absolute top-0 right-0 px-8 py-2 font-black text-xs uppercase tracking-widest text-white rounded-bl-3xl", category === 'outbound' ? 'bg-toba-green' : 'bg-obaja-blue')}>
          SCOPE: {category}
        </div>

        <div className="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
          <div>
            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight mb-2">
              {id ? 'Edit Paket' : 'Tambah Paket'} {category === 'outbound' ? 'Outbound' : 'Tour'}
            </h2>
            <p className="text-slate-500 font-medium">Buat paket {category} yang menarik untuk pelanggan.</p>
          </div>
          <button
            onClick={handleSubmit(onSubmit)}
            className={cn(
              "text-white px-10 py-4 rounded-2xl font-bold transition-all shadow-xl flex items-center space-x-2",
              category === 'outbound' ? "bg-toba-green hover:bg-toba-green/90 shadow-toba-green/20" : "bg-obaja-blue hover:bg-obaja-blue/90 shadow-blue-100"
            )}
          >
            <Save size={20} />
            <span>{id ? 'Simpan Perubahan' : 'Simpan Paket'}</span>
          </button>
        </div>

        {/* Tab Navigation */}
        <div className="flex overflow-x-auto hide-scrollbar border-b border-slate-100 mb-8 space-x-2">
          {tabs.map(tab => (
            <button
              key={tab.id}
              type="button"
              onClick={() => setActiveTab(tab.id)}
              className={cn(
                "flex items-center space-x-2 px-6 py-4 border-b-2 font-bold transition-all whitespace-nowrap",
                activeTab === tab.id ? (category === 'outbound' ? 'border-toba-green text-toba-green' : 'border-obaja-blue text-obaja-blue') : 'border-transparent text-slate-400 hover:text-slate-600'
              )}
            >
              {tab.icon}
              <span>{tab.label}</span>
            </button>
          ))}
        </div>

        <form className="space-y-8" onSubmit={(e) => e.preventDefault()}>
          {/* IDENTITAS PAKET */}
          {activeTab === 'info' && (
            <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4">
              <div className="flex flex-col md:flex-row gap-10">
                {/* Photo Upload Section */}
                <div className="w-full md:w-80 shrink-0 space-y-4">
                   <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Foto Paket (Utama)</label>
                   <div 
                     className="group relative aspect-video md:aspect-square rounded-[2rem] bg-slate-100 border-2 border-dashed border-slate-200 overflow-hidden flex flex-col items-center justify-center text-slate-400 hover:bg-slate-50 transition-all cursor-pointer"
                     onClick={() => document.getElementById('image-upload')?.click()}
                   >
                     {previewImage ? (
                       <img src={previewImage} alt="Preview" className="w-full h-full object-cover" />
                     ) : (
                       <>
                         <Upload size={32} className="mb-2 group-hover:-translate-y-1 transition-transform" />
                         <span className="text-xs font-bold">Pilih Foto</span>
                       </>
                     )}
                     <div className="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span className="text-white text-xs font-bold flex items-center gap-2"><Upload size={14}/> Ganti Foto</span>
                     </div>
                   </div>
                   <input 
                     type="file" 
                     id="image-upload" 
                     hidden 
                     accept="image/*" 
                     onChange={handleFileChange} 
                   />
                   <p className="text-[10px] text-slate-400 font-medium text-center uppercase tracking-widest leading-loose">
                      Format JPG/PNG/WEBP. Ukuran Maksimum 10MB.<br/>Akan dikompres otomatis oleh sistem.
                   </p>
                </div>

                {/* Text Info Section */}
                <div className="flex-grow space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="space-y-3">
                      <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Paket</label>
                      <div className="relative">
                        <PackageIcon className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                        <input
                          {...register('name', { required: true })}
                          className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-bold text-slate-900"
                          placeholder={category === 'outbound' ? "Contoh: Team Building Merapi" : "Contoh: Explore Danau Toba 3D2N"}
                        />
                      </div>
                    </div>

                    <div className="space-y-3">
                      <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Modul Bisnis (Scope)</label>
                      <select
                        {...register('scope', { required: true })}
                        className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-bold text-slate-900 appearance-none"
                      >
                        <option value="tour">Paket Tour & Travel</option>
                        <option value="outbound">Paket Corporate Outbound</option>
                      </select>
                    </div>

                    <div className="space-y-3">
                      <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Kota / Lokasi Venue</label>
                      <div className="relative">
                        <MapPin className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                        <select
                          {...register('city_id', { required: true })}
                          className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-bold text-slate-900 appearance-none"
                        >
                          <option value="">Pilih Lokasi</option>
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
                          className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-bold text-slate-900"
                          placeholder="3 Hari 2 Malam"
                        />
                      </div>
                    </div>
                  </div>

                  <div className="space-y-3 pt-4 border-t border-slate-100">
                    <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Deskripsi Singkat</label>
                    <textarea
                      {...register('short_description')}
                      rows={2}
                      className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium"
                      placeholder="Tampil ringkas di halaman katalog..."
                    />
                  </div>
                </div>
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Deskripsi Lengkap</label>
                <textarea
                  {...register('description')}
                  rows={8}
                  className="w-full p-5 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium"
                  placeholder="Detail lengkap mengenai aktivitas, teknis, dan keseruan paket..."
                />
              </div>
            </div>
          )}

          {/* HARGA - Using original logic but theme aware */}
          {activeTab === 'harga' && (
            <div className="space-y-6 animate-in fade-in slide-in-from-bottom-4">
              <div className="bg-slate-50 p-6 rounded-2xl space-y-4 mb-6">
                <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-2">Tabel Harga Tier (Opsional)</h4>
                {pricingFields.map((field, index) => (
                  <div key={field.id} className="flex gap-4">
                    <div className="flex-1">
                      <input
                        {...register(`pricing_details.${index}.pax`)}
                        placeholder="Jumlah Orang (mis. 2)"
                        className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                      />
                    </div>
                    <div className="flex-1">
                      <input
                        {...register(`pricing_details.${index}.price_per_person`)}
                        placeholder="Harga / Orang (Rp)"
                        className="w-full px-5 py-3 bg-white border border-slate-100 rounded-xl font-medium"
                      />
                    </div>
                    <button type="button" onClick={() => removePricing(index)} className="px-4 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors font-bold">Hapus</button>
                  </div>
                ))}
                <button type="button" onClick={() => appendPricing({ pax: '', price_per_person: '' })} className="text-toba-green font-bold text-sm bg-toba-green/5 px-6 py-3 rounded-xl hover:bg-toba-green/10 transition-colors">
                  + Tambah Baris Harga
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1 text-toba-green">Harga Dasar Utama</label>
                  <div className="relative">
                    <DollarSign className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={20} />
                    <input
                      type="number"
                      {...register('price', { required: true })}
                      className="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-bold text-slate-900"
                    />
                  </div>
                </div>
                <div className="space-y-3">
                    <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Teks Harga Custom</label>
                    <input
                      {...register('price_display')}
                      className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-medium"
                      placeholder="Contoh: Mulai dari Rp 450.000 / pax"
                    />
                  </div>
              </div>
            </div>
          )}

          {/* Tab lain (Itinerary, Fasilitas, SEO) tetap namun menyesuaikan warna tema t-green/o-blue */}
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
                      <input {...register(`itinerary.${index}.day`)} placeholder="Hari ke-" className="col-span-1 px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-toba-green/30 font-medium" />
                      <input {...register(`itinerary.${index}.title`)} placeholder="Judul / Lokasi" className="col-span-3 px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-toba-green/30 font-medium" />
                    </div>
                    <textarea {...register(`itinerary.${index}.description`)} placeholder="Aktivitas..." rows={2} className="w-full px-4 py-3 bg-slate-50 border-none rounded-lg focus:ring-2 focus:ring-toba-green/30 font-medium" />
                  </div>
                ))}
                <button type="button" onClick={() => appendItinerary({ day: '', title: '', description: '' })} className="text-toba-green font-bold text-sm bg-toba-green/5 px-6 py-3 rounded-xl hover:bg-toba-green/10 transition-colors w-full">+ Tambah Hari</button>
              </div>
            </div>
          )}

          {activeTab === 'fasilitas' && (
            <div className="space-y-6 animate-in fade-in">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div className="space-y-3">
                    <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Sudah Termasuk (pisah koma)</label>
                    <textarea {...register('includes')} rows={4} className="w-full p-5 bg-emerald-50 text-emerald-900 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500 font-medium" placeholder="Fasilitas A, Fasilitas B..."></textarea>
                  </div>
                  <div className="space-y-3">
                    <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Tidak Termasuk (pisah koma)</label>
                    <textarea {...register('excludes')} rows={4} className="w-full p-5 bg-rose-50 text-rose-900 border-none rounded-2xl focus:ring-2 focus:ring-rose-500 font-medium" placeholder="Tiket, Tips..."></textarea>
                  </div>
                </div>
            </div>
          )}

          {activeTab === 'seo' && (
            <div className="space-y-6 animate-in fade-in">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Status Publikasi</label>
                  <select {...register('status')} className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-bold text-slate-900 appearance-none">
                    <option value="active">Aktif (Tampil di Web)</option>
                    <option value="inactive">Nonaktif (Draft)</option>
                  </select>
                </div>
                <div className="space-y-3">
                  <label className="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] ml-1">Jadikan Unggulan</label>
                   <select {...register('is_featured')} className="w-full px-5 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-toba-green/30 font-bold text-slate-900 appearance-none">
                    <option value={0}>Tidak</option>
                    <option value={1}>Ya (Muncul di Home)</option>
                  </select>
                </div>
              </div>
            </div>
          )}
        </form>
      </div>
    </div>
  );
}
