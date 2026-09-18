# Modul 2: Monitoring Telemetri & GIS

Modul ini bertanggung jawab untuk visualisasi data secara realtime di *Dashboard* dan menampilkan lokasi persis dari setiap perangkat melalui *Geographic Information System* (GIS).

## Fitur Utama

1. **Peta Interaktif (GIS Map)**
   - Menggunakan Mapbox atau Leaflet.js.
   - Penanda (*Marker*) untuk Tiang dan Gardu dengan *color-coding*: Hijau (Nyala/Aman), Kuning (Abonemen), Merah berkedip (Gangguan/Padam).
   - *Layer Toggles*: Menyalakan/mematikan visibilitas heatmap daya, kabel jaringan (jalur), dan gardu.
   - Jendela Informasi (*Popup Tooltip*) ketika penanda diklik (menampilkan daya, ID, alamat).

2. **Log Telemetri Real-Time**
   - Menarik dan menyimpan log sensor dari panel IoT.
   - *Data points* yang diamati: Tegangan (V), Arus (A), Daya aktif (kW).
   - Log disajikan dalam tabel dengan pembaruan dinamis.

3. **Sistem Deteksi Anomali (Alerts)**
   - Algoritma pemantauan untuk mendeteksi *MCB Trip*, Tegangan Turun (Drop Voltage <190V), atau lampu tidak menarik arus saat malam hari.
   - *Push notifications* atau highlight peringatan di layar.

## Target UI/UX
- Integrasi peta *full-screen* yang responsif.
- Peta mendukung perbesaran (Zoom in/out) dan pelacakan lokasi (*my location*).

## Prioritas Pengembangan
- **Menengah-Tinggi**. (Fokus utama inovasi pada *Smart City*).
