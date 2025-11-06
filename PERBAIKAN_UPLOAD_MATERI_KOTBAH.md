# Perbaikan Upload File PPTX Materi Kotbah

## Masalah
File kotbah dengan format PPTX tidak tersimpan di `storage/materi-kotbah` ketika user klik tombol SIMPAN di halaman materi_kotbah.blade.php.

## Penyebab Masalah
1. **Folder Livewire Temporary tidak ada**: Livewire memerlukan folder `storage/app/livewire-tmp` untuk menyimpan file temporary sebelum dipindahkan ke lokasi permanen
2. **Validasi tidak konsisten**: Method `simpan()` tidak memiliki validasi `max:10240` seperti di method `update()`
3. **Konfigurasi Livewire belum dipublish**: File konfigurasi Livewire tidak ada sehingga menggunakan default yang mungkin tidak sesuai
4. **Tidak ada error handling yang memadai**: Tidak ada logging untuk debug ketika terjadi error
5. **Tidak ada loading indicator**: User tidak tahu apakah file sedang diupload atau tidak

## Solusi yang Diterapkan

### 1. Membuat Folder Livewire Temporary
```bash
mkdir -p storage/app/livewire-tmp
chmod 775 storage/app/livewire-tmp
```

### 2. Publish dan Konfigurasi Livewire
```bash
php artisan livewire:publish --config
```

Kemudian update `config/livewire.php`:
- Set `disk` ke `'local'`
- Set `rules` untuk file upload: `['file', 'max:10240']`
- Set `directory` ke `'livewire-tmp'`
- Tambahkan `'pdf', 'ppt', 'pptx'` ke `preview_mimes`
- Tingkatkan `max_upload_time` dari 5 menit ke 10 menit

### 3. Update MateriKotbah.php Component
**Perubahan pada method `simpan()`:**
- Tambahkan validasi `max:10240` (10MB)
- Ubah pengecekan file dari `if ($this->filename != null)` ke `if ($this->filename && is_object($this->filename))`
- Tambahkan try-catch untuk error handling
- Tambahkan logging untuk debugging
- Tambahkan import `use Illuminate\Support\Facades\Log;`

**Perubahan pada method `clear()`:**
- Ubah `$this->filename = '';` menjadi `$this->filename = null;`
- Tambahkan `$this->resetValidation();`

### 4. Update View (materi-kotbah.blade.php)
- Tambahkan loading indicator dengan `wire:loading` untuk file upload
- Tambahkan loading indicator pada tombol SIMPAN/UPDATE
- Tambahkan `wire:loading.attr="disabled"` agar tombol disabled saat proses upload/simpan
- Tambahkan display untuk error message dengan session `error`

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

## Cara Testing

### 1. Test Upload File PPTX
1. Login sebagai admin/pengurus
2. Masuk ke halaman Materi Kotbah (`/pengurus/materi_kotbah`)
3. Isi form:
   - Tanggal: Pilih tanggal
   - Judul Kotbah: Masukkan judul
   - File Kotbah: Upload file PPTX (maksimal 10MB)
4. Klik tombol **SIMPAN**
5. Akan muncul loading indicator "Menyimpan..."
6. Setelah selesai, akan muncul pesan sukses "Data Materi Kotbah berhasil disimpan."
7. Verifikasi file tersimpan di `storage/app/public/materi-kotbah/`

### 2. Test Upload File PDF
- Ulangi langkah di atas dengan file PDF

### 3. Test Upload File PPT
- Ulangi langkah di atas dengan file PPT

### 4. Test Validasi
- Coba upload file dengan ukuran lebih dari 10MB (harus muncul error)
- Coba upload file selain PDF/PPT/PPTX (harus muncul error)
- Coba simpan tanpa mengisi tanggal (harus muncul error)

### 5. Cek Log untuk Debugging
Jika ada masalah, cek file log:
```bash
tail -f storage/logs/laravel.log
```

Log akan menampilkan:
- Informasi file yang diupload (nama, mime type, size)
- Path file setelah disimpan
- ID data setelah disimpan ke database
- Error jika ada masalah

### 6. Verifikasi File di Storage
```bash
ls -la storage/app/public/materi-kotbah/
```

File harus tersimpan dengan format: `[random-hash].[ext]`

### 7. Verifikasi Data di Database
```sql
SELECT * FROM tbl_materi_kotbahs ORDER BY created_at DESC LIMIT 5;
```

Kolom `filename` harus berisi path relatif: `materi-kotbah/[filename]`

## File yang Diubah
1. `app/Livewire/MateriKotbah.php` - Component Livewire
2. `resources/views/livewire/materi-kotbah.blade.php` - View Blade
3. `config/livewire.php` - Konfigurasi Livewire (file baru)

## Catatan Penting
- Pastikan folder `storage/app/livewire-tmp` memiliki permission yang benar (775)
- Pastikan folder `storage/app/public/materi-kotbah` memiliki permission yang benar (775)
- Pastikan symbolic link `public/storage` sudah dibuat dengan `php artisan storage:link`
- File PPTX akan tersimpan di `storage/app/public/materi-kotbah/`
- File temporary Livewire akan otomatis dibersihkan setelah 24 jam
- Ukuran maksimal file adalah 10MB (dapat diubah di validasi dan config Livewire)

## Troubleshooting

### Jika file masih tidak tersimpan:
1. Cek permission folder:
   ```bash
   chmod -R 775 storage/app/public/materi-kotbah
   chmod -R 775 storage/app/livewire-tmp
   ```

2. Cek log error:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Cek konfigurasi PHP:
   ```bash
   php -i | grep -E "upload_max_filesize|post_max_size"
   ```

4. Clear semua cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   php artisan route:clear
   ```

5. Restart web server (jika menggunakan Apache/Nginx)

### Jika error "Maximum upload size exceeded":
- Update `config/livewire.php` bagian `temporary_file_upload.rules`
- Update validasi di `MateriKotbah.php` method `simpan()` dan `update()`
- Cek konfigurasi PHP `upload_max_filesize` dan `post_max_size`
