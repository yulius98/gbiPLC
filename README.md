# GBI PLC - Aplikasi Gereja Bethel Indonesia PLC

## 📋 Deskripsi

GBI PLC adalah sistem informasi gereja yang terdiri dari backend API dan aplikasi mobile untuk jemaat Gereja Bethel Indonesia PLC. Aplikasi ini memfasilitasi komunikasi dan pengelolaan informasi gereja secara digital, termasuk materi kotbah, pembacaan Alkitab, jadwal ibadah, dan informasi jemaat.

## 🎯 Fitur Utama

### Backend (API)
- **Autentikasi & Otorisasi**: Sistem login dengan JWT dan role-based access control (Jemaat & Pengurus)
- **Manajemen Jemaat**: Profil jemaat, data ulang tahun, dan Life Group
- **Konten Gereja**: 
  - Materi Kotbah
  - Pastor Note
  - Pembacaan Alkitab (Reading)
  - Jadwal Ibadah Raya
- **Event Management**: Pengelolaan acara dan kegiatan gereja
- **Carousel**: Banner dan informasi dinamis di aplikasi mobile
- **Password Reset**: Fitur reset password via email
- **Dashboard Admin**: Dashboard khusus untuk pengurus gereja

### Mobile App
- **Pembacaan Alkitab**: Audio player untuk mendengarkan bacaan Alkitab
- **Video Player**: Memutar video kotbah dan materi
- **PDF Viewer**: Membaca materi dalam format PDF
- **Image Gallery**: Carousel slider untuk banner dan informasi
- **Media Upload**: Upload foto/dokumen ke server
- **YouTube Integration**: Integrasi pemutar video YouTube
- **Secure Storage**: Penyimpanan aman untuk credentials dan data sensitif

## 🛠️ Tech Stack

### Backend (gbiPLC/)

#### Framework & Core
- **PHP 8.2+**: Bahasa pemrograman utama
- **Laravel 12**: Framework PHP modern untuk backend
- **Laravel Livewire 3.6**: Framework full-stack reactive untuk komponen UI

#### Authentication & Security
- **Tymon JWT-Auth 2.2**: JSON Web Token untuk autentikasi API
- **Laravel Sanctum 4.0**: Token API authentication

#### Database & Storage
- **Laravel Eloquent ORM**: Object-Relational Mapping
- **AWS S3 (League Flysystem)**: Cloud storage untuk media files
- **Laravel Queue**: Background job processing

#### Image Processing & HTTP
- **Intervention Image 3.11**: Library manipulasi gambar
- **Guzzle HTTP 7.9**: HTTP client untuk request eksternal

#### Frontend Assets
- **Vite 6.2**: Modern build tool dan bundler
- **Tailwind CSS 4.1**: Utility-first CSS framework
- **Axios 1.8**: HTTP client untuk JavaScript
- **Laravel Vite Plugin**: Integrasi Laravel dengan Vite

#### Development Tools
- **Laravel Tinker**: REPL untuk debugging
- **Laravel Pail**: Log viewer real-time
- **PHPUnit 11.5**: Testing framework
- **Mockery**: Mocking library untuk testing
- **Laravel Pint**: Code style fixer

### Mobile App (plc_mobile/)

#### Framework & Language
- **Flutter 3.8.1+**: Cross-platform mobile framework
- **Dart**: Bahasa pemrograman

#### Media & Playback
- **video_player 2.8**: Video playback
- **audioplayers 6.1**: Audio playback
- **just_audio 0.9**: Advanced audio player
- **youtube_player_flutter 9.1**: YouTube video integration

#### UI & Display
- **google_fonts 6.1**: Custom fonts dari Google
- **carousel_slider 5.1**: Slider/banner carousel
- **cupertino_icons 1.0**: iOS style icons

#### Networking & Data
- **http 1.2**: HTTP client untuk API calls
- **http_parser 4.0**: HTTP parsing
- **intl 0.20**: Internationalization (format tanggal, mata uang, dll)

#### File & Storage
- **flutter_pdfview 1.3**: PDF viewer
- **path_provider 2.1**: Access ke file system paths
- **path 1.9**: File path manipulation
- **image_picker 1.0**: Pick images dari gallery/camera
- **open_file 3.2**: Membuka file dengan aplikasi eksternal
- **flutter_secure_storage 9.2**: Secure storage untuk data sensitif
- **image 4.0**: Image manipulation

#### Permissions & Utilities
- **permission_handler 12.0**: Runtime permissions (Android/iOS)
- **url_launcher 6.2**: Launch URLs dan external apps
- **mime 2.0**: MIME type detection

#### Development Tools
- **flutter_launcher_icons 0.14**: Generate app icons
- **flutter_lints 6.0**: Linting rules
- **flutter_test**: Testing framework

## 📁 Struktur Proyek

```
GBI PLC Full/
├── gbiPLC/              # Backend Laravel API
│   ├── app/             # Application logic
│   │   ├── Http/        # Controllers, Middleware, Requests
│   │   ├── Models/      # Eloquent models
│   │   ├── Livewire/    # Livewire components
│   │   └── Services/    # Business logic services
│   ├── config/          # Configuration files
│   ├── database/        # Migrations & seeders
│   ├── routes/          # API & web routes
│   ├── public/          # Public assets & entry point
│   │   ├── musics/      # Audio files
│   │   └── videos/      # Video files
│   └── storage/         # File storage
│
└── plc_mobile/          # Flutter Mobile App
    ├── lib/             # Dart source code
    ├── assets/          # Images, icons, themes
    ├── android/         # Android-specific code
    └── ios/             # iOS-specific code
```

## 🚀 Instalasi & Setup

### Backend (Laravel)

```bash
cd gbiPLC

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Generate JWT secret
php artisan jwt:secret

# Build assets
npm run build

# Run development server
php artisan serve
```

### Mobile App (Flutter)

```bash
cd plc_mobile

# Get dependencies
flutter pub get

# Run on device/emulator
flutter run

# Build APK (Android)
flutter build apk

# Build iOS
flutter build ios
```

## 🔧 Konfigurasi

### Backend
- Configure `.env` file dengan database credentials
- Setup AWS S3 untuk media storage
- Configure JWT settings di `config/jwt.php`
- Setup mail server untuk password reset

### Mobile App
- Update API endpoint di konfigurasi app
- Configure permissions di `AndroidManifest.xml` dan `Info.plist`
- Setup app icons dengan `flutter_launcher_icons`

## 📱 Platform Support

- **Android**: API 21+ (Android 5.0 Lollipop)
- **iOS**: iOS 12+
- **Web**: Backend admin panel (Livewire)

## 👥 Role & Permissions

- **Jemaat**: Access basic features (profile, kotbah, events)
- **Pengurus**: Admin access (dashboard, manage content)

## 📄 License

MIT License

## 👨‍💻 Development Team

GBI PLC Development Team

---

**Note**: Aplikasi ini dikembangkan untuk keperluan internal Gereja Bethel Indonesia PLC.
