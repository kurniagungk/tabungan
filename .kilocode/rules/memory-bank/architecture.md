# Arsitektur Sistem Aplikasi Tabungan Digital

## Overview Arsitektur
Aplikasi Tabungan Digital menggunakan arsitektur MVC (Model-View-Controller) dengan pendekatan component-based melalui Livewire. Sistem dirancang untuk mendukung multi-lembaga dalam satu instalasi dengan segregasi data berdasarkan saldo_id.

## Struktur Direktori Utama
```
app/
├── Http/Controllers/          # Controller Laravel tradisional
├── Livewire/                  # Komponen Livewire
│   ├── Dasbord/              # Dashboard components
│   ├── Nasabah/              # Manajemen nasabah
│   ├── Transaksi/            # Transaksi setor/tarik
│   ├── Laporan/              # Sistem laporan
│   ├── Whatsapp/             # Integrasi WhatsApp
│   ├── User/                 # Manajemen user
│   ├── Setting/              # Konfigurasi sistem
│   └── Mitra/                # Manajemen mitra
├── Models/                    # Model Eloquent
├── Jobs/                      # Background jobs
└── Exports/                   # Export Excel classes
```

## Arsitektur Database

### Tabel Utama
1. **nasabah** - Data nasabah dengan UUID primary key
   - Auto-generate nomor rekening (NSBXXXXX)
   - Relasi ke saldo untuk multi-lembaga
   - Password transaksi untuk keamanan

2. **nasabah_transaksi** - Histori transaksi per nasabah
   - Credit/Debit system
   - Relasi ke user yang melakukan transaksi
   - Support untuk berbagai jenis transaksi

3. **transaksi** - Log transaksi umum
   - Tracking semua transaksi sistem
   - Referensi ke transaksi detail

4. **saldo** - Manajemen multi-lembaga
   - Segregasi data per lembaga
   - Relasi ke nasabah, user, dan setting

5. **whatsapp** - Notifikasi WhatsApp
   - Queue system untuk pengiriman
   - Status tracking (pending, berhasil, gagal)
   - Relasi ke transaksi dan nasabah

6. **setting** - Konfigurasi sistem
   - Per-lembaga configuration
   - WhatsApp API settings
   - Pengaturan tabungan

## Alur Transaksi

### Proses Setor Tunai
1. Input nomor rekening → Validasi nasabah
2. Modal konfirmasi password → Validasi keamanan
3. Input nominal & keterangan → Form transaksi
4. Database locking → Update saldo nasabah
5. Create transaksi records → Log transaksi
6. Queue WhatsApp notification → Background job

### Proses Tarik Tunai
Alur yang sama dengan setor tunai, namun dengan:
- Validasi saldo mencukupi
- Debit instead of credit
- Notifikasi WhatsApp berbeda

## Integrasi WhatsApp

### Session Management
- Session storage di database (tabel setting)
- Status checking dengan caching
- Auto-reconnect untuk session yang terputus

### Message Queue
- Background jobs untuk pengiriman
- Retry mechanism untuk failed messages
- Status tracking dan monitoring

### Webhook Integration
- Incoming message handling
- Command processing (saldo, mutasi)
- Automatic response system

## Security Implementation

### Authentication & Authorization
- Spatie Laravel Permission untuk role management
- Role-based middleware
- Multi-lembaga data segregation

### Transaction Security
- Password validation untuk setiap transaksi
- Database locking untuk konkurensi
- UUID untuk primary keys (predictability prevention)

### Data Protection
- Input validation dengan Laravel rules
- SQL injection prevention dengan Eloquent
- XSS protection dengan Blade templating

## Component Architecture (Livewire)

### Dashboard Component
```php
app/Livewire/Dasbord/Index.php
- Real-time statistics
- WhatsApp session monitoring
- Multi-lembaga data filtering
```

### Transaksi Components
```php
app/Livewire/Transaksi/Setor.php
app/Livewire/Transaksi/Tarik.php
- Password validation
- Real-time saldo calculation
- Transaction history display
```

### Laporan Components
```php
app/Livewire/Laporan/Transaksi.php
app/Livewire/Laporan/Mutasi.php
- Date range filtering
- Export to Excel
- Multi-lembaga support
```

## Performance Optimization

### Database Optimization
- Indexing pada foreign keys
- Query optimization dengan eager loading
- Database locking untuk transaksi

### Caching Strategy
- WhatsApp session status caching
- Dashboard statistics caching
- Query result caching

### Queue System
- WhatsApp notifications background processing
- Excel export jobs
- Retry mechanism untuk failed jobs

## API Integration

### WhatsApp API
- RESTful API communication
- Session management
- Message sending & receiving

### PPDB API (Optional)
- Student data import
- Synchronization dengan sistem PPDB

## Frontend Architecture

### UI Framework
- Mary UI (berbasis Tailwind CSS)
- Component-based design
- Responsive layout

### JavaScript Integration
- Alpine.js untuk interaktivitas
- Chart.js untuk visualisasi data
- Real-time updates dengan Livewire

## Deployment Architecture

### Environment Configuration
- Multi-environment support (.env)
- Database configuration
- WhatsApp API configuration

### File Structure
- Public assets management
- Storage system untuk exports
- Logging system

## Monitoring & Logging

### Error Handling
- Try-catch blocks pada critical operations
- Logging dengan Laravel Log system
- User-friendly error messages

### Performance Monitoring
- Query performance tracking
- WhatsApp API response monitoring
- Transaksi success rate tracking