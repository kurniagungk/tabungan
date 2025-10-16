# Aplikasi Tabungan Digital

## Deskripsi Singkat
Aplikasi Tabungan Digital adalah sistem manajemen tabungan berbasis web yang dikembangkan menggunakan framework Laravel dan Livewire. Aplikasi ini dirancang untuk mengelola transaksi tabungan nasabah dengan fitur lengkap mulai dari pendaftaran nasabah, transaksi setor dan tarik tunai, hingga pelaporan yang komprehensif.

## Tujuan Utama
- Mengotomatisasi proses manajemen tabungan nasabah
- Menyediakan sistem transaksi yang aman dengan validasi password
- Memfasilitasi komunikasi dengan nasabah melalui integrasi WhatsApp
- Menghasilkan laporan keuangan yang akurat dan real-time

## Fitur-Fitur Utama
1. **Manajemen Nasabah**: Pendaftaran, edit, dan pengelolaan data nasabah dengan nomor rekening otomatis
2. **Transaksi Tabungan**: Proses setor dan tarik tunai dengan validasi password keamanan
3. **Integrasi WhatsApp**: Notifikasi otomatis untuk transaksi dan pengecekan saldo/mutasi via chat
4. **Sistem Laporan**: Berbagai jenis laporan (transaksi harian, mutasi, biaya admin, dll) dengan eksport Excel
5. **Dashboard Komprehensif**: Statistik real-time jumlah nasabah dan total saldo
6. **Manajemen Multi-Lembaga**: Dukungan untuk beberapa lembaga dalam satu sistem
7. **Sistem Role-Based Access**: Kontrol akses berdasarkan peran (admin, petugas, mitra)

## Teknologi yang Digunakan
- **Backend**: Laravel 12.0 dengan PHP 8.2
- **Frontend**: Livewire 3.6 untuk interaktivitas real-time
- **UI Framework**: Mary UI (berbasis Tailwind CSS)
- **Database**: MySQL dengan Eloquent ORM
- **Package Tambahan**: 
  - Spatie Laravel Permission untuk manajemen role
  - Maatwebsite Excel untuk export laporan
  - WhatsApp API integration untuk notifikasi

## Signifikansi Proyek
Aplikasi ini memberikan solusi teknologi yang efisien untuk lembaga keuangan skala kecil hingga menengah dalam mengelola sistem tabungan. Dengan integrasi WhatsApp dan fitur pelaporan yang lengkap, aplikasi ini meningkatkan transparansi, efisiensi operasional, dan pengalaman nasabah secara keseluruhan.