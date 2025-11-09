# Panduan Deploy & Fix Upload Error di cPanel

## 🐛 Error yang Terjadi
```
Upload gagal:
{"status":"error","message":"No file found"}
Silakan coba lagi
```

Error ini hanya muncul di cPanel, tidak di localhost.

---

## ✅ Solusi yang Sudah Diterapkan

### 1. Perbaikan Backend
- ✅ Update `ChunkUploadController.php` dengan logging lengkap
- ✅ Tambah validasi request yang lebih detail
- ✅ Error message yang lebih informatif
- ✅ CSRF exception untuk route `/pengurus/chunk-upload`

### 2. Perbaikan Frontend
- ✅ Update `chunk-upload.js` dengan konfigurasi lebih lengkap
- ✅ Validasi CSRF token sebelum init Resumable.js
- ✅ Tambah header `Accept: application/json`
- ✅ Error handling yang lebih baik dengan parsing JSON

### 3. Konfigurasi Server
- ✅ Update `.htaccess` dengan PHP settings untuk upload besar
- ✅ Buat file `.user.ini` untuk override PHP settings
- ✅ Buat test script `test-upload-config.php`

---

## 📦 File yang Harus Diupload ke cPanel

Setelah pull/download kode terbaru, upload file-file berikut:

### File Wajib (Sudah Ada di Repo)
```
app/Http/Controllers/ChunkUploadController.php
app/Http/Middleware/VerifyCsrfToken.php
public/js/chunk-upload.js
public/.htaccess
.user.ini (upload ke root directory, sejajar dengan public_html)
```

### File Testing (Opsional)
```
public/test-upload-config.php (untuk testing konfigurasi PHP)
public/create-directories.php (untuk auto-create directories yang hilang)
public/clear-cache.php (untuk clear cache via browser)
```

---

## 🚀 Langkah-langkah Deploy ke cPanel

### Step 1: Upload File via FTP/File Manager
1. Login ke cPanel
2. Buka **File Manager**
3. Upload file yang sudah diubah ke lokasi yang sesuai
4. Upload `.user.ini` ke **root directory** (di luar public_html)

### Step 2: Set Permissions
Via File Manager atau SSH:
```bash
chmod -R 775 storage/app/chunks
chmod -R 775 storage/app/public/materi-kotbah
chmod -R 775 storage/logs
chmod -R 775 bootstrap/cache
```

**⚠️ PENTING - Jika Directory Tidak Ada:**

Jika folder-folder tersebut belum ada, buat dulu dengan salah satu cara berikut:

#### Cara A: Via Script Auto-Create (Paling Mudah)
1. Akses: `https://yourdomain.com/create-directories.php?key=gbi-plc-2024`
2. Klik tombol **"Create All Missing Directories"**
3. Tunggu proses selesai
4. Hapus file `create-directories.php` setelah selesai

#### Cara B: Via SSH
```bash
cd /home/username/public_html  # Sesuaikan path
mkdir -p storage/app/chunks
mkdir -p storage/app/public/materi-kotbah
mkdir -p storage/logs
mkdir -p bootstrap/cache
chmod -R 775 storage/
chmod -R 775 bootstrap/cache
```

#### Cara C: Via cPanel File Manager
1. Login ke cPanel → File Manager
2. Navigate ke root directory Laravel
3. Buat folder:
   - `storage/app/chunks`
   - `storage/app/public/materi-kotbah`
   - `storage/logs`
   - `bootstrap/cache`
4. Right-click setiap folder → Change Permissions → Set to **755** atau **775**


### Step 3: Clear Cache Laravel
Via SSH atau Terminal di cPanel:
```bash
cd /home/username/public_html  # Sesuaikan path
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Atau buat file `clear-cache.php` di public folder:
```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->call('config:clear');
$kernel->call('cache:clear');
$kernel->call('route:clear');
$kernel->call('view:clear');

echo "Cache cleared successfully!";
```

Lalu akses: `https://yourdomain.com/clear-cache.php`

### Step 4: Cek Konfigurasi PHP
Akses: `https://yourdomain.com/test-upload-config.php`

Pastikan semua centang hijau ✓. 

**⚠️ Jika Ada Directory NOT Found:**
- Jangan panik! Ini normal di fresh install
- Klik tombol **"Auto-Create Missing Directories"** di halaman test
- Atau akses: `https://yourdomain.com/create-directories.php?key=gbi-plc-2024`
- Klik tombol untuk auto-create semua directory yang dibutuhkan
- Refresh halaman test-upload-config.php untuk verifikasi

Jika masih ada yang merah ✗, lakukan Step 5.

### Step 5: Update PHP Settings di cPanel

#### Metode A: Via cPanel > Select PHP Version
1. Login cPanel
2. Cari **Select PHP Version**
3. Klik **Switch To PHP Options**
4. Set nilai berikut:
   - `upload_max_filesize`: **2048M**
   - `post_max_size`: **2048M**
   - `max_execution_time`: **600**
   - `max_input_time`: **600**
   - `memory_limit`: **512M**
5. Klik **Save**

#### Metode B: Via MultiPHP INI Editor
1. Login cPanel
2. Cari **MultiPHP INI Editor**
3. Pilih domain Anda
4. Set nilai yang sama seperti Metode A
5. Klik **Apply**

#### Metode C: Edit .htaccess (Sudah Dilakukan)
File `public/.htaccess` sudah diupdate dengan PHP settings.

### Step 6: Restart Web Server (Opsional)
Beberapa host memerlukan restart:
- Via cPanel: **Restart PHP-FPM** atau **Restart Apache**
- Atau tunggu 5-10 menit untuk auto-reload

### Step 7: Testing Upload
1. Login sebagai pengurus
2. Buka halaman Materi Kotbah
3. Klik **Toggle** ke "Upload Cepat"
4. Pilih file (PDF/PPT/PPTX)
5. Perhatikan:
   - Progress bar harus jalan
   - Console browser (F12) tidak ada error merah
   - Upload selesai dengan sukses

---

## 🔍 Troubleshooting

### Jika Masih Error "No file found"

#### 1. Cek Browser Console
Buka DevTools (F12) → Console tab:
```javascript
// Cek CSRF token
console.log(document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));

// Harus menampilkan token panjang seperti:
// "asdfghjkl1234567890qwertyuiop..."
```

#### 2. Cek Network Tab
1. Buka DevTools (F12) → Network tab
2. Klik tombol Upload
3. Cari request ke `/pengurus/chunk-upload`
4. Klik request tersebut
5. Tab **Headers**: 
   - Method harus **POST**
   - Status harus **200** atau **201**
6. Tab **Payload**:
   - Harus ada `file` (binary data)
   - Harus ada `resumableIdentifier`
   - Harus ada `resumableChunkNumber`
7. Tab **Response**:
   - Jika error, akan ada message detail

#### 3. Cek Laravel Log
Via File Manager, buka:
```
storage/logs/laravel.log
```

Cari log terbaru dengan keyword:
```
Chunk upload request received
```

Log akan menampilkan:
- `method`: GET atau POST
- `has_file`: true atau false
- `all_inputs`: array parameters
- Error jika ada

#### 4. Test dengan cURL
Via SSH atau Terminal lokal:
```bash
curl -X POST "https://yourdomain.com/pengurus/chunk-upload" \
  -F "file=@test.pdf" \
  -F "resumableChunkNumber=1" \
  -F "resumableTotalChunks=1" \
  -F "resumableIdentifier=test-123" \
  -F "resumableFilename=test.pdf" \
  -F "resumableChunkSize=2097152"
```

Response harus JSON:
```json
{
  "status": "success",
  "message": "File uploaded successfully",
  "filename": "materi-kotbah/xxxxxxxx.pdf"
}
```

---

## 🔄 Alternatif Jika Chunk Upload Tetap Gagal

### Opsi 1: Gunakan Upload Biasa dengan File Lebih Kecil
Tetap di mode "Upload Biasa", tapi batasi ukuran file:
- Kompress file PPTX
- Convert PPTX ke PDF dengan kompresi
- Split file besar jadi beberapa file kecil

### Opsi 2: Upload via FTP
Untuk file sangat besar (>500MB):
1. Upload file via FTP ke `storage/app/public/materi-kotbah/`
2. Rename file dengan format: `random-hash.pptx`
3. Insert manual ke database atau buat form input manual di admin

### Opsi 3: Gunakan Storage External
- AWS S3
- Google Cloud Storage
- Cloudinary
- DropBox API

---

## 📝 Checklist Deploy

- [ ] Upload semua file yang diubah ke cPanel
- [ ] Upload `.user.ini` ke root directory
- [ ] **Create missing directories** (via create-directories.php atau manual)
- [ ] Set permissions folder storage (chmod 775)
- [ ] Clear cache Laravel
- [ ] Test konfigurasi PHP via `test-upload-config.php`
- [ ] **Verifikasi semua directories sudah ada dan writable**
- [ ] Update PHP settings jika perlu
- [ ] Create symbolic link: `php artisan storage:link` (jika belum)
- [ ] Test upload file kecil (<10MB) dengan upload biasa
- [ ] Test upload file besar (>50MB) dengan chunk upload
- [ ] Cek browser console tidak ada error
- [ ] Cek Laravel log tidak ada error
- [ ] **Delete semua test files** (`test-upload-config.php`, `create-directories.php`, `clear-cache.php`) setelah selesai testing

---

## 📞 Support

Jika masih bermasalah, kumpulkan:
1. Screenshot error di browser
2. Screenshot Network tab (request/response)
3. Screenshot Console tab
4. File `storage/logs/laravel.log` (100 baris terakhir)
5. Screenshot hasil `test-upload-config.php`
6. Ukuran file yang dicoba diupload

---

## 🎯 Expected Result

Setelah deploy:
1. ✅ Upload file kecil (<50MB) berhasil dengan upload biasa
2. ✅ Upload file besar (>50MB) berhasil dengan chunk upload
3. ✅ Progress bar berjalan lancar
4. ✅ File tersimpan di `storage/app/public/materi-kotbah/`
5. ✅ Data tersimpan di database
6. ✅ Tidak ada error di console browser
7. ✅ Tidak ada error di Laravel log
