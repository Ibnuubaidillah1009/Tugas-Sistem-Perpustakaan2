# Sistem Perpustakaan - Laravel 11

Sistem perpustakaan modern yang dibangun dengan Laravel 11, dilengkapi dengan multi-role authentication dan UI yang profesional.

## Fitur Utama

### 🔐 Multi-Role Authentication
- **Perpustakawan**: Kelola buku, peminjaman, dan user
- **Guru**: Pinjam buku dan lihat stok
- **Siswa**: Pinjam buku dan lihat stok

### 📚 Manajemen Buku
- Tambah, edit, hapus buku
- Kategori buku (Fiksi, Non-Fiksi, Pendidikan, Teknologi, dll)
- Stok buku otomatis
- Pencarian dan filter buku

### 📖 Sistem Peminjaman
- Peminjaman buku dengan tanggal jatuh tempo
- Status peminjaman (Dipinjam, Dikembalikan, Terlambat)
- Riwayat peminjaman lengkap
- Pengembalian buku
- **Sistem Denda Otomatis**: Denda dimulai setelah 7 hari terlambat, bertambah Rp 2.000 setiap 7 hari

### 👥 Manajemen User
- Daftar user baru (Guru/Siswa)
- Edit informasi user
- Ubah role user
- Hapus user

### 🎨 UI/UX Profesional
- Desain modern dengan Tailwind CSS
- Sidebar navigation responsif
- Dashboard dengan statistik
- Color scheme yang kontras dan profesional

## Instalasi

### Prerequisites
- PHP 8.1 atau lebih tinggi
- Composer
- SQLite (default) atau MySQL/PostgreSQL

### Langkah Instalasi

1. **Clone repository**
```bash
git clone <repository-url>
cd library-system
```

2. **Install dependencies**
```bash
composer install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Setup database**
```bash
php artisan migrate
php artisan db:seed
```

5. **Jalankan server**
```bash
php artisan serve
```

## Akun Default

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

### Perpustakawan
- Email: `admin@perpustakaan.com`
- Password: `password`

### Guru
- Email: `guru@perpustakaan.com`
- Password: `password`

### Siswa
- Email: `siswa@perpustakaan.com`
- Password: `password`

## Struktur Database

### Users
- `id`, `name`, `email`, `password`, `role`, `timestamps`

### Books
- `id`, `title`, `author`, `isbn`, `description`, `stock`, `category`, `publication_year`, `publisher`, `timestamps`

### Borrowings
- `id`, `user_id`, `book_id`, `borrow_date`, `return_date`, `due_date`, `status`, `notes`, `timestamps`

## Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Tailwind CSS, Font Awesome
- **Database**: SQLite (default)
- **Authentication**: Laravel Auth
- **Middleware**: Role-based access control

## Fitur Per Role

### Perpustakawan
- ✅ Dashboard dengan statistik lengkap
- ✅ Kelola buku (CRUD)
- ✅ Kelola peminjaman (CRUD)
- ✅ Kelola user (CRUD)
- ✅ Lihat riwayat peminjaman semua user
- ✅ Kelola sistem denda
- ✅ Bayar denda untuk user

### Guru
- ✅ Dashboard dengan statistik personal
- ✅ Lihat daftar buku tersedia
- ✅ Pinjam buku
- ✅ Lihat riwayat peminjaman sendiri
- ✅ Kembalikan buku
- ✅ Bayar denda sendiri

### Siswa
- ✅ Dashboard dengan statistik personal
- ✅ Lihat daftar buku tersedia
- ✅ Pinjam buku
- ✅ Lihat riwayat peminjaman sendiri
- ✅ Kembalikan buku
- ✅ Bayar denda sendiri

## Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## Sistem Denda

### Aturan Denda
- **Grace Period**: 7 hari setelah jatuh tempo (tidak ada denda)
- **Denda Dimulai**: Setelah 7 hari terlambat
- **Perhitungan**: Rp 2.000 setiap 7 hari tambahan
- **Contoh**:
  - 1-7 hari terlambat: Rp 0
  - 8-14 hari terlambat: Rp 2.000
  - 15-21 hari terlambat: Rp 4.000
  - 22-28 hari terlambat: Rp 6.000
  - Dan seterusnya...

### Command untuk Menghitung Denda
```bash
php artisan fines:calculate
```

### Fitur Denda
- ✅ Perhitungan otomatis saat akses sistem
- ✅ Tampilan denda di dashboard dan daftar peminjaman
- ✅ Tombol bayar denda untuk user
- ✅ Status pembayaran denda (Lunas/Belum Lunas)
- ✅ Riwayat perhitungan denda

## Changelog

### v1.1.0
- ✅ Sistem denda otomatis
- ✅ Register hanya untuk guru dan siswa
- ✅ Perhitungan denda berdasarkan keterlambatan
- ✅ UI untuk pembayaran denda
- ✅ Statistik denda di dashboard

### v1.0.0
- Initial release
- Multi-role authentication
- Book management
- Borrowing system
- User management
- Professional UI/UX
