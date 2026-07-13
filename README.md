# Sistem Absensi KKN (Absen KKN)

Aplikasi pencatatan absensi mahasiswa Kuliah Kerja Nyata (KKN) Universitas Buana Perjuangan Karawang berbasis web yang responsif, dilengkapi dengan sistem pembatasan jarak (**Geofencing**), manajemen profil mahasiswa, dan panel kontrol admin.

Aplikasi ini telah dioptimalkan menjadi **SPA (Single Page Application)** menggunakan **HTMX** untuk transisi pemuatan halaman yang sangat cepat dan mulus.

Aplikasi berbasis web dengan framework Laravel ini digunakan untuk absensi mahasiswa KKN di Desa Mekarmaya yg dibuat untuk mengetahui serta merekap absensi mahasiswa selama masa KKN menggunakan fitur live location tracking.

---

## 🌟 Fitur Utama

### 1. Mahasiswa (Student)
- **Absen Berbasis Geofencing**: Pencatatan kehadiran pagi (Check-In) dan malam (Check-Out) berdasarkan jarak radius (meter) dari titik koordinat posko KKN yang sudah ditentukan oleh Admin.
- **Deteksi Jarak Realtime**: Menampilkan jarak GPS real-time mahasiswa ke koordinat posko sebelum melakukan absensi lengkap dengan indikator zona radius aman (hijau/merah).
- **Manajemen Profil & Foto**: Mahasiswa dapat memperbarui nama, NIM, program studi, kata sandi, dan mengunggah foto profil sendiri.
- **Riwayat Absensi**: Menampilkan daftar log riwayat kehadiran yang telah dicatat secara urut.
- **Pemuatan SPA (HTMX)**: Transisi perpindahan halaman dan pengiriman form instan tanpa reload penuh layar, dilengkapi bar loading bergradasi premium.

### 2. Administrator
- **Dashboard Ringkasan**: Statistik jumlah mahasiswa terdaftar dan total kehadiran hari ini.
- **Manajemen Mahasiswa (CRUD)**: Menambah, mengubah, mencari, dan menghapus data mahasiswa. Daftar diurutkan berdasarkan abjad nama secara otomatis.
- **Log Kehadiran**: Memantau seluruh waktu Check-In dan Check-Out mahasiswa serta dapat menghapus data absensi jika diperlukan.
- **Pengaturan Posko**: Menentukan koordinat pusat posko (Latitude & Longitude), batas radius toleransi absensi (meter), serta konfigurasi batasan jam absen pagi dan absen malam.

---

## 🛠️ Spesifikasi Teknologi

- **Backend Framework**: Laravel 11.x
- **Frontend Logic & Animations**: Vanilla JavaScript & HTMX 1.9
- **Design System & Styling**: Vanilla CSS (dengan estetika *Glassmorphism* premium dan tata letak responsif mobile/desktop)
- **Database**: MySQL / MariaDB (mendukung SQLite untuk pengujian cepat)
