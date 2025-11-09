# Quick Fix: Directory NOT Found di cPanel

## 🔴 Problem
Ketika mengakses `test-upload-config.php`, muncul error:
```
📁 Directory Permissions
storage/app/chunks              ✗ Directory NOT Found
storage/app/public/materi-kotbah ✗ Directory NOT Found
storage/logs                     ✗ Directory NOT Found
```

---

## ✅ Solusi Cepat (3 Langkah)

### Langkah 1: Akses Script Auto-Create
Buka browser dan akses:
```
https://philadelphialifecenter.com/create-directories.php?key=gbi-plc-2024
```

### Langkah 2: Klik Tombol Create
Akan muncul halaman dengan tombol **"Create All Missing Directories"**.
Klik tombol tersebut dan tunggu proses selesai.

### Langkah 3: Verifikasi
Akses kembali:
```
https://philadelphialifecenter.com/test-upload-config.php
```

Semua directory seharusnya sudah **✓ Exists & Writable**.

---

## 📋 Manual Alternative (Jika Script Gagal)

### Via SSH:
```bash
cd /home/philadel/public_html
mkdir -p storage/app/chunks
mkdir -p storage/app/public/materi-kotbah
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
chmod -R 775 storage/
chmod -R 775 bootstrap/cache
```

### Via cPanel File Manager:
1. Login cPanel
2. Buka **File Manager**
3. Navigate ke `/home/philadel/public_html`
4. Klik **+ Folder** untuk membuat folder baru
5. Buat folder-folder berikut:
   ```
   storage/app/chunks
   storage/app/public/materi-kotbah
   storage/logs
   bootstrap/cache
   ```
6. Right-click setiap folder → **Change Permissions**
7. Set permission ke **755** atau **775**

---

## 🔐 Create Symbolic Link

Setelah folder dibuat, buat symbolic link untuk public storage:

### Via SSH:
```bash
cd /home/philadel/public_html
php artisan storage:link
```

### Via Script (create-symlink.php):
Buat file `public/create-symlink.php`:
```php
<?php
$target = dirname(__DIR__) . '/storage/app/public';
$link = __DIR__ . '/storage';

if (file_exists($link)) {
    echo "Symbolic link already exists!<br>";
} else {
    if (symlink($target, $link)) {
        echo "✓ Symbolic link created successfully!<br>";
        echo "Target: $target<br>";
        echo "Link: $link<br>";
    } else {
        echo "✗ Failed to create symbolic link.<br>";
        echo "Please run: php artisan storage:link<br>";
    }
}
?>
```

Akses: `https://philadelphialifecenter.com/create-symlink.php`

---

## ✅ Expected Result

Setelah selesai, halaman `test-upload-config.php` harus menampilkan:

```
📁 Directory Permissions
storage/app/chunks                      ✓ Exists & Writable (Permission: 0775)
storage/app/public/materi-kotbah        ✓ Exists & Writable (Permission: 0775)
storage/logs                            ✓ Exists & Writable (Permission: 0775)
bootstrap/cache                         ✓ Exists & Writable (Permission: 0775)
```

---

## 🧹 Cleanup (Setelah Selesai)

**PENTING:** Hapus file-file testing untuk keamanan:

Via cPanel File Manager:
```
public/test-upload-config.php
public/create-directories.php
public/clear-cache.php
public/create-symlink.php (jika dibuat)
```

Via SSH:
```bash
cd /home/philadel/public_html/public
rm test-upload-config.php
rm create-directories.php
rm clear-cache.php
rm create-symlink.php
```

---

## 🎯 Next Steps

Setelah semua directory sudah ada:
1. ✅ Test upload file kecil (<10MB)
2. ✅ Test upload file besar (>50MB) dengan chunk upload
3. ✅ Verifikasi file tersimpan di `storage/app/public/materi-kotbah/`
4. ✅ Hapus semua file testing

---

## 📞 Troubleshooting

### Jika masih gagal membuat directory:

**Cek permission parent folder:**
```bash
ls -la /home/philadel/public_html/storage/app/
```

Harus ada permission write (w):
```
drwxrwxr-x  (775) atau drwxr-xr-x (755)
```

Jika tidak, update permission:
```bash
chmod 775 /home/philadel/public_html/storage/app/
```

**Cek ownership:**
```bash
ls -la /home/philadel/public_html/storage/
```

Owner harus sesuai dengan user cPanel Anda:
```
drwxrwxr-x  philadel philadel
```

Jika tidak:
```bash
chown -R philadel:philadel /home/philadel/public_html/storage/
```

---

**Problem Solved? ✓**
Lanjut ke testing upload functionality!
