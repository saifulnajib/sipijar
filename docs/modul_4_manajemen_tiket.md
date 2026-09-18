# Modul 4: Manajemen Tiket & Penugasan (Work Order)

Modul operasional lapangan bagi Tim Reaksi Cepat (TRC) untuk merespon dan mendokumentasikan pemeliharaan PJU.

## Fitur Utama

1. **Laporan & Tiket Gangguan (Ticketing System)**
   - Otomatis membuat tiket (*Auto-Ticket*) dari alarm anomali telemetri.
   - Input laporan manual dari warga (Nomor Tiang/Alamat, Foto, Deskripsi).
   - Kategorisasi tiket: Kritis (MCB Trip), Sedang (Lampu Padam Sebagian), Rendah (Tiang Miring).

2. **Penugasan (Dispatch & Disposisi)**
   - *Assigment* tiket ke regu piket / teknisi tertentu.
   - Status Pelacakan: *Open*, *Dispatched*, *In Progress*, *Resolved*, *Closed*.

3. **Catatan Perbaikan & SLA**
   - Teknisi dapat mengunggah bukti foto setelah perbaikan.
   - Kalkulasi *Service Level Agreement* (SLA): durasi antara laporan dibuat hingga diselesaikan.

## Target UI/UX
- Tampilan papan kanban (Todo, Doing, Done) untuk memudahkan pemantauan kerja lapangan.
- Kompatibel dengan *mobile browser* karena Teknisi akan mengaksesnya dari lapangan.

## Prioritas Pengembangan
- **Tinggi**. (Krusial untuk memelihara standar *uptime* 98% PJU).
