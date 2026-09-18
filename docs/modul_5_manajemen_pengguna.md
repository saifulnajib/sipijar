# Modul 5: Pengaturan & Manajemen Pengguna (RBAC)

Modul infrastruktur sistem untuk mengamankan data dan membatasi akses antar divisi di dalam Dinas Perhubungan.

## Fitur Utama

1. **Manajemen Pengguna (User Management)**
   - CRUD data pengguna (Nama, Email, Password).
   - Fitur Reset Password dan penonaktifan akun.

2. **Role-Based Access Control (RBAC)**
   - **Super Admin / Kepala Dinas:** *Read-only* menyeluruh, akses ke laporan audit penghematan, dashboard eksekutif.
   - **Operator Command Center:** Akses penuh manajemen tiket, monitoring peta, dan disposisi.
   - **Tim Teknisi TRC:** Akses *mobile-view* untuk memperbarui status tiket.
   - **Auditor Eksternal (Opsional):** Akses laporan saja.

3. **Pengaturan Global Sistem (Settings)**
   - Pengaturan batas peringatan tegangan drop (misal: set < 190V = Alert).
   - Sinkronisasi API (API Keys untuk layanan eksternal).

## Target UI/UX
- Antarmuka simpel untuk manajemen hak akses (tabel matriks *checkbox*).

## Prioritas Pengembangan
- **Menengah**. (Perlu diimplementasikan sebelum *go-live* untuk mengamankan data sensitif).
