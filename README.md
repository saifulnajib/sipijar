# SIPIJAR - Sistem Informasi & Pengelolaan Infrastruktur Jaringan

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

Aplikasi manajemen dan audit infrastruktur jaringan listrik & penerangan jalan umum (PJU), monitoring telemetri, serta audit tagihan berbasis **Laravel 12**, **Inertia.js**, dan **Vue 3**.

---

## 📋 Daftar Isi
1. [Tech Stack](#-tech-stack)
2. [Fitur Utama](#-fitur-utama)
3. [Panduan Instalasi Lokal (Development)](#-panduan-instalasi-lokal-development)
4. [Panduan Deploy ke Server Plesk (Production)](#-panduan-deploy-ke-server-plesk-production)
   - [Prasyarat Server](#1-prasyarat-server)
   - [Metode 1: Deploy via Plesk Git Extension (Rekomendasi)](#2-metode-1-deploy-via-plesk-git-extension-rekomendasi)
   - [Metode 2: Deploy Manual via SSH / File Manager](#3-metode-2-deploy-manual-via-ssh--file-manager)
   - [Konfigurasi Environment (.env)](#4-konfigurasi-environment-env)
   - [Pengaturan Document Root & Web Server (Nginx / Apache)](#5-pengaturan-document-root--web-server)
   - [Pengaturan Izin Akses (Permissions)](#6-pengaturan-izin-akses-permissions)
   - [Pengaturan Task Scheduler (Cron Job)](#7-pengaturan-task-scheduler-cron-job)
   - [Troubleshooting Masalah Umum](#8-troubleshooting-masalah-umum)

---

## 🛠 Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vue 3 (Composition API), Inertia.js 2.x, TailwindCSS, Lucide Icons, Leaflet / Mapbox
- **Database**: PostgreSQL / MySQL
- **Build Tool**: Vite 6.x

---

## 🚀 Fitur Utama

- 📊 **Dashboard & Spreadsheet View**: Visualisasi data real-time, monitoring status trafo, gardu, dan titik lampu.
- 🗺️ **Interactive Geographic Map**: Pemetaan geografis aset tiang, gardu, dan ID Pelanggan (IDPEL).
- ⚡ **Telemetri & Monitoring Daya**: Pencatatan data beban daya, tegangan, dan anomali konsumsi listrik.
- 📑 **Audit Keuangan & Billing**: Pelacakan riwayat tagihan dan efisiensi konsumsi listrik PJU.
- 🎫 **Manajemen Tiket & Pengaduan**: Alur penanganan gangguan dan pemeliharaan lapangan.

---

## 💻 Panduan Instalasi Lokal (Development)

1. **Clone repository**:
   ```bash
   git clone https://github.com/saifulnajib/sipijar.git
   cd sipijar
   ```

2. **Install dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Salin file environment & generate App Key**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database di `.env` lalu jalankan migrasi**:
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan development server**:
   ```bash
   # Terminal 1: Laravel Backend
   php artisan serve

   # Terminal 2: Vite Frontend
   npm run dev
   ```

---

## 🌐 Panduan Deploy ke Server Plesk (Production)

Panduan lengkap untuk mempublikasikan SIPIJAR ke hosting/VPS yang menggunakan kontrol panel **Plesk Obsidian / Onyx**.

---

### 1. Prasyarat Server

Pastikan konfigurasi domain/subdomain di Plesk memenuhi spesifikasi berikut:

- **PHP Version**: `PHP 8.2` atau `PHP 8.3` (FPM application served by Apache / Nginx).
- **Ekstensi PHP Aktif**:
  `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pcre`, `pdo`, `pdo_mysql` / `pdo_pgsql`, `tokenizer`, `xml`, `zip`.
- **Node.js**: Versi `18.x` atau `20.x` LTS (jika build asset langsung di server).
- **Composer**: Versi `2.x`.
- **Database**: MySQL 8.0+ / MariaDB 10.5+ / PostgreSQL 14+.
- **SSL Certificate**: Let's Encrypt aktif di domain target.

---

### 2. Metode 1: Deploy via Plesk Git Extension (Rekomendasi)

Plesk memiliki ekstensi bawaan **Git** yang memungkinkan deployment otomatis setiap ada perubahan (push) ke repository GitHub.

#### A. Tambahkan Repository di Plesk
1. Masuk ke panel Plesk > pilih menu **Websites & Domains** > pilih domain Anda.
2. Klik ikon **Git**.
3. Pilih **Remote Git hosting like GitHub or Bitbucket**.
4. Masukkan URL repository:
   ```text
   https://github.com/saifulnajib/sipijar.git
   ```
   *(Jika repo bersifat Private, salin SSH public key yang disediakan Plesk dan daftarkan ke menu **Settings > Deploy Keys** di GitHub Anda).*
5. Tentukan branch: `main`.
6. Tentukan folder target: `/httpdocs` atau `/sipijar`.

#### B. Konfigurasi Additional Deployment Actions
Di menu Git Plesk, buka tab **Additional deployment actions**, lalu masukkan skrip otomatisasi berikut:

```bash
# Pindah ke direktori proyek
cd /var/www/vhosts/DOMAIN_ANDA.com/httpdocs

# 1. Install dependency composer production
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Build asset frontend (Vite & Inertia)
npm ci
npm run build

# 3. Jalankan migrasi database
php artisan migrate --force

# 4. Cache konfigurasi & route untuk kecepatan optimal
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Link storage publik
php artisan storage:link

# 6. Pastikan permission folder storage aman
chmod -R 775 storage bootstrap/cache
```
> **Catatan**: Ganti `DOMAIN_ANDA.com/httpdocs` sesuai dengan path virtual host Anda di server Plesk.

---

### 3. Metode 2: Deploy Manual via SSH / File Manager

Jika server Plesk Anda tidak menggunakan Git extension atau ingin mendeploy secara manual:

1. **Akses SSH ke server**:
   ```bash
   ssh user_domain@ip_server
   cd /var/www/vhosts/DOMAIN_ANDA.com/httpdocs
   ```

2. **Clone source code**:
   ```bash
   git clone https://github.com/saifulnajib/sipijar.git .
   ```

3. **Install Dependensi & Build Asset**:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   ```

4. **Siapkan Environment & Database**:
   ```bash
   cp .env.example .env
   # Edit konfigurasi .env sesuai kredensial server
   nano .env

   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   ```

5. **Optimasi Cache Laravel**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

### 4. Konfigurasi Environment (.env)

Buka file `.env` di server (bisa via SSH `nano .env` atau **Plesk File Manager**), lalu sesuaikan konfigurasi production:

```ini
APP_NAME=SIPIJAR
APP_ENV=production
APP_KEY=base64:GENERATE_DARI_ARTISAN_KEY_GENERATE
APP_DEBUG=false
APP_URL=https://sipijar.domainanda.com

LOG_CHANNEL=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Sesuaikan dengan Database yang dibuat di Plesk Database Menu
DB_CONNECTION=pgsql # atau mysql
DB_HOST=127.0.0.1
DB_PORT=5432        # 3306 untuk MySQL
DB_DATABASE=db_sipijar
DB_USERNAME=user_sipijar
DB_PASSWORD=password_rahasia_database

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

---

### 5. Pengaturan Document Root & Web Server

#### A. Ubah Document Root ke Folder `public`
> ⚠️ **SANGAT PENTING**: Jangan arahkan Document Root ke root folder proyek. Document Root **WAJIB** diarahkan ke folder `public`.

1. Di Plesk, buka **Websites & Domains** > **Hosting & DNS** > **Hosting Settings**.
2. Pada kolom **Document Root**, ubah dari:
   - ❌ `httpdocs`
   menjadi:
   - ✅ `httpdocs/public`
3. Klik **Apply** / **OK**.

#### B. Konfigurasi Nginx / Apache Directives
Agar URL routing Inertia.js / Vue SPA tidak menghasilkan `404 Not Found` saat halaman di-refresh:

1. Buka **Websites & Domains** > **Apache & Nginx Settings**.
2. Pada bagian **Additional nginx directives**, tambahkan aturan berikut:

```nginx
# Routing Laravel & Inertia.js
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

# Proteksi file sensitif
location ~ /\.(env|git|htaccess) {
    deny all;
    return 404;
}

# Caching file statis frontend (Vite build)
location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, no-transform";
}
```

---

### 6. Pengaturan Izin Akses (Permissions)

Web server (Apache/Nginx/PHP-FPM) membutuhkan izin tulis (*write permission*) pada folder `storage` dan `bootstrap/cache`.

Jalankan perintah berikut via SSH:
```bash
cd /var/www/vhosts/DOMAIN_ANDA.com/httpdocs

# Memberikan hak akses grup writable
chmod -R 775 storage bootstrap/cache

# Sesuaikan ownership pengguna domain plesk (contoh: userdomain:psacln)
chown -R $(whoami):psacln storage bootstrap/cache
```

Atau jika menggunakan **Plesk File Manager**:
1. Centang folder `storage` dan `bootstrap/cache`.
2. Klik **Change Permissions**.
3. Pastikan grup/user **Application pool group / Plesk user** memiliki centang **Read & Write**.

---

### 7. Pengaturan Task Scheduler (Cron Job)

Laravel memiliki fitur scheduled tasks untuk monitoring otomatis dan pembersihan data.

1. Buka panel Plesk > **Websites & Domains** > **Scheduled Tasks (Cron Jobs)**.
2. Klik **Add Task**.
3. Atur parameter:
   - **Task type**: `Run a command`
   - **Command**:
     ```bash
     /opt/plesk/php/8.2/bin/php /var/www/vhosts/DOMAIN_ANDA.com/httpdocs/artisan schedule:run >/dev/null 2>&1
     ```
     *(Sesuaikan versi PHP dan path domain Anda)*
   - **Schedule**: Pilih **Cron style** -> `* * * * *` (setiap menit).
4. Klik **Apply** / **OK**.

---

### 8. Troubleshooting Masalah Umum

| Gejala Masalah | Penyebab Utama | Solusi |
| :--- | :--- | :--- |
| **Halaman blank putih / 500 Server Error** | `.env` belum dibuat, App Key kosong, atau permission folder `storage/logs` terkunci | Jalankan `php artisan key:generate`, cek `storage/logs/laravel.log`, dan jalankan `chmod -R 775 storage`. |
| **404 Not Found saat klik menu / refresh** | Document root salah atau Nginx direct URL rewrite belum aktif | Pastikan Document Root adalah `httpdocs/public` dan tambahkan `try_files $uri $uri/ /index.php?$query_string;` di Nginx directive. |
| **Assets CSS/JS tidak muncul (Inertia Manifest Error)** | Asset Vite belum di-build untuk production | Jalankan `npm run build` di server atau build lokal lalu upload folder `public/build`. |
| **Database Connection Refused / Access Denied** | Kredensial di file `.env` tidak sesuai dengan database Plesk | Buka menu **Databases** di Plesk, cek username, password, dan port (MySQL: 3306, PostgreSQL: 5432). |
| **Error: Symlink() has been disabled for security reasons** | Fungsi `symlink` dinonaktifkan di `php.ini` hosting | Buka **PHP Settings** di Plesk, hapus `symlink` dari daftar `disable_functions`, lalu jalankan `php artisan storage:link`. |

---

## 📄 Lisensi

Proyek ini dikembangkan khusus untuk pengelolaan infrastruktur dan bersifat privat / internal.
