# Sistem Informasi Manajemen Data Mahasiswa

Aplikasi tugas PHP Native + MySQL/MariaDB dengan fitur:
- Login dan logout menggunakan PHP Session.
- Dashboard dan jumlah data mahasiswa.
- CRUD: tambah, tampil, cari, edit, dan hapus data mahasiswa.
- Validasi form dengan `if-else`, `filter_var()`, dan pengecekan pilihan.
- Sanitasi output menggunakan `htmlspecialchars()`.
- Prepared statement PDO untuk operasi database.
- Pilihan bahasa Indonesia dan Inggris yang disimpan dalam Session.
- Token CSRF pada form yang mengubah data.

## 1. Persiapan
1. Install dan jalankan **Apache** serta **MySQL** melalui XAMPP.
2. Ekstrak folder `sistem_informasi_mahasiswa` ke `C:\xampp\htdocs\`.
3. Buka phpMyAdmin melalui `http://localhost/phpmyadmin`.
4. Pilih tab **Import**, lalu impor file `database.sql`.
   - Alternatif: buka tab SQL, lalu salin dan jalankan isi `database.sql`.

## 2. Konfigurasi database
Buka `config/database.php`. Pengaturan default XAMPP:
- Host: `localhost`
- Database: `db_mahasiswa`
- Username: `root`
- Password: kosong

Sesuaikan `$dbuser` dan `$dbpass` jika konfigurasi MySQL Anda berbeda.

## 3. Membuat akun administrator
1. Buka `http://localhost/sistem_informasi_mahasiswa/setup_admin.php`.
2. Jika berhasil, akun awal adalah:
   - Username: `admin`
   - Password: `admin123`
   - `setup_admin.php` juga otomatis menambahkan kolom yang kurang jika Anda memakai database lama.
3. **Hapus file `setup_admin.php` setelah akun dibuat** demi keamanan.

## 4. Menjalankan aplikasi
Buka `http://localhost/sistem_informasi_mahasiswa/`, kemudian login dengan akun admin.

## 5. Struktur folder
```text
sistem_informasi_mahasiswa/
├── assets/
│   └── style.css
├── config/
│   └── database.php
├── includes/
│   ├── footer.php
│   ├── form_mahasiswa.php
│   ├── functions.php
│   └── header.php
├── database.sql
├── index.php
├── login.php
├── logout.php
├── dashboard.php
├── mahasiswa.php
├── tambah.php
├── edit.php
├── hapus.php
├── password.php
├── set_language.php
├── setup_admin.php
└── README.md
```

## 6. Pengujian fitur
1. Coba login dengan username/password yang benar dan salah.
2. Tambahkan mahasiswa dengan seluruh kolom terisi.
3. Coba kosongkan salah satu kolom untuk menguji validasi.
4. Masukkan email yang tidak valid.
5. Tambahkan NIM yang sama untuk menguji validasi data unik.
6. Edit data mahasiswa.
7. Cari data berdasarkan nama, NIM, atau program studi.
8. Hapus data dan pastikan data hilang dari tabel.
9. Ganti bahasa ID/EN, lalu pindah halaman untuk memastikan bahasa tetap tersimpan.
10. Logout, lalu coba buka `dashboard.php` secara langsung. Aplikasi harus mengarahkan kembali ke login.

## 7. Catatan
- Aplikasi dibuat dengan PHP Native dan PDO, tanpa framework.
- Gunakan PHP 7.4 atau lebih baru (disarankan PHP 8.x).
- Untuk penggunaan nyata, ganti password awal, gunakan HTTPS, dan hapus file setup setelah digunakan.
