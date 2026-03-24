import { Mail, Phone, MapPin, Instagram, Facebook, Twitter, Youtube, Send, ArrowRight } from 'lucide-react';
import { Link } from 'react-router-dom';

export default function Footer() {
  return (
    <footer className="bg-[#0f172a] text-slate-300 pt-24 pb-12 overflow-hidden relative">
      {/* Decorative background elements */}
      <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-toba-green via-toba-accent to-toba-green opacity-50" />
      <div className="absolute -top-24 -right-24 w-96 h-96 bg-toba-green/5 rounded-full blur-3xl" />
      <div className="absolute -bottom-24 -left-24 w-96 h-96 bg-toba-accent/5 rounded-full blur-3xl" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {/* Newsletter Section */}
        <div className="bg-white/5 backdrop-blur-md rounded-[2.5rem] p-8 md:p-12 mb-20 border border-white/10 flex flex-col md:flex-row items-center justify-between gap-8">
          <div className="max-w-md">
            <h3 className="text-2xl md:text-3xl font-bold text-white mb-3">Dapatkan Penawaran Eksklusif</h3>
            <p className="text-slate-400">Berlangganan newsletter kami untuk mendapatkan info promo dan destinasi terbaru langsung di email Anda.</p>
          </div>
          <div className="w-full md:w-auto flex-1 max-w-md">
            <div className="relative group">
              <input 
                type="email" 
                placeholder="Alamat email Anda" 
                className="w-full bg-white/10 border border-white/20 rounded-2xl py-4 pl-6 pr-16 text-white placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-toba-green/50 focus:border-toba-green/50 transition-all"
              />
              <button className="absolute right-2 top-2 bottom-2 bg-toba-green hover:bg-toba-green/80 text-white px-6 rounded-xl transition-all flex items-center justify-center group-hover:shadow-lg group-hover:shadow-toba-green/20">
                <Send size={18} />
              </button>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-20">
          {/* Brand */}
          <div className="space-y-8">
            <Link to="/" className="flex items-center space-x-3 group">
              <div className="w-12 h-12 bg-gradient-to-br from-toba-green to-toba-accent rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-toba-green/20 group-hover:scale-110 transition-transform duration-500">
                W
              </div>
              <span className="text-2xl font-bold text-white tracking-tight">Wonderful<span className="text-toba-green">Toba</span></span>
            </Link>
            <p className="text-slate-400 leading-relaxed text-lg">
              Membantu Anda menemukan keindahan tersembunyi di Sumatera Utara dengan layanan perjalanan premium dan terpercaya.
            </p>
            <div className="flex space-x-4">
              {[Instagram, Facebook, Twitter, Youtube].map((Icon, i) => (
                <a 
                  key={i} 
                  href="#" 
                  className="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-slate-400 hover:bg-toba-green hover:text-white hover:-translate-y-1 transition-all duration-300 border border-white/5"
                >
                  <Icon size={20} />
                </a>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-white font-bold text-xl mb-8 flex items-center">
              <span className="w-8 h-1 bg-toba-green rounded-full mr-3" />
              Destinasi Populer
            </h4>
            <ul className="space-y-4">
              {['Danau Toba', 'Pulau Samosir', 'Berastagi', 'Bukit Lawang', 'Nias'].map((item) => (
                <li key={item}>
                  <Link to="/packages" className="text-slate-400 hover:text-toba-green flex items-center group transition-colors">
                    <ArrowRight size={14} className="mr-2 opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" />
                    {item}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Support */}
          <div>
            <h4 className="text-white font-bold text-xl mb-8 flex items-center">
              <span className="w-8 h-1 bg-toba-accent rounded-full mr-3" />
              Layanan Kami
            </h4>
            <ul className="space-y-4">
              {['Paket Tour Kustom', 'Rental Mobil Mewah', 'Tiket Pesawat & Hotel', 'Corporate Travel', 'MICE & Event'].map((item) => (
                <li key={item}>
                  <a href="#" className="text-slate-400 hover:text-toba-accent flex items-center group transition-colors">
                    <ArrowRight size={14} className="mr-2 opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" />
                    {item}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-white font-bold text-xl mb-8 flex items-center">
              <span className="w-8 h-1 bg-toba-green rounded-full mr-3" />
              Hubungi Kami
            </h4>
            <div className="space-y-6">
              <div className="flex items-start space-x-4 group">
                <div className="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-toba-green shrink-0 border border-white/5 group-hover:bg-toba-green group-hover:text-white transition-all duration-300">
                  <Phone size={20} />
                </div>
                <div>
                  <p className="text-xs text-slate-500 uppercase font-bold tracking-widest mb-1">Telepon</p>
                  <p className="text-white font-medium">+62 61 1234 5678</p>
                </div>
              </div>
              <div className="flex items-start space-x-4 group">
                <div className="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-toba-green shrink-0 border border-white/5 group-hover:bg-toba-green group-hover:text-white transition-all duration-300">
                  <Mail size={20} />
                </div>
                <div>
                  <p className="text-xs text-slate-500 uppercase font-bold tracking-widest mb-1">Email</p>
                  <p className="text-white font-medium">info@wonderfultoba.com</p>
                </div>
              </div>
              <div className="flex items-start space-x-4 group">
                <div className="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-toba-green shrink-0 border border-white/5 group-hover:bg-toba-green group-hover:text-white transition-all duration-300">
                  <MapPin size={20} />
                </div>
                <div>
                  <p className="text-xs text-slate-500 uppercase font-bold tracking-widest mb-1">Alamat Utama</p>
                  <p className="text-white font-medium leading-relaxed">Jl. Sisingamangaraja No. 123, Medan, Sumatera Utara</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="pt-12 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-8">
          <div className="flex flex-col md:flex-row items-center gap-4 md:gap-8">
            <p className="text-slate-500 text-sm">
              © 2026 Wonderful Toba Indonesia. Member of ASITA & IATA.
            </p>
            <div className="flex items-center space-x-6 text-sm">
              <a href="#" className="text-slate-500 hover:text-white transition-colors">Syarat & Ketentuan</a>
              <a href="#" className="text-slate-500 hover:text-white transition-colors">Kebijakan Privasi</a>
            </div>
          </div>
          <div className="flex items-center space-x-4">
            <div className="h-8 w-12 bg-white/5 rounded border border-white/10 flex items-center justify-center text-[10px] font-bold text-slate-500">VISA</div>
            <div className="h-8 w-12 bg-white/5 rounded border border-white/10 flex items-center justify-center text-[10px] font-bold text-slate-500">MC</div>
            <div className="h-8 w-12 bg-white/5 rounded border border-white/10 flex items-center justify-center text-[10px] font-bold text-slate-500">BCA</div>
            <div className="h-8 w-12 bg-white/5 rounded border border-white/10 flex items-center justify-center text-[10px] font-bold text-slate-500">MANDIRI</div>
          </div>
        </div>
      </div>
    </footer>
  );
}
