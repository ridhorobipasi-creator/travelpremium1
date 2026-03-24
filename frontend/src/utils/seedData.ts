import { collection, addDoc, getDocs } from 'firebase/firestore';
import { db } from '../firebase';

export const seedInitialData = async () => {
  try {
    const provincesSnap = await getDocs(collection(db, 'provinces'));
    if (!provincesSnap.empty) {
      console.log('Data already seeded');
      return;
    }

    // 1. Provinces
    const provinces = [
      { name: 'Sumatera Utara', description: 'Danau Toba & Budaya Batak', image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd' },
    ];
    const provinceRefs: Record<string, string> = {};
    for (const p of provinces) {
      const ref = await addDoc(collection(db, 'provinces'), p);
      provinceRefs[p.name] = ref.id;
    }

    // 2. Cities
    const cities = [
      { provinceId: provinceRefs['Sumatera Utara'], name: 'Danau Toba', description: 'Danau vulkanik terbesar di dunia' },
      { provinceId: provinceRefs['Sumatera Utara'], name: 'Samosir', description: 'Pulau di tengah Danau Toba' },
      { provinceId: provinceRefs['Sumatera Utara'], name: 'Berastagi', description: 'Kota wisata di kaki Gunung Sinabung' },
      { provinceId: provinceRefs['Sumatera Utara'], name: 'Tangkahan', description: 'Surga tersembunyi di Langkat' },
      { provinceId: provinceRefs['Sumatera Utara'], name: 'Bukit Lawang', description: 'Pintu masuk Taman Nasional Gunung Leuser' },
      { provinceId: provinceRefs['Sumatera Utara'], name: 'Medan', description: 'Ibukota Sumatera Utara' },
    ];
    const cityRefs: Record<string, string> = {};
    for (const c of cities) {
      const ref = await addDoc(collection(db, 'cities'), c);
      cityRefs[c.name] = ref.id;
    }

    // 3. Packages
    const packages = [
      {
        name: 'Pesona Danau Toba & Budaya Batak',
        description: 'Jelajahi keindahan Danau Toba yang memukau dan rasakan kekayaan budaya Batak yang autentik. Nikmati pemandangan danau vulkanik terbesar di dunia dari berbagai sudut pandang.',
        price: 3500000,
        duration: '4 Hari 3 Malam',
        cityId: cityRefs['Danau Toba'],
        includes: ['Hotel Bintang 3 di Parapat', 'Makan 3x Sehari (Masakan Batak)', 'Transport AC Medan–Toba PP', 'Guide Lokal Berpengalaman', 'Tiket Masuk Destinasi', 'Naik Kapal ke Samosir'],
        excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Tip Guide', 'Oleh-oleh'],
        images: ['https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&q=80&w=800'],
        status: 'active',
      },
      {
        name: 'Eksplorasi Pulau Samosir',
        description: 'Kunjungi Pulau Samosir yang eksotis di tengah Danau Toba. Temukan rumah adat Batak, patung Sigale-gale, dan keindahan alam yang tiada duanya.',
        price: 2800000,
        duration: '3 Hari 2 Malam',
        cityId: cityRefs['Samosir'],
        includes: ['Penginapan Tepi Danau Toba', 'Makan 2x Sehari', 'Kapal Feri Ajibata–Tomok', 'Guide Lokal Samosir', 'Kunjungan Desa Adat Batak'],
        excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Souvenir'],
        images: ['https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&q=80&w=800'],
        status: 'active',
      },
      {
        name: 'Petualangan Berastagi & Gunung Sinabung',
        description: 'Rasakan kesegaran udara pegunungan Berastagi sambil menikmati pemandangan Gunung Sinabung yang megah. Kunjungi pasar buah dan sayur yang terkenal.',
        price: 2200000,
        duration: '2 Hari 1 Malam',
        cityId: cityRefs['Berastagi'],
        includes: ['Hotel di Berastagi', 'Makan 2x Sehari', 'Transport AC', 'Guide Lokal', 'Kunjungan Pasar Buah Berastagi'],
        excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Oleh-oleh'],
        images: ['https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=800'],
        status: 'active',
      },
      {
        name: 'Ekowisata Tangkahan & Gajah Sumatera',
        description: 'Pengalaman tak terlupakan berinteraksi dengan gajah Sumatera jinak di Tangkahan. Mandi bersama gajah di sungai jernih dan jelajahi hutan tropis yang masih alami.',
        price: 3200000,
        duration: '3 Hari 2 Malam',
        cityId: cityRefs['Tangkahan'],
        includes: ['Penginapan Eco Lodge Tangkahan', 'Makan 3x Sehari', 'Aktivitas Mandi Gajah', 'Guide Ranger TNGL', 'Trekking Hutan Tropis'],
        excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Tip Ranger'],
        images: ['https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&q=80&w=800'],
        status: 'active',
      },
      {
        name: 'Trekking Bukit Lawang & Orangutan',
        description: 'Petualangan trekking di hutan hujan tropis Bukit Lawang untuk melihat orangutan Sumatera liar dalam habitat aslinya. Pengalaman yang akan selalu dikenang.',
        price: 2900000,
        duration: '3 Hari 2 Malam',
        cityId: cityRefs['Bukit Lawang'],
        includes: ['Penginapan di Bukit Lawang', 'Makan 3x Sehari', 'Guide Trekking Bersertifikat', 'Perlengkapan Trekking', 'Kunjungan Pusat Rehabilitasi Orangutan'],
        excludes: ['Tiket Pesawat ke Medan', 'Pengeluaran Pribadi', 'Tip Guide'],
        images: ['https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&q=80&w=800'],
        status: 'active',
      },
    ];
    for (const pkg of packages) {
      await addDoc(collection(db, 'packages'), pkg);
    }

    // 4. Cars
    const cars = [
      { name: 'Toyota Avanza', type: 'MPV', pricePerDay: 450000, status: 'available', image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800', seats: 7, fuel: 'Bensin', transmission: 'Manual' },
      { name: 'Mitsubishi Pajero Sport', type: 'SUV', pricePerDay: 1200000, status: 'available', image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800', seats: 7, fuel: 'Solar', transmission: 'Otomatis' },
      { name: 'Toyota Innova Reborn', type: 'MPV', pricePerDay: 750000, status: 'available', image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800', seats: 8, fuel: 'Solar', transmission: 'Manual' },
      { name: 'Honda CR-V', type: 'SUV', pricePerDay: 900000, status: 'available', image: 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?auto=format&fit=crop&q=80&w=800', seats: 5, fuel: 'Bensin', transmission: 'Otomatis' },
      { name: 'Toyota Alphard', type: 'Luxury', pricePerDay: 2500000, status: 'available', image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&q=80&w=800', seats: 7, fuel: 'Bensin', transmission: 'Otomatis' },
    ];
    for (const car of cars) {
      await addDoc(collection(db, 'cars'), car);
    }

    console.log('Seeding complete!');
  } catch (error) {
    console.error('Seeding failed:', error);
  }
};
