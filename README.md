# Panduan Setup Aplikasi Inventory

Aplikasi ini adalah sistem manajemen inventaris barang berbasis Laravel 11, Bootstrap 5, dan Tailwind/Vite. Ikuti panduan singkat di bawah ini untuk melakukan konfigurasi awal di komputer lokal Anda.

---

## Prasyarat Sistem
* **PHP >= 8.2**
* **Composer**
* **Node.js & NPM**
* **Laragon** (direkomendasikan) atau **XAMPP** dengan MySQL aktif

---

## Langkah Instalasi

### 1. Pindahkan Folder Proyek
Salin folder proyek ini ke direktori root server lokal Anda:
* Laragon: `C:\laragon\www\confused`
* XAMPP: `C:\xampp\htdocs\confused`

### 2. Install Dependensi PHP
Buka terminal di dalam folder proyek tersebut, lalu jalankan perintah:
```bash
composer install
```

### 3. Salin Konfigurasi Environment (`.env`)
Salin berkas `.env.example` menjadi `.env` di folder utama proyek:
```bash
cp .env.example .env
```
Buka file `.env` tersebut dan pastikan konfigurasi databasenya sesuai dengan MySQL lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=confused
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database Baru
Buat database kosong bernama **`confused`** melalui phpMyAdmin atau HeidiSQL di komputer lokal Anda.

### 5. Generate Security Key & Jalankan Migrasi
Jalankan rangkaian perintah berikut di terminal Anda untuk menginisialisasi aplikasi dan struktur tabel database beserta data awal (seeder):
```bash
php artisan key:generate
php artisan migrate --seed
```

### 6. Jalankan Dependensi Aset Frontend (Vite)
Install dependensi Javascript dan jalankan Vite dev server dengan perintah berikut:
```bash
npm install
npm run dev
```
*(Biarkan terminal ini tetap berjalan selama Anda membuka aplikasi).*

### 7. Jalankan Server Aplikasi
Buka terminal baru di folder yang sama, lalu jalankan perintah untuk memulai server:
```bash
php artisan serve
```
Akses aplikasi melalui browser di alamat: **`http://127.0.0.1:8000`**

*(Khusus pengguna Laragon, Anda bisa langsung mengakses aplikasi via virtual host: **`http://confused.test`** tanpa perlu menjalankan `php artisan serve`)*

---

## Akun Login Default
Gunakan akun bawaan di bawah ini untuk mencoba aplikasi:

* **Role Owner** (Akses Laporan & Manajemen User)
  * Username: `owner`
  * Password: `password`

* **Role Admin** (Akses Operasional & Master Barang)
  * Username: `admin`
  * Password: `password`

---

## Modul Utama Aplikasi
* **Dashboard:** Ringkasan tren transaksi pesanan keluar bulanan dan daftar transaksi terkini.
* **Master Data:** Manajemen barang, supplier, reseller, dan toko.
* **Persediaan & Stok Opname:** Monitoring batas aman stok (*Safety Stock*) otomatis dan penyesuaian selisih fisik barang.
* **Transaksi Masuk & Keluar:** Pencatatan pasokan masuk dari supplier, retur penjualan, serta pesanan keluar.
* **Laporan & Cash Flow:** Rangkuman performa keuangan, pengeluaran riil supplier, dan rekonsiliasi profit operasional.
