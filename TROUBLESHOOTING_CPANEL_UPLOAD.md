# Troubleshooting Upload File di cPanel

## Masalah: "No file found" di cPanel

### Penyebab Umum
1. **CSRF Token tidak terkirim dengan benar**
2. **POST request tidak berisi file** 
3. **Konfigurasi PHP di cPanel berbeda dengan local**
4. **Max upload size/post size terlalu kecil**

---

## Solusi yang Diterapkan

### 1. Update ChunkUploadController
- Menambahkan logging lengkap untuk debugging
- Menambahkan pengecekan file yang lebih detail
- Error message yang lebih informatif

### 2. Update chunk-upload.js
- Menambahkan validasi CSRF token sebelum init Resumable
- Menambahkan konfigurasi `fileParameterName: 'file'`
- Menambahkan header `Accept: application/json`
- Error handling yang lebih baik

### 3. CSRF Token Exception
- Menambahkan `pengurus/chunk-upload` ke dalam CSRF exception di `VerifyCsrfToken.php`
- Tetap mengirim token melalui header dan query parameter

---

## Konfigurasi PHP yang Harus Dicek di cPanel

### 1. Melalui cPanel File Manager
Edit file `.htaccess` di root public folder atau buat file `php.ini`:

```ini
upload_max_filesize = 2048M
post_max_size = 2048M
max_execution_time = 600
max_input_time = 600
memory_limit = 512M
```

### 2. Melalui cPanel > Select PHP Version
Pastikan pengaturan berikut:
- `upload_max_filesize`: 2048M
- `post_max_size`: 2048M
- `max_execution_time`: 600
- `max_input_time`: 600
- `memory_limit`: 512M

### 3. Melalui MultiPHP INI Editor di cPanel
Atur nilai yang sama seperti di atas.

---

## Cara Testing di cPanel

### 1. Cek Log Laravel
Akses file `storage/logs/laravel.log` melalui cPanel File Manager untuk melihat:
```
Chunk upload request received
- method: POST/GET
- has_file: true/false
- all_inputs: [...]
```

### 2. Test Upload Kecil Dulu
- Upload file < 10MB menggunakan upload biasa
- Jika berhasil, berarti konfigurasi dasar OK
- Lalu coba upload dengan chunk upload

### 3. Cek Browser Console
- Buka Developer Tools (F12)
- Tab Console: lihat log dari JavaScript
- Tab Network: lihat request/response chunk upload

### 4. Test CSRF Token
Buka browser console dan jalankan:
```javascript
console.log(document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
```
Harus menampilkan token yang valid (string panjang).

---

## Debugging Langkah per Langkah

### Step 1: Verifikasi Route
```bash
php artisan route:list | grep chunk
```
Pastikan route `/pengurus/chunk-upload` terdaftar.

### Step 2: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Step 3: Cek Permission Folder
```bash
chmod -R 775 storage/app/chunks
chmod -R 775 storage/app/public/materi-kotbah
chmod -R 775 storage/logs
```

### Step 4: Test dengan cURL
```bash
curl -X POST "https://yourdomain.com/pengurus/chunk-upload" \
  -F "file=@test.pdf" \
  -F "resumableChunkNumber=1" \
  -F "resumableTotalChunks=1" \
  -F "resumableIdentifier=test-123" \
  -F "resumableFilename=test.pdf" \
  -F "resumableChunkSize=2097152" \
  -H "Accept: application/json"
```

Jika berhasil, akan return JSON `{"status":"success",...}`

---

## Solusi Alternatif Jika Masih Error

### Opsi 1: Gunakan Upload Biasa dengan Ukuran Lebih Besar
Edit `config/livewire.php`:
```php
'rules' => 'max:102400', // 100MB
```

Dan update validasi di `MateriKotbah.php`.

### Opsi 2: Gunakan Storage External
- Upload ke AWS S3
- Upload ke Google Cloud Storage
- Atau gunakan Cloudinary

### Opsi 3: FTP Upload
Untuk file sangat besar (>500MB):
1. Upload file via FTP ke `storage/app/public/materi-kotbah/`
2. Generate nama file random: `abcdef123456.pptx`
3. Input manual ke database atau buat form khusus untuk itu

---

## File yang Diubah

1. ✅ `app/Http/Controllers/ChunkUploadController.php` - Tambah logging & validasi
2. ✅ `public/js/chunk-upload.js` - Perbaiki konfigurasi Resumable.js
3. ✅ `app/Http/Middleware/VerifyCsrfToken.php` - Tambah CSRF exception

---

## Testing Checklist

- [ ] Clear semua cache Laravel
- [ ] Verifikasi PHP settings di cPanel
- [ ] Test upload file kecil (<10MB) dengan upload biasa
- [ ] Test upload file sedang (50-100MB) dengan chunk upload
- [ ] Cek browser console untuk error JavaScript
- [ ] Cek `storage/logs/laravel.log` untuk error PHP
- [ ] Verifikasi CSRF token ada di meta tag
- [ ] Test di browser berbeda (Chrome, Firefox, Safari)
- [ ] Test dengan koneksi internet berbeda

---

## Kontak Support

Jika masalah masih berlanjut, kumpulkan informasi berikut:
1. Screenshot error di browser
2. Isi `storage/logs/laravel.log` (100 baris terakhir)
3. Screenshot Network tab di DevTools
4. Screenshot Console tab di DevTools
5. Versi PHP di cPanel
6. Ukuran file yang coba diupload
