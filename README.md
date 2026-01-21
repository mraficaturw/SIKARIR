# SIKARIR - Sistem Informas Karir / Unsika Karir

<p align="center">
  <img src="https://drive.google.com/file/d/1kdpxas7OP30mmjY4rZO_kxALMpo98hzw/view?usp=sharing" width="200" alt="SIKARIR Logo">
</p>

<p align="center">
  <strong>Platform Informasi Lowongan Kerja & Magang untuk Mahasiswa</strong>
</p>

---

## 📖 Tentang SIKARIR

SIKARIR adalah platform berbasis web yang dirancang untuk membantu mahasiswa mencari informasi lowongan kerja dan magang. Dibangun dengan **Laravel 12** dan **Filament v4** untuk panel admin yang modern dan powerful.

## ✨ Fitur Utama

### 🎨 UI/UX Modern & User-Friendly

- **Glassmorphism Design** - Tampilan modern dengan efek blur dan transparansi
- **Gradient Effects** - Warna-warna gradient yang menarik dan eye-catching
- **Smooth Animations** - Animasi yang halus menggunakan AOS (Animate On Scroll)
- **SPA-like Experience** - Navigasi tanpa reload halaman menggunakan Livewire
- **Responsive Design** - Tampilan optimal di semua ukuran perangkat
- **Dark/Light Mode Support** - Dukungan tema gelap dan terang

### 🏢 Manajemen Company

- **Company Database** - Database perusahaan yang terintegrasi
- **Company Detail Page** - Halaman detail perusahaan lengkap dengan:
  - Informasi perusahaan (nama, alamat, website)
  - Logo perusahaan
  - Deskripsi perusahaan
  - Daftar lowongan yang tersedia
- **Company Profile** - Profil perusahaan yang terstruktur

### 💼 Manajemen Lowongan

- **Job Listings** - Daftar lowongan kerja dan magang
- **Job Categories** - Kategorisasi lowongan (Full-time, Part-time, Magang, dll)
- **Job Search** - Pencarian lowongan dengan filter real-time
- **Job Detail** - Informasi lengkap lowongan termasuk:
  - Rentang gaji
  - Lokasi kerja
  - Persyaratan
  - Benefit

### 👤 Fitur User

- **Profile Management** - Kelola profil pengguna
- **Favorites** - Simpan lowongan favorit
- **Apply Status** - Lacak status lamaran
- **Faculty-based Filtering** - Filter lowongan berdasarkan fakultas

### 🔐 Admin Panel (Filament v4)

- **Dashboard** - Dashboard admin yang informatif
- **CRUD Operations** - Kelola data dengan mudah
- **User Management** - Manajemen pengguna
- **Company Management** - Kelola data perusahaan
- **Job Management** - Kelola lowongan kerja

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| **Laravel 12** | PHP Framework |
| **Filament v4** | Admin Panel |
| **Livewire** | Reactive Components |
| **Alpine.js** | Frontend Interactivity |
| **Tailwind CSS** | Styling |
| **PostgreSQL/MySQL** | Database |
| **Supabase** | Cloud Database & Storage |

## 🚀 Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL/MySQL Database

### Installation

1. **Clone repository**

   ```bash
   git clone https://github.com/mraficaturw/SIKARIR.git
   cd SIKARIR
   ```

2. **Install dependencies**

   ```bash
   composer install
   npm install
   ```

3. **Setup environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**

   Edit file `.env` dan sesuaikan konfigurasi database:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=sikarir
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run migrations**

   ```bash
   php artisan migrate --seed
   ```

6. **Build assets & run server**

   ```bash
   npm run dev
   php artisan serve
   ```

7. **Access the application**
   - Frontend: `http://localhost:8000`
   - Admin Panel: `http://localhost:8000/admin`

## 📁 Project Structure

```
SIKARIR/
├── app/
│   ├── Filament/          # Filament admin resources
│   ├── Http/
│   │   └── Controllers/   # Controllers
│   ├── Livewire/          # Livewire components
│   └── Models/            # Eloquent models
├── database/
│   ├── migrations/        # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── views/             # Blade templates
│       └── livewire/      # Livewire views
└── public/                # Public assets
```

## 📝 Recent Updates

### v1.1.0 - UI/UX Enhancement Update

- 🎨 **Redesigned UI** - Tampilan yang lebih modern dengan glassmorphism dan gradient effects
- ⚡ **Improved Performance** - Optimasi performa dengan Livewire components
- 🏢 **Company Integration** - Database perusahaan yang terintegrasi dengan `company_id` foreign key
- 📄 **Company Detail Page** - Halaman detail perusahaan yang lengkap
- 🔍 **Real-time Search** - Pencarian lowongan secara real-time
- ❤️ **Favorites System** - Sistem favorit yang responsif
- 📱 **Better Mobile Experience** - Tampilan mobile yang lebih optimal
- 🐛 **Bug Fix: Admin Panel Access** - Perbaikan error "Forbidden" saat mengakses Filament Admin Panel

## 🤝 Contributing

Contributions are welcome! Please read our contributing guidelines before submitting a pull request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
  Made with ❤️ by <strong>Rafi</strong>
</p>
