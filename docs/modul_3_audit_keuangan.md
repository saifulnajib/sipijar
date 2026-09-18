# Modul 3: Audit Keuangan & Rekonsiliasi Tagihan

Modul untuk keperluan administratif Dinas Perhubungan, terutama memantau pengeluaran anggaran, rekonsiliasi data dengan PLN, dan penyajian bukti penghematan pasca pemasangan kWh meter.

## Fitur Utama

1. **Manajemen Tagihan PLN (Billing)**
   - Input manual, import file CSV/Excel dari PLN, atau integrasi API billing UID Riau & Kepri.
   - Pelacakan tagihan berdasarkan IDPEL dan Bulan/Tahun.
   - Rekam pemakaian (kWh usage) dan Rupiah tagihan aktual.

2. **Analitik Penghematan (Efficiency Audit)**
   - Perbandingan (Divergensi) tagihan baseline (saat masih *Abonemen Flat*) vs setelah terpasang *Smart Meter*.
   - Kalkulasi otomatis total efisiensi kas daerah secara bulanan dan tahunan.
   - Visualisasi grafis (bar chart/line chart) penghematan.

3. **Eksport Laporan (Reporting)**
   - Pembuatan Laporan Rekonsiliasi bulanan.
   - Eksport dokumen ke PDF atau Spreadsheet.
   - Dokumen ini akan diformat mengikuti standar LHP BPK.

## Target UI/UX
- Halaman *luxury* khusus laporan dengan grafik yang menarik (Charts.js atau ApexCharts).
- Tabel pelaporan yang dapat difilter berdasarkan tahun anggaran.

## Prioritas Pengembangan
- **Menengah**. (Dibutuhkan terutama pada saat tutup buku bulanan/tahunan).
