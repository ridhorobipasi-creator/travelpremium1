import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Helmet } from 'react-helmet-async';
import { ArrowRight, Compass, Users } from 'lucide-react';
import { cn } from '../utils/cn';

export default function Landing() {
  const navigate = useNavigate();
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    setIsLoaded(true);
  }, []);

  return (
    <div className="font-sans bg-slate-950 text-white overflow-hidden min-h-screen">
      <Helmet>
        <title>Wonderful Toba | Premium Tour & Corporate Outbound</title>
        <meta name="description" content="Portal utama Wonderful Toba. Pilih layanan premium Tour Travel Sumatera Utara atau Corporate Outbound & Team Building." />
      </Helmet>

      {/* Main Split Interface */}
      <main className="h-[100dvh] flex flex-col md:flex-row relative">
        
        {/* Central Logo & Branding - floats over the split */}
        {/* Responsive Fix: Smaller on mobile, no overlapping with buttons */}
        <div className={cn(
            "absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[60] text-center transition-all duration-1000",
            isLoaded ? 'opacity-100 scale-100' : 'opacity-0 scale-90'
        )}>
           <div className="bg-slate-900/60 backdrop-blur-2xl p-6 md:p-8 rounded-[2.5rem] border border-white/10 shadow-2xl shadow-toba-green/20 scale-75 md:scale-100">
             <div className="w-16 h-16 md:w-20 md:h-20 bg-toba-green rounded-2xl flex items-center justify-center font-black text-2xl md:text-3xl mx-auto shadow-inner mb-3 md:mb-4">W</div>
             <h1 className="text-2xl md:text-3xl font-black tracking-tight whitespace-nowrap mb-1">Wonderful <span className="text-toba-green">Toba</span></h1>
             <p className="text-[9px] md:text-[10px] font-bold tracking-[0.3em] uppercase text-slate-300">Sumatera Utara</p>
           </div>
        </div>

        {/* Left Side - Corporate Outbound */}
        <button 
          onClick={() => navigate('/outbound')} 
          className="relative w-full md:w-1/2 h-1/2 md:h-full group cursor-pointer block overflow-hidden border-b border-white/5 md:border-b-0 md:border-r border-white/5"
        >
          {/* Background Image */}
          <div 
            className="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] ease-out group-hover:scale-110" 
            style={{ backgroundImage: "url('/assets/images/2023/10/006.jpg')" }}
          />
          {/* Gradient Overlay - Darker on mobile bottom to avoid text clashing with Central Logo */}
          <div className="absolute inset-0 bg-slate-900/40 group-hover:bg-slate-900/20 transition-colors duration-500" />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent md:bg-gradient-to-t md:from-slate-950/90 md:via-transparent md:to-transparent" />
          
          {/* Content */}
          <div className="absolute inset-0 flex flex-col justify-end md:justify-end p-8 md:p-16 lg:p-24 text-left transition-transform duration-500 group-hover:-translate-y-2 md:group-hover:-translate-y-4">
            <div className="flex items-center gap-3 mb-2 md:mb-4 text-toba-green md:opacity-0 md:translate-y-4 md:group-hover:opacity-100 md:group-hover:translate-y-0 transition-all duration-500 delay-100">
               <Users size={16} className="md:size-5" />
               <span className="font-bold text-[10px] md:text-xs uppercase tracking-widest text-white/80">Corporate Outbound</span>
            </div>
            <h2 className="text-3xl md:text-5xl lg:text-7xl font-black mb-4 tracking-tighter leading-tight md:leading-[1.1]">
              Professional<br/><span className="text-toba-green">Outbound.</span>
            </h2>
            <p className="text-slate-300 font-medium max-w-sm mb-6 md:mb-8 text-xs md:text-base leading-relaxed hidden sm:block">
              Solusi team building & gathering profesional untuk instansi Anda. Tersedia di puluhan lokasi premium.
            </p>
            <div className="flex items-center gap-3 md:gap-4">
              <div className="bg-toba-green text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-[11px] md:text-sm tracking-widest uppercase transition-all shadow-xl group-hover:shadow-toba-green/30 md:group-hover:px-10">
                 JELAJAHI OUTBOUND
              </div>
              <div className="w-10 h-10 md:w-12 md:h-12 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all">
                <ArrowRight size={18} className="-rotate-45 group-hover:rotate-0 transition-transform duration-300" />
              </div>
            </div>
          </div>
        </button>

        {/* Right Side - Tour & Travel */}
        <button 
          onClick={() => navigate('/tour')} 
          className="relative w-full md:w-1/2 h-1/2 md:h-full group cursor-pointer block overflow-hidden"
        >
          {/* Background Image */}
          <div 
            className="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] ease-out group-hover:scale-110" 
            style={{ backgroundImage: "url('https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=2000')" }}
          />
          {/* Gradient Overlay */}
          <div className="absolute inset-0 bg-slate-900/40 group-hover:bg-slate-900/20 transition-colors duration-500" />
          <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent md:bg-gradient-to-t md:from-slate-950/90 md:via-transparent md:to-transparent" />
          
          {/* Content */}
          <div className="absolute inset-0 flex flex-col justify-end p-8 md:p-16 lg:p-24 text-left md:text-right transition-transform duration-500 group-hover:-translate-y-2 md:group-hover:-translate-y-4">
            <div className="flex items-center md:justify-end gap-3 mb-2 md:mb-4 text-emerald-400 md:opacity-0 md:translate-y-4 md:group-hover:opacity-100 md:group-hover:translate-y-0 transition-all duration-500 delay-100">
               <Compass size={16} className="md:size-5" />
               <span className="font-bold text-[10px] md:text-xs uppercase tracking-widest text-white/80">Tour & Travel</span>
            </div>
            <h2 className="text-3xl md:text-5xl lg:text-7xl font-black mb-4 tracking-tighter leading-tight md:leading-[1.1]">
              Exclusive<br/><span className="text-emerald-400">Travel.</span>
            </h2>
            <p className="text-slate-300 font-medium max-w-sm mb-6 md:mb-8 text-xs md:text-base leading-relaxed md:ml-auto hidden sm:block">
              Eksplorasi keindahan Danau Toba dengan paket liburan eksklusif dan layanan transportasi premium.
            </p>
            <div className="flex items-center md:justify-end gap-3 md:gap-4 flex-row-reverse md:flex-row">
              <div className="w-10 h-10 md:w-12 md:h-12 rounded-full border border-white/20 flex items-center justify-center group-hover:bg-white group-hover:text-slate-900 transition-all">
                <ArrowRight size={18} className="-rotate-45 group-hover:rotate-0 transition-transform duration-300" />
              </div>
              <div className="bg-emerald-500 text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold text-[11px] md:text-sm tracking-widest uppercase transition-all shadow-xl group-hover:shadow-emerald-500/30 md:group-hover:px-10">
                 JELAJAHI WISATA
              </div>
            </div>
          </div>
        </button>
        
      </main>
    </div>
  );
}
