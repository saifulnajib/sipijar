# Modul 1: Master Data Aset PJU

Modul ini berfungsi sebagai tulang punggung (backbone) data aplikasi SIPIJAR, menyimpan dan mengelola semua aset yang terkait dengan Penerangan Jalan Umum.

## Fitur Utama

1. **Manajemen IDPEL PLN**
   - Menambahkan, mengedit, dan menghapus (CRUD) Data IDPEL.
   - Status IDPEL (Meterisasi Digital vs Abonemen Flat).
   - Penempatan letak administratif (Kecamatan / Kelurahan).
   - Target migrasi Abonemen ke Meterisasi.

2. **Manajemen Gardu & Panel (Substation)**
   - CRUD panel distribusi atau gardu induk per IDPEL.
   - Limitasi / Kapasitas daya kontrak maksimal (VA).
   - Pengaturan toleransi MCB / Tegangan untuk peringatan anomali.

3. **Manajemen Tiang & Lampu PJU**
   - Inventarisasi titik tiang beserta koordinat GPS (Lat, Lng) presisi tinggi.
   - Asosiasi satu tiang dengan Gardu Induk (Substation) tertentu.
   - Spesifikasi Lampu: Smart LED (60W-90W), Konvensional (120W), atau Son-T (250W).

## Target UI/UX
- Tabel *DataTables* atau komponen Inertia/Vue dengan fitur pencarian dan penyaringan (berdasarkan kecamatan dan status meterisasi).
- *Form validation* ketat untuk format IDPEL (biasanya 12 digit).

## Prioritas Pengembangan
- **Tinggi**. (Harus dikerjakan terlebih dahulu karena modul lain akan bergantung padanya).
