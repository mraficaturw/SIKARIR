# 📚 SIKARIR - Dokumentasi Programming Lengkap

> **Sistem Informasi Karir / Unsika Karir**
> Platform Informasi Lowongan Kerja & Magang untuk Mahasiswa

---

## 📖 Daftar Isi

1. [Gambaran Umum Proyek](#1-gambaran-umum-proyek)
2. [Tech Stack](#2-tech-stack)
3. [Struktur Direktori](#3-struktur-direktori)
4. [Arsitektur Sistem](#4-arsitektur-sistem)
5. [Database Schema](#5-database-schema)
6. [Models](#6-models)
7. [Controllers](#7-controllers)
8. [Livewire Components](#8-livewire-components)
9. [Filament Admin Panel](#9-filament-admin-panel)
10. [Authentication System](#10-authentication-system)
11. [Routing](#11-routing)
12. [Services & Utilities](#12-services--utilities)
13. [Notification System](#13-notification-system)
14. [File Storage](#14-file-storage)
15. [Environment Configuration](#15-environment-configuration)
16. [Deployment](#16-deployment)
17. [Testing](#17-testing)

---

## 1. Gambaran Umum Proyek

**SIKARIR** adalah platform berbasis web yang dirancang untuk membantu mahasiswa mencari informasi lowongan kerja dan magang. Sistem ini menyediakan fitur pencarian lowongan, pengelolaan profil pengguna, sistem favorit, pelacakan status lamaran, dan panel admin yang powerful.

### Fitur Utama

| Kategori | Fitur |
|----------|-------|
| **UI/UX** | Glassmorphism Design, Gradient Effects, Smooth Animations (AOS), Responsive Design, Dark/Light Mode |
| **Company** | Database perusahaan, Halaman detail perusahaan, Logo & deskripsi |
| **Lowongan** | Job listings, Kategorisasi, Pencarian real-time, Detail lengkap (gaji, lokasi, persyaratan) |
| **User** | Profile management, Favorites, Apply tracking, Faculty-based filtering |
| **Admin** | Dashboard informatif, CRUD operations, User/Company/Job management |

---

## 2. Tech Stack

```
┌─────────────────────────────────────────────────────────────┐
│                        FRONTEND                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │ Blade Views  │──│   Livewire   │──│  Alpine.js   │       │
│  └──────────────┘  └──────────────┘  └──────────────┘       │
│  ┌──────────────┐  ┌──────────────┐                         │
│  │ Tailwind CSS │──│ UI Components│                         │
│  └──────────────┘  └──────────────┘                         │
├─────────────────────────────────────────────────────────────┤
│                        BACKEND                               │
│  ┌──────────────┐  ┌──────────────┐                         │
│  │  Laravel 12  │──│   PHP 8.2+   │                         │
│  └──────────────┘  └──────────────┘                         │
│  ┌──────────────┐                                           │
│  │ Filament v4  │                                           │
│  └──────────────┘                                           │
├─────────────────────────────────────────────────────────────┤
│                       DATABASE                               │
│  ┌──────────────┐  ┌──────────────┐                         │
│  │PostgreSQL/   │──│   Supabase   │                         │
│  │   MySQL      │  │   Storage    │                         │
│  └──────────────┘  └──────────────┘                         │
└─────────────────────────────────────────────────────────────┘
```

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 12.x | PHP Framework |
| **Filament** | 4.0 | Admin Panel |
| **Livewire** | 3.7+ | Reactive Components |
| **Alpine.js** | - | Frontend Interactivity |
| **Tailwind CSS** | - | Styling |
| **PostgreSQL/MySQL** | - | Database |
| **Supabase** | - | Cloud Storage |
| **Intervention Image** | 1.5+ | Image Processing |

---

## 3. Struktur Direktori

```
SIKARIR/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── Companies/          # Resource untuk perusahaan
│   │   │   │   ├── CompaniesResource.php
│   │   │   │   ├── Pages/
│   │   │   │   ├── Schemas/
│   │   │   │   └── Tables/
│   │   │   └── Internjobs/         # Resource untuk lowongan
│   │   │       ├── InternjobResource.php
│   │   │       ├── Pages/
│   │   │       ├── Schemas/
│   │   │       └── Tables/
│   │   └── Widgets/
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   ├── ResetPasswordController.php
│   │   │   │   └── VerificationController.php
│   │   │   └── InternjobController.php
│   │   └── Requests/
│   │       └── Auth/
│   │           ├── RegisterRequest.php
│   │           └── VerifyEmailRequest.php
│   │
│   ├── Livewire/
│   │   ├── ApplyButton.php
│   │   ├── FavoriteButton.php
│   │   └── JobSearch.php
│   │
│   ├── Models/
│   │   ├── Internjob.php           # Model lowongan
│   │   ├── UserAccount.php         # Model user (mahasiswa)
│   │   ├── User.php                # Model admin (Filament)
│   │   ├── companies.php           # Model perusahaan
│   │   └── PasswordChangeToken.php # Token perubahan password
│   │
│   ├── Notifications/
│   │   ├── CustomVerifyEmail.php
│   │   └── VerifyPasswordChange.php
│   │
│   ├── Providers/
│   └── Services/
│       └── ImageService.php
│
├── config/
│   ├── auth.php                    # Konfigurasi multi-guard auth
│   ├── filesystems.php             # Konfigurasi Supabase storage
│   └── ...
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── resources/
│   ├── views/
│   │   ├── auth/                   # Views autentikasi
│   │   ├── livewire/               # Views Livewire
│   │   ├── partials/               # Komponen partials
│   │   ├── profile/                # Views profil user
│   │   ├── job-detail.blade.php
│   │   ├── jobs.blade.php
│   │   ├── company-detail.blade.php
│   │   └── welcome.blade.php
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php                     # Routes utama
│   ├── api.php
│   └── console.php
│
└── public/
```

---

## 4. Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────────────┐
│                              CLIENT                                      │
│   ┌─────────────┐     ┌─────────────────┐                               │
│   │   Browser   │ ──▶ │   Blade Views   │                               │
│   └─────────────┘     └────────┬────────┘                               │
│                                │                                         │
│                       ┌────────▼────────┐                               │
│                       │Livewire Components│                              │
│                       └────────┬────────┘                               │
└────────────────────────────────┼────────────────────────────────────────┘
                                 │
┌────────────────────────────────┼────────────────────────────────────────┐
│                     APPLICATION LAYER                                    │
│                                │                                         │
│   ┌─────────────┐     ┌────────▼────────┐     ┌─────────────┐          │
│   │Routes web.php│ ──▶ │   Controllers   │ ──▶ │    Models   │          │
│   └─────────────┘     └─────────────────┘     └──────┬──────┘          │
│                                                       │                  │
│   ┌─────────────┐     ┌─────────────────┐            │                  │
│   │   Filament  │ ──▶ │    Resources    │ ───────────┤                  │
│   └─────────────┘     └─────────────────┘            │                  │
└──────────────────────────────────────────────────────┼──────────────────┘
                                                       │
┌──────────────────────────────────────────────────────┼──────────────────┐
│                        DATA LAYER                    │                   │
│                                                      │                   │
│   ┌─────────────────┐                    ┌───────────▼───────────┐      │
│   │    Database     │◀───────────────────│      Supabase         │      │
│   │(PostgreSQL/MySQL)│                    │      Storage          │      │
│   └─────────────────┘                    └───────────────────────┘      │
└─────────────────────────────────────────────────────────────────────────┘
```

### Flow Diagram - User Registration

```
┌────────┐     ┌──────────────────┐     ┌───────────────┐
│  User  │────▶│RegisterController│────▶│ UserAccount   │
└────────┘     └────────┬─────────┘     │    Model      │
                        │               └───────┬───────┘
                        │                       │
                        ▼                       │
               ┌────────────────┐               │
               │ Validate via   │               │
               │RegisterRequest │               │
               └────────────────┘               │
                        │                       │
                        ▼                       │
               ┌────────────────┐               │
               │  Create User   │◀──────────────┘
               │  (Hash Password)│
               └───────┬────────┘
                       │
                       ▼
               ┌────────────────┐     ┌───────────────┐
               │ Auto Login     │────▶│ Email Service │
               │ user_accounts  │     └───────┬───────┘
               └────────────────┘             │
                                              ▼
                                     ┌────────────────┐
                                     │ Send Verify    │
                                     │ Email          │
                                     └────────────────┘
```

---

## 5. Database Schema

### Entity Relationship Diagram

```
┌─────────────────────┐
│       USERS         │              ┌─────────────────────┐
│ (Admin Filament)    │              │    USER_ACCOUNTS    │
├─────────────────────┤              │    (Mahasiswa)      │
│ id (PK)             │              ├─────────────────────┤
│ name                │              │ id (PK)             │
│ email (UK)          │              │ name                │
│ password            │              │ email (UK)          │
│ email_verified_at   │              │ password            │
│ created_at          │              │ avatar              │
│ updated_at          │              │ email_verified_at   │
└─────────────────────┘              │ created_at          │
                                     │ updated_at          │
                                     └──────────┬──────────┘
                                                │
                    ┌───────────────────────────┼───────────────────────────┐
                    │                           │                           │
                    ▼                           ▼                           ▼
┌─────────────────────────────┐  ┌─────────────────────────┐  ┌─────────────────────────┐
│   USER_ACCOUNT_FAVORITES    │  │   USER_ACCOUNT_APPLIED  │  │  PASSWORD_CHANGE_TOKENS │
├─────────────────────────────┤  ├─────────────────────────┤  ├─────────────────────────┤
│ id (PK)                     │  │ id (PK)                 │  │ id (PK)                 │
│ user_account_id (FK)        │  │ user_account_id (FK)    │  │ user_id (FK)            │
│ internjob_id (FK)           │  │ internjob_id (FK)       │  │ new_password            │
│ created_at                  │  │ applied_at              │  │ token                   │
│ updated_at                  │  │ created_at              │  │ expires_at              │
└──────────────┬──────────────┘  │ updated_at              │  │ created_at              │
               │                 └────────────┬────────────┘  │ updated_at              │
               │                              │               └─────────────────────────┘
               └──────────────┬───────────────┘
                              │
                              ▼
                   ┌─────────────────────┐
                   │     INTERNJOBS      │
                   │    (Lowongan)       │
                   ├─────────────────────┤
                   │ id (PK)             │
                   │ title               │
                   │ company_id (FK) ────┼──────────┐
                   │ location            │          │
                   │ type                │          │
                   │ salary_min          │          │
                   │ salary_max          │          │
                   │ description         │          │
                   │ responsibility      │          │
                   │ qualifications      │          │
                   │ deadline            │          │
                   │ category            │          │
                   │ apply_url           │          │
                   │ created_at          │          │
                   │ updated_at          │          │
                   └─────────────────────┘          │
                                                    │
                              ┌─────────────────────┘
                              │
                              ▼
                   ┌─────────────────────┐
                   │     COMPANIES       │
                   │   (Perusahaan)      │
                   ├─────────────────────┤
                   │ id (PK)             │
                   │ company_name        │
                   │ logo                │
                   │ official_website    │
                   │ email               │
                   │ phone               │
                   │ address             │
                   │ company_description │
                   │ created_at          │
                   │ updated_at          │
                   └─────────────────────┘
```

### Relasi Antar Tabel

| Relasi | Tipe | Deskripsi |
|--------|------|-----------|
| Companies → Internjobs | One-to-Many | Satu perusahaan memiliki banyak lowongan |
| UserAccount → Favorites | Many-to-Many | User bisa memfavoritkan banyak lowongan |
| UserAccount → Applied | Many-to-Many | User bisa melamar ke banyak lowongan |
| UserAccount → PasswordToken | One-to-Many | User bisa punya token perubahan password |

### Migration Files

| File | Deskripsi |
|------|-----------|
| `0001_01_01_000000_create_users_table.php` | Tabel users untuk admin |
| `0001_01_01_000001_create_cache_table.php` | Tabel cache |
| `0001_01_01_000002_create_jobs_table.php` | Tabel jobs (Laravel queue) |
| `2025_10_02_173406_create_internjobs_table.php` | Tabel lowongan magang |
| `2025_10_09_000000_create_user_accounts_table.php` | Tabel akun pengguna |
| `2025_10_09_000001_create_user_favorites_table.php` | Tabel favorit |
| `2025_10_26_135215_create_user_applied_table.php` | Tabel lamaran |
| `2026_01_10_181804_create_companies_table.php` | Tabel perusahaan |
| `2026_01_11_000001_add_company_id_to_internjobs_table.php` | Relasi company-internjob |
| `2026_01_13_155338_create_password_change_tokens_table.php` | Token perubahan password |

---

## 6. Models

### 6.1 Internjob Model

**Path:** `app/Models/Internjob.php`

Model untuk lowongan kerja/magang.

```php
class Internjob extends Model
{
    protected $fillable = [
        'title',           // Judul posisi lowongan
        'company_id',      // ID perusahaan (foreign key)
        'location',        // Lokasi penempatan kerja
        'type',            // Tipe pekerjaan (full-time, part-time, magang)
        'salary_min',      // Gaji minimum
        'salary_max',      // Gaji maksimum
        'description',     // Deskripsi lengkap lowongan
        'responsibility',  // Tanggung jawab posisi
        'qualifications',  // Kualifikasi yang dibutuhkan
        'deadline',        // Batas waktu pendaftaran
        'category',        // Kategori berdasarkan fakultas
        'apply_url',       // Link untuk mendaftar
    ];

    protected $casts = [
        'deadline' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];
}
```

**Relationships:**

| Method | Type | Related Model | Description |
|--------|------|---------------|-------------|
| `company()` | belongsTo | companies | Perusahaan pemilik lowongan |
| `favoredBy()` | belongsToMany | UserAccount | Users yang memfavoritkan |
| `appliedBy()` | belongsToMany | UserAccount | Users yang melamar |

**Accessors:**

| Accessor | Return Type | Description |
|----------|-------------|-------------|
| `getLogoUrlAttribute()` | string | URL logo dari perusahaan terkait |

---

### 6.2 UserAccount Model

**Path:** `app/Models/UserAccount.php`

Model untuk akun pengguna (mahasiswa). Implements `MustVerifyEmail`.

```php
class UserAccount extends Authenticatable implements MustVerifyEmail
{
    protected $fillable = [
        'name',      // Nama lengkap pengguna
        'email',     // Alamat email (unik, untuk login)
        'password',  // Password ter-hash
        'avatar',    // Path file avatar di Supabase
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',  // Auto-hash saat di-set
        ];
    }
}
```

**Relationships:**

| Method | Type | Related Model | Pivot Table | Description |
|--------|------|---------------|-------------|-------------|
| `favorites()` | belongsToMany | Internjob | user_account_favorites | Lowongan favorit |
| `appliedJobs()` | belongsToMany | Internjob | user_account_applied | Lowongan yang dilamar |

**Helper Methods:**

| Method | Parameters | Return Type | Description |
|--------|------------|-------------|-------------|
| `hasFavorited($jobId)` | int | bool | Cek apakah sudah memfavoritkan lowongan |
| `hasApplied($jobId)` | int | bool | Cek apakah sudah melamar lowongan |
| `sendEmailVerificationNotification()` | - | void | Kirim email verifikasi custom |

---

### 6.3 Companies Model

**Path:** `app/Models/companies.php`

Model untuk data perusahaan.

```php
class companies extends Model
{
    protected $table = 'companies';
    
    protected $fillable = [
        'company_name',        // Nama perusahaan
        'logo',                // Path file logo di Supabase
        'official_website',    // Website resmi
        'email',               // Email kontak
        'phone',               // Nomor telepon
        'address',             // Alamat kantor
        'company_description', // Deskripsi perusahaan
    ];
}
```

**Relationships:**

| Method | Type | Related Model | Description |
|--------|------|---------------|-------------|
| `internjobs()` | hasMany | Internjob | Daftar lowongan dari perusahaan |

---

### 6.4 User Model (Admin)

**Path:** `app/Models/User.php`

Model untuk admin panel Filament. Implements `FilamentUser`.

```php
class User extends Authenticatable implements FilamentUser
{
    protected $fillable = ['name', 'email', 'password'];

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Semua user admin bisa akses
    }
}
```

---

### 6.5 PasswordChangeToken Model

**Path:** `app/Models/PasswordChangeToken.php`

Model untuk menyimpan token verifikasi perubahan password.

```php
class PasswordChangeToken extends Model
{
    protected $fillable = [
        'user_id',       // ID user yang meminta ganti password
        'new_password',  // Password baru (sudah di-hash)
        'token',         // Token acak untuk verifikasi
        'expires_at',    // Waktu expired token
    ];

    // Relasi ke UserAccount
    public function user(): BelongsTo;
    
    // Cek apakah token sudah expired
    public function isExpired(): bool;
}
```

---

## 7. Controllers

### 7.1 InternjobController

**Path:** `app/Http/Controllers/InternjobController.php`

Controller utama untuk mengelola tampilan lowongan.

| Method | Route | Description |
|--------|-------|-------------|
| `index(Request $request)` | GET `/` | Halaman utama dengan 6 lowongan terbaru |
| `jobs(Request $request)` | GET `/jobs` | Daftar semua lowongan dengan pagination |
| `show($id)` | GET `/job/{id}` | Detail lowongan |
| `toggleFavorite($id)` | POST `/jobs/{id}/favorite-toggle` | Toggle favorit |
| `toggleApplied($id)` | POST `/jobs/{id}/applied-toggle` | Toggle status lamaran |
| `companyDetail($id)` | GET `/company/{id}` | Detail perusahaan |

**Fitur Pencarian:**

Pencarian dilakukan di 3 kolom:
- `title` - Judul lowongan
- `company_name` - Nama perusahaan (melalui relasi)
- `description` - Deskripsi lowongan

**Kategori Fakultas:**

```php
$faculties = [
    'Fakultas Teknik',
    'Fakultas Ekonomi dan Bisnis',
    'Fakultas Ilmu Komputer',
    'Fakultas Hukum',
    'Fakultas Kesehatan',
    'Fakultas Pertanian',
    'Fakultas Ilmu Sosial dan Politik',
    'Fakultas Keguruan dan Ilmu Pendidikan',
    'Fakultas Agama Islam',
];
```

---

### 7.2 Auth Controllers

| Controller | Method | Route | Description |
|------------|--------|-------|-------------|
| **LoginController** | `showLoginForm()` | GET `/login` | Tampilkan form login |
| | `login()` | POST `/login` | Proses login dengan rate limiting |
| | `logout()` | POST `/logout` | Logout pengguna |
| **RegisterController** | `showRegisterForm()` | GET `/register` | Tampilkan form registrasi |
| | `register()` | POST `/register` | Proses registrasi + kirim email |
| **ProfileController** | `show()` | GET `/profile` | Tampilkan profil dengan favorit & lamaran |
| | `editForm()` | GET `/profile/edit` | Form edit profil |
| | `updateProfile()` | POST `/profile/update` | Update nama & avatar |
| | `changePasswordForm()` | GET `/change-password` | Form ganti password |
| | `changePassword()` | POST `/change-password` | Proses ganti password |
| | `verifyPasswordChange()` | GET `/password/verify/{token}` | Verifikasi dari email |
| **ForgotPasswordController** | `showLinkRequestForm()` | GET `/forgot-password` | Form request reset |
| | `sendResetLinkEmail()` | POST `/forgot-password` | Kirim link reset |
| **VerificationController** | `notice()` | GET `/email/verify` | Halaman notice verifikasi |
| | `verify()` | GET `/email/verify/{id}/{hash}` | Proses verifikasi email |
| | `send()` | POST `/email/verification-notification` | Kirim ulang email |

---

## 8. Livewire Components

### 8.1 JobSearch Component

**Path:** `app/Livewire/JobSearch.php`

Komponen pencarian lowongan dengan real-time filtering.

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `$search` | string | Kata kunci pencarian |
| `$category` | string | Filter kategori fakultas |

**Features:**
- Pagination terintegrasi
- Query string sync (URL berubah saat filter)
- Auto-reset pagination saat filter berubah

---

### 8.2 FavoriteButton Component

**Path:** `app/Livewire/FavoriteButton.php`

Tombol toggle favorit yang reactive.

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `$jobId` | int | ID lowongan |
| `$isFavorite` | bool | Status favorit |

**Toggle Logic:**
1. Cek autentikasi → redirect ke login jika belum
2. Cek verifikasi email → redirect jika belum
3. Toggle status di database
4. Update UI secara reactive

---

### 8.3 ApplyButton Component

**Path:** `app/Livewire/ApplyButton.php`

Tombol toggle status lamaran.

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `$jobId` | int | ID lowongan |
| `$isApplied` | bool | Status sudah melamar |
| `$applyUrl` | ?string | URL external untuk apply |

---

## 9. Filament Admin Panel

### 9.1 Access Configuration

**URL:** `/admin`

**Guard:** `admin` (menggunakan model `User`)

### 9.2 InternjobResource

**Path:** `app/Filament/Resources/Internjobs/InternjobResource.php`

Resource untuk mengelola lowongan magang.

**Form Fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| title | TextInput | ✓ | Nama posisi |
| company_id | Select | ✓ | Pilih perusahaan (searchable) |
| location | TextInput | ✓ | Lokasi penempatan |
| salary_min | TextInput | ✗ | Gaji minimum |
| salary_max | TextInput | ✗ | Gaji maksimum |
| description | Textarea | ✓ | Deskripsi posisi |
| responsibility | Textarea | ✗ | Tanggung jawab |
| qualifications | Textarea | ✗ | Kualifikasi |
| deadline | DatePicker | ✗ | Batas waktu lamaran |
| category | Select | ✓ | Fakultas terkait |
| apply_url | TextInput | ✗ | Link untuk apply |

### 9.3 CompaniesResource

**Path:** `app/Filament/Resources/Companies/CompaniesResource.php`

Resource untuk mengelola data perusahaan.

---

## 10. Authentication System

### Multi-Guard Authentication

SIKARIR menggunakan sistem multi-guard untuk memisahkan autentikasi.

```
┌─────────────────────────────────────────────────────────────┐
│                        GUARDS                                │
│                                                             │
│   ┌──────────────────┐      ┌──────────────────┐           │
│   │   user_accounts  │      │      admin       │           │
│   │                  │      │                  │           │
│   │  ┌────────────┐  │      │  ┌────────────┐  │           │
│   │  │UserAccount │  │      │  │    User    │  │           │
│   │  │   Model    │  │      │  │   Model    │  │           │
│   │  └────────────┘  │      │  └────────────┘  │           │
│   │                  │      │                  │           │
│   │  Untuk Mahasiswa │      │  Untuk Admin    │           │
│   └──────────────────┘      └──────────────────┘           │
└─────────────────────────────────────────────────────────────┘
```

**Configuration (config/auth.php):**

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'user_accounts',
    ],
    'user_accounts' => [
        'driver' => 'session',
        'provider' => 'user_accounts',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'admin',
    ],
],

'providers' => [
    'admin' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'user_accounts' => [
        'driver' => 'eloquent',
        'model' => App\Models\UserAccount::class,
    ],
],
```

### Rate Limiting (LoginController)

| Parameter | Value | Description |
|-----------|-------|-------------|
| `$maxAttempts` | 5 | Maksimum percobaan login |
| `$decaySeconds` | 300 | Durasi lockout (5 menit) |

### Password Requirements (RegisterRequest)

```php
Password::min(8)      // Minimal 8 karakter
    ->mixedCase()     // Huruf besar & kecil
    ->numbers()       // Angka
    ->symbols()       // Simbol
    ->uncompromised() // Tidak ada di database breach
```

---

## 11. Routing

### 11.1 Public Routes

| Route | Method | Controller | Name |
|-------|--------|------------|------|
| `/` | GET | InternjobController@index | welcome |
| `/jobs` | GET | InternjobController@jobs | jobs |
| `/job/{id}` | GET | InternjobController@show | job.detail |
| `/company/{id}` | GET | InternjobController@companyDetail | company.detail |
| `/login` | GET, POST | LoginController | login |
| `/register` | GET, POST | RegisterController | register |
| `/forgot-password` | GET, POST | ForgotPasswordController | password.request |

### 11.2 Email Verification Routes

| Route | Method | Middleware | Name |
|-------|--------|------------|------|
| `/email/verify` | GET | auth:user_accounts | verification.notice |
| `/email/verify/{id}/{hash}` | GET | auth:user_accounts, signed | verification.verify |
| `/email/verification-notification` | POST | auth:user_accounts, throttle:6,1 | verification.send |

### 11.3 Protected Routes

| Route | Method | Middleware | Name |
|-------|--------|------------|------|
| `/profile` | GET | auth, verified | profile.show |
| `/profile/edit` | GET | auth, verified | profile.edit |
| `/profile/update` | POST | auth, verified | profile.update |
| `/change-password` | GET, POST | auth, verified | profile.change-password |
| `/jobs/{id}/favorite-toggle` | POST | auth:user_accounts | job.favorite.toggle |
| `/jobs/{id}/applied-toggle` | POST | auth:user_accounts | job.applied.toggle |

---

## 12. Services & Utilities

### ImageService

**Path:** `app/Services/ImageService.php`

Service untuk konversi dan upload gambar ke WebP format.

| Method | Parameters | Return | Description |
|--------|------------|--------|-------------|
| `convertToWebp()` | UploadedFile, quality | string | Konversi ke WebP |
| `convertAndUpload()` | UploadedFile, disk, directory, quality | string | Konversi dan upload |

**Keuntungan format WebP:**
- Ukuran file 25-35% lebih kecil dari JPEG
- Mendukung transparansi (seperti PNG)
- Didukung oleh semua browser modern

**Contoh Penggunaan:**

```php
// Upload avatar ke Supabase
$path = ImageService::convertAndUpload(
    $request->file('avatar'),
    'supabase-avatar',    // Nama disk
    'avatars',            // Folder
    80                    // Kualitas
);
// Result: "avatars/john_1705123456.webp"
```

---

## 13. Notification System

### 13.1 CustomVerifyEmail

**Path:** `app/Notifications/CustomVerifyEmail.php`

Email verifikasi akun yang dikustomisasi dalam Bahasa Indonesia.

**Email Content:**
- Subject: "Verifikasi Email - SIKARIR"
- Greeting: "Halo!"
- Body: Selamat datang + instruksi verifikasi
- Action: Tombol "Verifikasi Email Saya"
- Expires: 60 menit

### 13.2 VerifyPasswordChange

**Path:** `app/Notifications/VerifyPasswordChange.php`

Email verifikasi perubahan password.

**Email Content:**
- Subject: "Verifikasi Perubahan Password - SIKARIR"
- Greeting: "Halo {nama}!"
- Body: Konfirmasi perubahan password
- Action: Tombol "Konfirmasi Perubahan Password"
- Expires: 60 menit

---

## 14. File Storage

### Storage Disks Configuration

**Path:** `config/filesystems.php`

| Disk | Driver | Purpose |
|------|--------|---------|
| `local` | local | File privat lokal |
| `public` | local | File publik lokal |
| `supabase` | s3 | Logo perusahaan |
| `supabase-avatar` | s3 | Avatar pengguna |

### Supabase Storage Configuration

```php
'supabase' => [
    'driver' => 's3',
    'key' => env('SUPABASE_STORAGE_KEY'),
    'secret' => env('SUPABASE_STORAGE_SECRET'),
    'region' => env('SUPABASE_STORAGE_REGION', 'ap-southeast-1'),
    'bucket' => env('SUPABASE_STORAGE_BUCKET', 'company-logos'),
    'endpoint' => env('SUPABASE_STORAGE_ENDPOINT'),
    'url' => env('SUPABASE_STORAGE_URL'),
    'use_path_style_endpoint' => true,
    'visibility' => 'public',
],
```

---

## 15. Environment Configuration

### Required Environment Variables

```env
# Application
APP_NAME=SIKARIR
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sikarir
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail (untuk email verification)
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sikarir.com
MAIL_FROM_NAME="${APP_NAME}"

# Supabase Storage
SUPABASE_STORAGE_KEY=your_key
SUPABASE_STORAGE_SECRET=your_secret
SUPABASE_STORAGE_REGION=ap-southeast-1
SUPABASE_STORAGE_BUCKET=company-logos
SUPABASE_STORAGE_ENDPOINT=https://xxx.supabase.co/storage/v1
SUPABASE_STORAGE_URL=https://xxx.supabase.co/storage/v1/object/public/company-logos
SUPABASE_AVATAR_URL=https://xxx.supabase.co/storage/v1/object/public/avatar
```

---

## 16. Deployment

### Vercel Configuration

**Path:** `vercel.json`

SIKARIR sudah dikonfigurasi untuk deployment di Vercel.

### Deployment Steps

```bash
# 1. Build assets
npm run build

# 2. Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Run migrations
php artisan migrate --force
```

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper mail driver
- [ ] Setup Supabase storage credentials
- [ ] Run database migrations
- [ ] Set proper HTTPS/SSL

---

## 17. Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Or with composer script
composer test
```

### Test Configuration

**Path:** `phpunit.xml`

---

## 📝 Quick Reference

### Common Artisan Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh install with seeder
php artisan db:seed              # Run seeders

# Cache
php artisan cache:clear          # Clear cache
php artisan config:clear         # Clear config cache
php artisan view:clear           # Clear view cache

# Development
php artisan serve                # Start dev server
composer dev                     # Start all services

# Filament
php artisan make:filament-resource  # Create new resource
```

### Useful Links

| Resource | URL |
|----------|-----|
| Frontend | `http://localhost:8000` |
| Admin Panel | `http://localhost:8000/admin` |

---

> **📌 Catatan:** Dokumentasi ini dibuat berdasarkan analisis kode pada tanggal 20 Januari 2026.
