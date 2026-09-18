<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    kpi: Object,
    idpels: Object
});

const selectedDistrict = ref('all');
const selectedYear = ref('2024');

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
};

const formatNumber = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};
</script>

<template>
    <Head title="SIPIJAR Command Center - Dishub Tanjungpinang" />

    <DashboardLayout>
        <main class="w-full pt-20 pb-12 bg-transparent px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
            
            <!-- 1. TOP HEADER & FILTER BAR -->
            <section class="relative overflow-hidden bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 lg:p-7 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 transition-all">
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-gradient-to-br from-rose-100/40 to-transparent rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
                    <div class="flex flex-col gap-2 max-w-3xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100/80 dark:bg-rose-950/60 text-primary dark:text-rose-300 font-caption text-[11px] font-extrabold uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                Dishub Kota Tanjungpinang • Command Center
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-caption text-[11px] font-medium border border-gray-200/60 dark:border-gray-700/60">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                                </span>
                                Realtime IoT Telemetry
                            </span>
                        </div>
                        <h1 class="font-headline-xl text-2xl sm:text-3xl font-extrabold text-gray-950 dark:text-white tracking-tight leading-tight">
                            Pusat Kendali &amp; Monitoring PJU Terukur Kota Tanjungpinang
                        </h1>
                        <p class="font-body-md text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            Sistem Informasi dan Pengendalian Jaringan Penerangan Jalan Umum Terukur (SI-PIJAR) Terintegrasi PLN UID Riau &amp; Kepri
                        </p>
                    </div>

                    <!-- Header Action Buttons -->
                    <div class="flex flex-wrap items-center gap-3">
                        <button
                            class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-rose-50/80 dark:hover:bg-rose-950/40 text-gray-800 dark:text-gray-200 px-4 py-2.5 rounded-xl font-label-md text-xs font-bold transition-all duration-200 border border-rose-200/70 dark:border-rose-900/50 shadow-xs hover:shadow hover:-translate-y-0.5 active:translate-y-0"
                            type="button"
                        >
                            <span class="material-symbols-outlined text-[18px] text-secondary">sync</span>
                            <span>Sinkronisasi PLN UID</span>
                        </button>
                        <button
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-primary via-secondary to-primary-container hover:from-primary-container hover:to-primary text-white px-4 py-2.5 rounded-xl font-label-md text-xs font-bold transition-all duration-200 shadow-md shadow-primary/20 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                            type="button"
                        >
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            <span>Export Laporan</span>
                        </button>
                    </div>
                </div>

                <!-- Quick Control Filter Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-6 mt-6 border-t border-rose-100/50 dark:border-rose-950/40">
                    <!-- Filter Periode -->
                    <div class="flex flex-col gap-1.5 bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 hover:border-primary/30 transition-all">
                        <label class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5" for="filter-periode">
                            <span class="material-symbols-outlined text-secondary text-[16px]">calendar_today</span>
                            Periode Anggaran
                        </label>
                        <select v-model="selectedYear" class="w-full bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0" id="filter-periode">
                            <option value="2025">Tahun Anggaran 2025 (Berjalan)</option>
                            <option value="2024">Tahun Anggaran 2024 (Realisasi Penuh)</option>
                            <option value="2023">Tahun Anggaran 2023 (Baseline)</option>
                        </select>
                    </div>

                    <!-- Filter Kecamatan -->
                    <div class="flex flex-col gap-1.5 bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 hover:border-primary/30 transition-all">
                        <label class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5" for="filter-kecamatan">
                            <span class="material-symbols-outlined text-secondary text-[16px]">domain</span>
                            Wilayah Kecamatan
                        </label>
                        <select v-model="selectedDistrict" class="w-full bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0" id="filter-kecamatan">
                            <option value="all">Semua Kecamatan (Kota Tanjungpinang)</option>
                            <option value="bukit-bestari">Kecamatan Bukit Bestari</option>
                            <option value="tpi-timur">Kecamatan Tanjungpinang Timur</option>
                            <option value="tpi-kota">Kecamatan Tanjungpinang Kota</option>
                            <option value="tpi-barat">Kecamatan Tanjungpinang Barat</option>
                        </select>
                    </div>

                    <!-- Filter Status IDPEL -->
                    <div class="flex flex-col gap-1.5 bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 hover:border-primary/30 transition-all">
                        <label class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5" for="filter-status">
                            <span class="material-symbols-outlined text-secondary text-[16px]">electric_meter</span>
                            Status Meterisasi Gardu
                        </label>
                        <select class="w-full bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0" id="filter-status">
                            <option value="all">Semua Status (1.482 IDPEL)</option>
                            <option value="terukur">Sudah Meterisasi kWh (78,0%)</option>
                            <option value="abonemen">Non-Meter / Flat Abonemen (22,0%)</option>
                        </select>
                    </div>

                    <!-- Filter Tipe Beban / Jaringan -->
                    <div class="flex flex-col gap-1.5 bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 hover:border-primary/30 transition-all">
                        <label class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5" for="filter-operasional">
                            <span class="material-symbols-outlined text-secondary text-[16px]">lightbulb</span>
                            Status Operasional PJU
                        </label>
                        <select class="w-full bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0" id="filter-operasional">
                            <option value="all">Seluruh Jaringan Normal (98,2%)</option>
                            <option value="gangguan">Dalam Perbaikan / Padam (16 Titik)</option>
                            <option value="smart-timer">Terjadwal Smart Dimming</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- 2. KEY KPI METRIC CARDS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Card 1: Total IDPEL -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-rose-100/50 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total IDPEL PLN</span>
                            <span class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-secondary flex items-center justify-center border border-rose-100/60 dark:border-rose-900/40">
                                <span class="material-symbols-outlined text-[20px]">hub</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-gray-950 dark:text-white font-extrabold tracking-tight">
                                {{ formatNumber(kpi.total_idpel) }}
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-caption text-[11px] font-bold border border-emerald-200/50">
                                +24 Baru
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <span class="font-caption text-[11px] text-gray-500 dark:text-gray-400">Tersebar di 4 Kecamatan • 18 Kelurahan</span>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-primary to-secondary rounded-full" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Jumlah Titik Lampu -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-amber-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah Titik Lampu</span>
                            <span class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 flex items-center justify-center border border-amber-100/60 dark:border-amber-900/40">
                                <span class="material-symbols-outlined text-[20px]">wb_incandescent</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-gray-950 dark:text-white font-extrabold tracking-tight">
                                {{ formatNumber(kpi.total_lamps) }}
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/40 text-primary dark:text-rose-300 font-caption text-[11px] font-bold border border-rose-200/50">
                                98,2% Nyala
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between font-caption text-[11px] text-gray-500 dark:text-gray-400">
                            <span>Smart LED: 68,5%</span>
                            <span>Son-T: 9,7%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                            <div class="h-full bg-primary rounded-l-full" style="width: 68.5%;"></div>
                            <div class="h-full bg-secondary" style="width: 21.8%;"></div>
                            <div class="h-full bg-amber-400 rounded-r-full" style="width: 9.7%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: IDPEL Sudah Meterisasi -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-emerald-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">IDPEL Meterisasi</span>
                            <span class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center border border-emerald-100/60 dark:border-emerald-900/40">
                                <span class="material-symbols-outlined text-[20px]">electric_meter</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-primary dark:text-rose-400 font-extrabold tracking-tight">
                                {{ formatNumber(kpi.meterisasi_count) }}
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-100/80 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-caption text-[11px] font-bold">
                                {{ kpi.meterisasi_percentage }}% Terukur
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between font-caption text-[11px] text-gray-500 dark:text-gray-400">
                            <span>Target TA 2025: 100%</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400">Optimal Terukur</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full" :style="`width: ${kpi.meterisasi_percentage}%`"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: IDPEL Belum Meterisasi -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-rose-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Abonemen Flat</span>
                            <span class="w-9 h-9 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-600 flex items-center justify-center border border-gray-200/60 dark:border-gray-700/60">
                                <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-gray-950 dark:text-white font-extrabold tracking-tight">
                                {{ formatNumber(kpi.abonemen_count) }}
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-caption text-[11px] font-bold border border-amber-200/50">
                                Target Migrasi
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between font-caption text-[11px] text-gray-500 dark:text-gray-400">
                            <span>Sisa {{ kpi.abonemen_percentage }}% Kontrak Daya</span>
                            <span class="text-secondary font-medium">Beban Flat</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-500 to-rose-500 rounded-full" :style="`width: ${kpi.abonemen_percentage}%`"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. SPECIAL SECTION: ANALISIS EFISIENSI & PENGHEMATAN ANGGARAN (HERO LUXURY) -->
            <section class="bg-gradient-to-br from-[#4c0f17] via-[#7a1c28] to-[#36080e] text-white rounded-3xl p-6 sm:p-8 lg:p-9 shadow-2xl border border-rose-500/20 relative overflow-hidden">
                <!-- Radial Luminous Accents -->
                <div class="absolute -right-24 -top-24 w-96 h-96 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-secondary/25 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col gap-8">
                    <!-- Section Headline & Big Pill -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex flex-col gap-2 max-w-3xl">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/80 text-white font-caption text-xs font-extrabold uppercase tracking-wider backdrop-blur-sm border border-white/10">
                                    <span class="material-symbols-outlined text-[14px]">savings</span>
                                    Audit Efisiensi Belanja Energi
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-rose-100 font-caption text-xs border border-white/10">
                                    Audit BPK &amp; Rekonsiliasi PLN Sesuai Permendagri
                                </span>
                            </div>
                            <h2 class="font-headline-lg text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-snug">
                                Optimalisasi Belanja Rekening Listrik PJU Pemko Tanjungpinang Pasca Meterisasi Berkelanjutan
                            </h2>
                            <p class="font-body-sm text-xs sm:text-sm text-rose-200/90 leading-relaxed">
                                Perbandingan analitik riil antara skema pembayaran daya tak terukur (abonemen flat) dengan instalasi smart kWh meter terukur pada 1.156 IDPEL aktif.
                            </p>
                        </div>

                        <!-- Big Highlight Pill -->
                        <div class="bg-white/10 backdrop-blur-xl p-6 rounded-2xl flex flex-col items-start lg:items-end justify-center border border-white/15 shadow-inner">
                            <span class="font-caption text-xs text-rose-200 uppercase font-bold tracking-wider">Total Efisiensi Anggaran Kas Daerah</span>
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-rose-200 font-black tracking-tight mt-1">
                                Rp 7,84 Miliar <span class="text-base sm:text-lg text-white/90 font-normal font-sans">/ Tahun</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 text-emerald-300 font-label-md text-xs font-extrabold mt-2 bg-emerald-950/40 px-3 py-1 rounded-full border border-emerald-500/30">
                                <span class="material-symbols-outlined text-[16px]">trending_down</span>
                                Efisiensi 41,6% dari Pagu Semula
                            </span>
                        </div>
                    </div>

                    <!-- Financial Metrics Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
                        <!-- Sebelum Meterisasi -->
                        <div class="bg-black/20 backdrop-blur-md p-5 rounded-2xl flex flex-col justify-between border border-white/10 hover:border-white/20 transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-caption text-xs text-rose-200/80 uppercase font-bold tracking-wider">Biaya Sebelum Meterisasi (Flat)</span>
                                <span class="material-symbols-outlined text-rose-300 text-[20px]">receipt_long</span>
                            </div>
                            <div class="font-telemetry-data text-2xl font-extrabold text-white">
                                Rp 18,85 <span class="font-telemetry-unit text-sm font-normal text-rose-200">Miliar / Thn</span>
                            </div>
                            <div class="font-caption text-[11px] text-rose-200/80 mt-2 pt-2 border-t border-white/10">
                                Rata-rata tagihan PLN: <strong class="text-white font-telemetry-unit">Rp 1,57 M / Bulan</strong> (Kapasitas sambungan 24 jam)
                            </div>
                        </div>

                        <!-- Setelah Meterisasi -->
                        <div class="bg-black/20 backdrop-blur-md p-5 rounded-2xl flex flex-col justify-between border border-white/10 hover:border-white/20 transition-all">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-caption text-xs text-emerald-300 uppercase font-bold tracking-wider">Biaya Pasca Meterisasi Terukur</span>
                                <span class="material-symbols-outlined text-emerald-400 text-[20px]">verified</span>
                            </div>
                            <div class="font-telemetry-data text-2xl font-extrabold text-emerald-300">
                                Rp 11,01 <span class="font-telemetry-unit text-sm font-normal text-emerald-200">Miliar / Thn</span>
                            </div>
                            <div class="font-caption text-[11px] text-rose-200/80 mt-2 pt-2 border-t border-white/10">
                                Rata-rata tagihan PLN: <strong class="text-white font-telemetry-unit">Rp 917,5 Juta / Bulan</strong> (Sensor riil malam 12 jam)
                            </div>
                        </div>

                        <!-- Penghematan Rata-rata Bulanan -->
                        <div class="bg-gradient-to-br from-rose-900/50 to-primary-container/60 backdrop-blur-md p-5 rounded-2xl flex flex-col justify-between border border-rose-400/30 hover:border-rose-400/50 transition-all shadow-md">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-caption text-xs text-rose-200 uppercase font-bold tracking-wider">Penghematan Bulanan Rata-Rata</span>
                                <span class="material-symbols-outlined text-rose-200 text-[20px]">account_balance_wallet</span>
                            </div>
                            <div class="font-telemetry-data text-2xl font-extrabold text-rose-200">
                                Hemat Rp 653,5 <span class="font-telemetry-unit text-sm font-normal text-white">Juta / Bln</span>
                            </div>
                            <div class="font-caption text-[11px] text-rose-100 mt-2 pt-2 border-t border-white/10">
                                Dialihkan untuk pemeliharaan armatur LED &amp; 350 titik baru pesisir.
                            </div>
                        </div>
                    </div>

                    <!-- Comparison Chart: Jan to Dec -->
                    <div class="bg-black/25 backdrop-blur-md p-6 rounded-2xl flex flex-col gap-4 border border-white/10">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="flex flex-col">
                                <span class="font-label-lg text-sm font-bold text-white">Divergensi Beban Anggaran Bulanan (Januari – Desember 2024)</span>
                                <span class="font-caption text-xs text-rose-200/70">Satuan: Miliar Rupiah (IDR). Terhubung otomatis dengan billing PLN UID Riau &amp; Kepri</span>
                            </div>
                            <div class="flex items-center gap-4 text-xs">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-md bg-rose-300/80 border border-white/20"></span>
                                    <span class="text-rose-200 font-medium">Sebelum (Rp 1,57 M)</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-md bg-emerald-400 border border-white/20"></span>
                                    <span class="text-rose-200 font-medium">Setelah (Rp 0,91 M)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Inline SVG Visualization for Month-by-Month divergence -->
                        <div class="w-full h-44 flex items-end pt-2">
                            <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 1000 160">
                                <defs>
                                    <linearGradient id="savingsGradient" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#f43f5e" stop-opacity="0.35"/>
                                        <stop offset="100%" stop-color="#10b981" stop-opacity="0.05"/>
                                    </linearGradient>
                                </defs>
                                <!-- Grid Lines -->
                                <line stroke="rgba(255,255,255,0.08)" stroke-dasharray="4" x1="0" x2="1000" y1="20" y2="20"></line>
                                <line stroke="rgba(255,255,255,0.08)" stroke-dasharray="4" x1="0" x2="1000" y1="70" y2="70"></line>
                                <line stroke="rgba(255,255,255,0.08)" stroke-dasharray="4" x1="0" x2="1000" y1="120" y2="120"></line>
                                
                                <!-- Shaded Savings Area Between Before & After -->
                                <polygon fill="url(#savingsGradient)" points="40,30 120,30 200,30 280,30 360,30 440,30 520,30 600,30 680,30 760,30 840,30 920,30 920,118 840,115 760,112 680,108 600,102 520,100 440,96 360,98 280,105 200,108 120,112 40,115"></polygon>
                                
                                <!-- Before Meterization Line (Flat ~1.57 M) -->
                                <polyline fill="none" points="40,30 120,30 200,30 280,30 360,30 440,30 520,30 600,30 680,30 760,30 840,30 920,30" stroke="#fb7185" stroke-dasharray="6,4" stroke-width="2.5"></polyline>
                                
                                <!-- After Meterization Line (Measured ~0.91 M, declining slightly) -->
                                <polyline fill="none" points="40,115 120,112 200,108 280,105 360,98 440,96 520,100 600,102 680,108 760,112 840,115 920,118" stroke="#34d399" stroke-width="3.5"></polyline>
                                
                                <!-- Data Dots for After Meterization -->
                                <circle cx="40" cy="115" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="120" cy="112" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="200" cy="108" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="280" cy="105" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="360" cy="98" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="440" cy="96" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="520" cy="100" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="600" cy="102" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="680" cy="108" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="760" cy="112" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="840" cy="115" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                                <circle cx="920" cy="118" fill="#34d399" r="5" stroke="#064e3b" stroke-width="2"></circle>
                            </svg>
                        </div>
                        
                        <!-- Month Labels -->
                        <div class="grid grid-cols-12 text-center font-caption text-xs text-rose-200/80 px-2 font-semibold">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
                            <span>Jul</span><span>Agu</span><span>Sep</span><span>Okt</span><span>Nov</span><span>Des</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. SEBARAN PETA TITIK LAMPU PJU KOTA TANJUNGPINANG (Rich GIS Map Container) -->
            <section class="flex flex-col gap-4 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 lg:p-7 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-secondary flex items-center justify-center border border-rose-100/60">
                                <span class="material-symbols-outlined text-[20px]">map</span>
                            </span>
                            <h2 class="font-headline-lg text-xl sm:text-2xl font-extrabold text-gray-950 dark:text-white tracking-tight">
                                Peta Sebaran Titik Lampu &amp; Panel Gardu PJU Terukur
                            </h2>
                        </div>
                        <span class="font-body-sm text-xs text-gray-500 dark:text-gray-400 pl-10">
                            Pusat Koordinat Pesisir Pulau Bintan: Selat Riau, Jembatan Dompak, Ring Road KM 8 – Bandara RHF
                        </span>
                    </div>

                    <!-- Map Legend -->
                    <div class="flex flex-wrap items-center gap-3 bg-rose-50/50 dark:bg-rose-950/20 p-2.5 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 text-xs">
                        <div class="flex items-center gap-2 px-2 py-1 bg-white dark:bg-gray-800 rounded-xl shadow-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">Meterisasi (Normal)</span>
                        </div>
                        <div class="flex items-center gap-2 px-2 py-1 bg-white dark:bg-gray-800 rounded-xl shadow-xs">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">Non-Meter (Abonemen)</span>
                        </div>
                        <div class="flex items-center gap-2 px-2 py-1 bg-rose-100/70 dark:bg-rose-950/60 rounded-xl">
                            <span class="w-2.5 h-2.5 rounded-full bg-primary animate-ping"></span>
                            <span class="text-primary dark:text-rose-300 font-extrabold">Lampu Padam (16 Titik)</span>
                        </div>
                    </div>
                </div>

                <!-- Map Canvas Wrapper -->
                <div class="relative w-full h-[520px] rounded-3xl overflow-hidden bg-gray-950 shadow-inner border border-rose-900/20">
                    <!-- GIS Map Background Representation -->
                    <div class="w-full h-full bg-cover bg-center filter brightness-90 contrast-110" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAXF8vI7IP-UEZ0e8u9bzz_obaw7ztInlFRpoR2R1tq3S92dHbZ32PEEK7Cqqkg4N4OonyY1Aq_GM-KfD8PVbVHE3deT9ikdZyBuUbBKRUb9L5Y-XwO9Q8yreB1raJENEFoiLpxkTGpKKe4DHgf7Nqqrk6u5yC3xkvv3fMmmvy9TxqQ5LQzvIJ3_5RCesWCB_h1UNqRIs7_ThH5FtyrhmUeWSufbc6UEW1IJ5U9UtVJbeoL1N4lVn2O')"></div>
                    
                    <!-- Stylized Vector Overlays mimicking live electrical lines & coastal layout -->
                    <div class="absolute inset-0 pointer-events-none">
                        <svg class="w-full h-full" fill="none" viewBox="0 0 1200 650">
                            <!-- Main Grid Routes: Tepi Laut to Dompak & KM Artery -->
                            <path d="M 220 280 Q 320 240 450 310 T 680 340 T 890 310 T 1080 260" opacity="0.8" stroke="#fb7185" stroke-dasharray="6,3" stroke-linecap="round" stroke-width="3"></path>
                            <path d="M 450 310 L 480 480 L 590 560 L 710 540" opacity="0.85" stroke="#34d399" stroke-linecap="round" stroke-width="3"></path>
                            <path d="M 680 340 L 760 210 L 920 180" opacity="0.8" stroke="#fbbf24" stroke-linecap="round" stroke-width="2.5"></path>
                            <path d="M 220 280 L 260 410 L 390 440" opacity="0.75" stroke="#f43f5e" stroke-width="2.5"></path>
                            
                            <!-- Zone Indicators -->
                            <text fill="#ffffff" font-family="Plus Jakarta Sans" font-size="12" font-weight="800" letter-spacing="1" x="180" y="240">TEPI LAUT &amp; JL. POS</text>
                            <text fill="#ffffff" font-family="Plus Jakarta Sans" font-size="12" font-weight="800" letter-spacing="1" x="590" y="585">KOMPLEKS PEMPROV DOMPAK</text>
                            <text fill="#ffffff" font-family="Plus Jakarta Sans" font-size="12" font-weight="800" letter-spacing="1" x="820" y="160">BANDARA RHF &amp; KM 14</text>
                            <text fill="#ffffff" font-family="Plus Jakarta Sans" font-size="12" font-weight="800" letter-spacing="1" x="640" y="325">JL. BASUKI RAHMAT / BUKIT BESTARI</text>
                            
                            <!-- Nodes: Active Burgundy / Ruby Nodes -->
                            <circle class="animate-pulse" cx="220" cy="280" fill="#34d399" r="6" stroke="#ffffff" stroke-width="2"></circle>
                            <circle cx="280" cy="260" fill="#34d399" r="5" stroke="#ffffff" stroke-width="1.5"></circle>
                            <circle cx="340" cy="275" fill="#34d399" r="5" stroke="#ffffff" stroke-width="1.5"></circle>
                            <circle cx="450" cy="310" fill="#34d399" r="7" stroke="#ffffff" stroke-width="2"></circle>
                            <circle cx="480" cy="480" fill="#34d399" r="6" stroke="#ffffff" stroke-width="2"></circle>
                            <circle cx="590" cy="560" fill="#34d399" r="7" stroke="#ffffff" stroke-width="2"></circle>
                            <circle cx="680" cy="340" fill="#34d399" r="8" stroke="#ffffff" stroke-width="2.5"></circle>
                            <circle cx="750" cy="330" fill="#34d399" r="6" stroke="#ffffff" stroke-width="2"></circle>
                            <circle cx="890" cy="310" fill="#34d399" r="6" stroke="#ffffff" stroke-width="2"></circle>
                            <circle cx="1080" cy="260" fill="#34d399" r="6" stroke="#ffffff" stroke-width="2"></circle>
                            
                            <!-- Nodes: Warm Terracotta Non-Meter Nodes -->
                            <circle cx="760" cy="210" fill="#fbbf24" r="6" stroke="#ffffff" stroke-width="2"></circle>
                            <circle cx="840" cy="195" fill="#fbbf24" r="5.5" stroke="#ffffff" stroke-width="1.5"></circle>
                            <circle cx="920" cy="180" fill="#fbbf24" r="5.5" stroke="#ffffff" stroke-width="1.5"></circle>
                            <circle cx="260" cy="410" fill="#fbbf24" r="6" stroke="#ffffff" stroke-width="2"></circle>
                            
                            <!-- Nodes: Fault Red Nodes -->
                            <circle cx="390" cy="440" fill="#e11d48" r="7" stroke="#ffffff" stroke-width="2"></circle>
                            <circle class="animate-ping" cx="390" cy="440" opacity="0.75" r="14" stroke="#f43f5e" stroke-width="2"></circle>
                            <circle cx="710" cy="540" fill="#e11d48" r="7" stroke="#ffffff" stroke-width="2"></circle>
                            <circle class="animate-ping" cx="710" cy="540" opacity="0.75" r="14" stroke="#f43f5e" stroke-width="2"></circle>
                        </svg>
                    </div>

                    <!-- Quick Map Search & Layer Controls (Floating HUD) -->
                    <div class="absolute top-4 left-4 flex flex-col gap-2.5 z-20 max-w-sm w-full">
                        <div class="flex items-center gap-2 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl px-3.5 py-2.5 rounded-2xl shadow-xl border border-white/20">
                            <span class="material-symbols-outlined text-secondary text-[20px]">search</span>
                            <input class="bg-transparent font-label-md text-xs font-semibold text-gray-900 dark:text-white focus:outline-none w-full border-none p-0 focus:ring-0" placeholder="Cari IDPEL / Nama Ruas Jalan..." type="text" value="Basuki Rahmat"/>
                            <button aria-label="Clear Search" class="text-gray-400 hover:text-gray-600" type="button">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-1.5 bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-xl border border-white/20">
                            <button class="px-2.5 py-1 rounded-xl bg-gradient-to-r from-primary to-secondary text-white font-caption text-[11px] font-bold shadow-xs" type="button">Gardu Induk</button>
                            <button class="px-2.5 py-1 rounded-xl hover:bg-rose-50 text-gray-700 dark:text-gray-300 font-caption text-[11px] font-semibold transition-all" type="button">Tiang PJU (8.940)</button>
                            <button class="px-2.5 py-1 rounded-xl hover:bg-rose-50 text-gray-700 dark:text-gray-300 font-caption text-[11px] font-semibold transition-all" type="button">Kabel Jaringan</button>
                            <button class="px-2.5 py-1 rounded-xl hover:bg-rose-50 text-gray-700 dark:text-gray-300 font-caption text-[11px] font-semibold transition-all" type="button">Heatmap Daya</button>
                        </div>
                    </div>

                    <!-- Zoom & Compass Controls -->
                    <div class="absolute top-4 right-4 flex flex-col gap-1.5 z-20">
                        <button aria-label="Perbesar Peta" class="w-9 h-9 rounded-xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl hover:bg-white text-gray-800 dark:text-gray-200 flex items-center justify-center shadow-lg transition-all border border-white/20 hover:scale-105 active:scale-95" type="button">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                        </button>
                        <button aria-label="Perkecil Peta" class="w-9 h-9 rounded-xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl hover:bg-white text-gray-800 dark:text-gray-200 flex items-center justify-center shadow-lg transition-all border border-white/20 hover:scale-105 active:scale-95" type="button">
                            <span class="material-symbols-outlined text-[20px]">remove</span>
                        </button>
                        <button aria-label="Pusatkan Ulang Peta" class="w-9 h-9 rounded-xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl hover:bg-white text-gray-800 dark:text-gray-200 flex items-center justify-center shadow-lg transition-all border border-white/20 hover:scale-105 active:scale-95" type="button">
                            <span class="material-symbols-outlined text-[20px]">my_location</span>
                        </button>
                    </div>

                    <!-- Floating Map Tooltip/Card: Active Selected Node -->
                    <div class="absolute bottom-4 right-4 z-20 max-w-sm w-full bg-[#3c0c13]/95 text-white backdrop-blur-2xl p-5 rounded-3xl shadow-2xl border border-rose-500/30">
                        <div class="flex items-center justify-between border-b border-white/10 pb-2 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                <span class="font-caption text-[11px] text-emerald-300 uppercase font-extrabold tracking-wider">Panel Aktif Terukur</span>
                            </div>
                            <span class="font-caption text-[11px] text-rose-200 font-mono">ID: TPI-BKT-042</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="font-headline-sm text-base font-extrabold text-white leading-snug">
                                Panel Gardu IDPEL 543100892110
                            </span>
                            <span class="font-caption text-xs text-rose-200/80 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                Jl. Basuki Rahmat • Kec. Bukit Bestari
                            </span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-3 pt-3 border-t border-white/10 text-center">
                            <div class="flex flex-col bg-white/5 p-2 rounded-xl">
                                <span class="font-caption text-[10px] text-rose-200/70 uppercase">Beban</span>
                                <span class="font-telemetry-data text-xs font-bold text-white mt-0.5">4.8 kW</span>
                            </div>
                            <div class="flex flex-col bg-white/5 p-2 rounded-xl">
                                <span class="font-caption text-[10px] text-rose-200/70 uppercase">Tegangan</span>
                                <span class="font-telemetry-data text-xs font-bold text-emerald-300 mt-0.5">224 V</span>
                            </div>
                            <div class="flex flex-col bg-white/5 p-2 rounded-xl">
                                <span class="font-caption text-[10px] text-rose-200/70 uppercase">Koneksi</span>
                                <span class="font-telemetry-data text-xs font-bold text-white mt-0.5">34 Tiang</span>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 flex items-center justify-between text-xs">
                            <span class="inline-flex items-center gap-1.5 text-emerald-300 font-caption text-[11px]">
                                <span class="material-symbols-outlined text-[14px]">sensors</span>
                                Smart kWh Digital Online
                            </span>
                            <button class="text-rose-200 hover:text-white font-label-md text-xs font-bold inline-flex items-center gap-1 transition-colors" type="button">
                                Detail Gardu <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5. CHART & GRAFIS RELEVAN -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Chart A: Realisasi Beban Tagihan PJU Perbandingan Bulanan -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between lg:col-span-2">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div class="flex flex-col">
                            <h3 class="font-headline-sm text-lg font-extrabold text-gray-950 dark:text-white tracking-tight">
                                Tren Realisasi Tagihan Listrik PJU (Sebelum vs Setelah)
                            </h3>
                            <span class="font-caption text-xs text-gray-500 dark:text-gray-400">
                                Data audit belanja rekening listrik PJU Dishub Kota Tanjungpinang (dalam Miliar Rupiah)
                            </span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-950/40 text-secondary font-caption text-xs font-extrabold border border-rose-200/50">
                            Hemat Akumulasi Rp 7,84 M
                        </span>
                    </div>

                    <!-- Visual Bar comparison chart for 12 months -->
                    <div class="w-full flex flex-col gap-3 pt-2">
                        <div class="w-full h-56 flex items-end">
                            <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 720 200">
                                <!-- Grid horizontal lines -->
                                <line stroke="#f5ebec" stroke-width="1.5" x1="0" x2="720" y1="40" y2="40"></line>
                                <line stroke="#f5ebec" stroke-width="1.5" x1="0" x2="720" y1="90" y2="90"></line>
                                <line stroke="#f5ebec" stroke-width="1.5" x1="0" x2="720" y1="140" y2="140"></line>
                                <line stroke="#e8d8da" stroke-width="2" x1="0" x2="720" y1="190" y2="190"></line>
                                
                                <!-- Jan -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="25" y="42"></rect>
                                <rect fill="#7a1c28" height="85" rx="4" width="18" x="47" y="105"></rect>
                                <!-- Feb -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="85" y="42"></rect>
                                <rect fill="#7a1c28" height="88" rx="4" width="18" x="107" y="102"></rect>
                                <!-- Mar -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="145" y="42"></rect>
                                <rect fill="#7a1c28" height="92" rx="4" width="18" x="167" y="98"></rect>
                                <!-- Apr -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="205" y="42"></rect>
                                <rect fill="#7a1c28" height="96" rx="4" width="18" x="227" y="94"></rect>
                                <!-- Mei -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="265" y="42"></rect>
                                <rect fill="#7a1c28" height="100" rx="4" width="18" x="287" y="90"></rect>
                                <!-- Jun -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="325" y="42"></rect>
                                <rect fill="#7a1c28" height="102" rx="4" width="18" x="347" y="88"></rect>
                                <!-- Jul -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="385" y="42"></rect>
                                <rect fill="#7a1c28" height="98" rx="4" width="18" x="407" y="92"></rect>
                                <!-- Agu -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="445" y="42"></rect>
                                <rect fill="#7a1c28" height="95" rx="4" width="18" x="467" y="95"></rect>
                                <!-- Sep -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="505" y="42"></rect>
                                <rect fill="#7a1c28" height="90" rx="4" width="18" x="527" y="100"></rect>
                                <!-- Okt -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="565" y="42"></rect>
                                <rect fill="#7a1c28" height="86" rx="4" width="18" x="587" y="104"></rect>
                                <!-- Nov -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="625" y="42"></rect>
                                <rect fill="#7a1c28" height="82" rx="4" width="18" x="647" y="108"></rect>
                                <!-- Des -->
                                <rect fill="#f43f5e" height="148" opacity="0.3" rx="4" width="18" x="685" y="42"></rect>
                                <rect fill="#7a1c28" height="80" rx="4" width="18" x="707" y="110"></rect>
                            </svg>
                        </div>
                        <div class="grid grid-cols-12 text-center font-caption text-xs text-gray-500 dark:text-gray-400 font-semibold">
                            <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span>
                            <span>Jul</span><span>Agu</span><span>Sep</span><span>Okt</span><span>Nov</span><span>Des</span>
                        </div>
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-800 text-xs">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center gap-2 font-caption text-gray-600 dark:text-gray-400">
                                    <span class="w-3 h-3 rounded-md bg-rose-400 opacity-60"></span> Sebelum Meterisasi (Rp 1,57 M/bln)
                                </span>
                                <span class="flex items-center gap-2 font-caption text-gray-600 dark:text-gray-400">
                                    <span class="w-3 h-3 rounded-md bg-primary"></span> Setelah Terukur (Rp 0,91 M/bln)
                                </span>
                            </div>
                            <span class="font-telemetry-unit text-secondary font-extrabold">Penurunan: -41,6%</span>
                        </div>
                    </div>
                </div>

                <!-- Chart C: Distribusi Jenis Lampu & Efisiensi Daya -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between">
                    <div class="flex flex-col gap-1 mb-2">
                        <h3 class="font-headline-sm text-lg font-extrabold text-gray-950 dark:text-white tracking-tight">
                            Distribusi Jenis Lampu &amp; Daya
                        </h3>
                        <span class="font-caption text-xs text-gray-500 dark:text-gray-400">Total 8.940 titik lampu terpasang</span>
                    </div>

                    <!-- Donut Chart SVG -->
                    <div class="flex items-center justify-center my-2 relative">
                        <svg class="w-44 h-44 -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" fill="none" r="38" stroke="#f5ebec" stroke-width="14"></circle>
                            <!-- Smart LED 60W-90W (68.5%) -->
                            <circle cx="50" cy="50" fill="none" r="38" stroke="#7a1c28" stroke-dasharray="163.6 238.7" stroke-dashoffset="0" stroke-width="14"></circle>
                            <!-- Conventional LED 120W (21.8%) -->
                            <circle cx="50" cy="50" fill="none" r="38" stroke="#c94657" stroke-dasharray="52.1 238.7" stroke-dashoffset="-163.6" stroke-width="14"></circle>
                            <!-- Son-T 250W lama (9.7%) -->
                            <circle cx="50" cy="50" fill="none" r="38" stroke="#fbbf24" stroke-dasharray="23.1 238.7" stroke-dashoffset="-215.7" stroke-width="14"></circle>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="font-telemetry-data text-2xl font-black text-gray-900 dark:text-white">68,5%</span>
                            <span class="font-caption text-[11px] text-gray-500 uppercase font-bold tracking-wider">Smart LED</span>
                        </div>
                    </div>

                    <!-- Legend Breakdown -->
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                                Smart LED (60W – 90W)
                            </span>
                            <span class="font-telemetry-unit font-bold text-gray-900 dark:text-white">6.120 (68,5%)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                                LED Konvensional (120W)
                            </span>
                            <span class="font-telemetry-unit font-bold text-gray-900 dark:text-white">1.950 (21,8%)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                                Son-T / Merkuri (250W)
                            </span>
                            <span class="font-telemetry-unit font-bold text-gray-900 dark:text-white">870 (9,7%)</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6. PROGRES PER KECAMATAN & TIM REAKSI CEPAT -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Chart B: Status Meterisasi IDPEL per Kecamatan -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 lg:col-span-2 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex flex-col">
                            <h3 class="font-headline-sm text-lg font-extrabold text-gray-950 dark:text-white tracking-tight">
                                Status Meterisasi IDPEL per Kecamatan
                            </h3>
                            <span class="font-caption text-xs text-gray-500 dark:text-gray-400">
                                Kemajuan pemasangan kWh meter terukur dari total 1.482 sambungan IDPEL
                            </span>
                        </div>
                        <span class="font-caption text-xs font-bold text-primary uppercase bg-rose-50 dark:bg-rose-950/40 px-3 py-1 rounded-full border border-rose-200/50">Target TA 2025</span>
                    </div>

                    <div class="flex flex-col gap-4">
                        <!-- Bukit Bestari -->
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between font-label-md text-xs">
                                <span class="font-bold text-gray-900 dark:text-white">Kecamatan Bukit Bestari</span>
                                <span class="font-telemetry-unit font-bold text-secondary">
                                    412 Terukur / 68 Belum <span class="text-gray-400 font-normal">(85,8%)</span>
                                </span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-l-full" style="width: 85.8%;"></div>
                                <div class="h-full bg-amber-400 rounded-r-full" style="width: 14.2%;"></div>
                            </div>
                        </div>

                        <!-- Tanjungpinang Timur -->
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between font-label-md text-xs">
                                <span class="font-bold text-gray-900 dark:text-white">Kecamatan Tanjungpinang Timur</span>
                                <span class="font-telemetry-unit font-bold text-secondary">
                                    440 Terukur / 120 Belum <span class="text-gray-400 font-normal">(78,6%)</span>
                                </span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-l-full" style="width: 78.6%;"></div>
                                <div class="h-full bg-amber-400 rounded-r-full" style="width: 21.4%;"></div>
                            </div>
                        </div>

                        <!-- Tanjungpinang Kota -->
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between font-label-md text-xs">
                                <span class="font-bold text-gray-900 dark:text-white">Kecamatan Tanjungpinang Kota</span>
                                <span class="font-telemetry-unit font-bold text-secondary">
                                    164 Terukur / 58 Belum <span class="text-gray-400 font-normal">(73,9%)</span>
                                </span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-l-full" style="width: 73.9%;"></div>
                                <div class="h-full bg-amber-400 rounded-r-full" style="width: 26.1%;"></div>
                            </div>
                        </div>

                        <!-- Tanjungpinang Barat -->
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between font-label-md text-xs">
                                <span class="font-bold text-gray-900 dark:text-white">Kecamatan Tanjungpinang Barat</span>
                                <span class="font-telemetry-unit font-bold text-secondary">
                                    140 Terukur / 80 Belum <span class="text-gray-400 font-normal">(63,6%)</span>
                                </span>
                            </div>
                            <div class="w-full h-3 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                                <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-l-full" style="width: 63.6%;"></div>
                                <div class="h-full bg-amber-400 rounded-r-full" style="width: 36.4%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs">
                        <span class="text-gray-500 dark:text-gray-400">Prioritas Pemasangan: Kawasan Ring Road &amp; Pesisir Barat</span>
                        <span class="font-bold text-primary dark:text-rose-400">Total Terukur: 1.156 IDPEL (78,0%)</span>
                    </div>
                </div>

                <!-- Mini Visual Card: Field Dispatch & PJU Maintenance Vehicle -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tim Reaksi Cepat</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-caption text-[11px] font-extrabold border border-emerald-200/60">
                            3 Regu Siaga
                        </span>
                    </div>
                    <div class="w-full h-36 rounded-2xl overflow-hidden relative mb-3 shadow-inner">
                        <img class="w-full h-full object-cover" alt="Teknisi Dishub Tanjungpinang" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhFKz8fIC3wpgmR1UDJQsFVoae-8YO_2B6SaHvHE7Dmes9DxUbsMXIDd0ykOuAtv8mX4cbssuRTwB743ASNo46ZWRMc4Jb86AD_cnk41_nfLiQhCSx0S4NEQlQsP7vc591YI-jEQ58aZmAAUQe-pwrKeMPfHrwL36G2kmnaDAgijeCj-a9TjrIVqn4iCjSxUdP1alWcbSeSFww9n15eEAzgzxHHfzgQXijicKteptk2Gan2Ozf_Ayp"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent"></div>
                        <div class="absolute bottom-2.5 left-3 right-3 text-white flex items-center justify-between">
                            <span class="font-label-md text-xs font-bold">Patroli Malam &amp; Perbaikan</span>
                            <span class="font-caption text-[11px] text-emerald-300 font-extrabold bg-black/40 px-2 py-0.5 rounded-md backdrop-blur-sm">Uptime 98,2%</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <span class="font-caption text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                            Laporan Warga Masuk: <strong class="text-gray-900 dark:text-white font-bold">2 Tiket</strong> dalam 24 jam terakhir. Respon rata-rata: <strong class="text-primary font-bold">45 Menit</strong>.
                        </span>
                        <button class="w-full bg-gradient-to-r from-rose-50 to-rose-100 hover:from-rose-100 hover:to-rose-200 text-primary py-2.5 rounded-xl font-label-md text-xs font-bold transition-all text-center mt-1 border border-rose-200 shadow-xs" type="button">
                            Buka Jadwal Tiket Lapangan
                        </button>
                    </div>
                </div>
            </section>

            <!-- 7. TABEL MONITORING CEPAT (Live PJU Feed & Incident Alerts) -->
            <section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 lg:p-7 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-secondary flex items-center justify-center border border-rose-100/60">
                                <span class="material-symbols-outlined text-[20px]">tune</span>
                            </span>
                            <h3 class="font-headline-sm text-lg sm:text-xl font-extrabold text-gray-950 dark:text-white tracking-tight">
                                Log Telemetri &amp; Monitoring Terkini IDPEL PJU
                            </h3>
                        </div>
                        <span class="font-caption text-xs text-gray-500 dark:text-gray-400 pl-10">
                            Pembaruan waktu nyata dari panel SCADA dan sinkronisasi server PLN UID Riau &amp; Kepri
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-xl font-label-md text-xs font-bold transition-all border border-rose-100 shadow-xs" type="button">
                            <span class="material-symbols-outlined text-[16px]">filter_list</span>
                            <span>Saring Data</span>
                        </button>
                        <button class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-200 px-3 py-2 rounded-xl font-label-md text-xs font-bold transition-all border border-rose-100 shadow-xs" type="button">
                            <span class="material-symbols-outlined text-[16px]">refresh</span>
                            <span>Segarkan</span>
                        </button>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto rounded-2xl border border-rose-100/70 dark:border-rose-950/50 shadow-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-rose-50/60 dark:bg-rose-950/30 text-gray-600 dark:text-gray-400 font-caption text-[11px] uppercase tracking-wider border-b border-rose-100/80">
                                <th class="p-3.5 pl-4 font-bold">IDPEL PLN</th>
                                <th class="p-3.5 font-bold">Lokasi / Ruas Jalan</th>
                                <th class="p-3.5 font-bold">Kecamatan</th>
                                <th class="p-3.5 font-bold">Status Meterisasi</th>
                                <th class="p-3.5 font-bold">Daya / kWh Terukur</th>
                                <th class="p-3.5 font-bold">Status Jaringan</th>
                                <th class="p-3.5 pr-4 text-right font-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100/50 dark:divide-rose-950/30 font-body-sm text-xs text-gray-800 dark:text-gray-200 bg-white/50 dark:bg-gray-900/50">
                            <tr v-for="idpel in idpels.data" :key="idpel.id" class="hover:bg-rose-50/40 dark:hover:bg-rose-950/20 transition-colors">
                                <td class="p-3.5 pl-4 font-telemetry-unit font-bold text-primary">
                                    {{ idpel.idpel_number }}
                                </td>
                                <td class="p-3.5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-950 dark:text-white">{{ idpel.name }}</span>
                                        <span class="font-caption text-[11px] text-gray-500">{{ idpel.address || 'Alamat tidak tersedia' }}</span>
                                    </div>
                                </td>
                                <td class="p-3.5 text-gray-600">{{ idpel.district ? idpel.district.name : '-' }}</td>
                                <td class="p-3.5">
                                    <span v-if="idpel.status === 'meterisasi'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-caption text-[11px] font-bold border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Meter Digital
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 font-caption text-[11px] font-bold border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Non-Meter (Flat)
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    <div class="flex flex-col">
                                        <span v-if="idpel.status === 'meterisasi'" class="font-telemetry-data font-bold text-gray-900 dark:text-white">
                                            {{ idpel.substations && idpel.substations.length > 0 ? idpel.substations[0].current_kw : '0' }} kW
                                        </span>
                                        <span v-else class="font-telemetry-data font-bold text-gray-700">Beban Flat Kontrak</span>
                                        <span class="font-caption text-[11px] text-gray-500">
                                            {{ idpel.status === 'meterisasi' ? 'Monitoring Aktif' : 'Estimasi ' + (idpel.substations && idpel.substations.length > 0 ? (idpel.substations[0].current_kw * 1000) : '6.600') + ' VA' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-caption text-[11px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 100% Nyala (Normal)
                                    </span>
                                </td>
                                <td class="p-3.5 pr-4 text-right">
                                    <button class="p-1.5 rounded-lg hover:bg-rose-100 text-gray-500 hover:text-primary transition-colors" title="Lihat Detail Telemetri" type="button">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="idpels.data.length === 0">
                                <td colspan="7" class="p-3.5 text-center text-gray-500 font-caption py-8">
                                    Tidak ada data IDPEL ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer & Summary -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 text-xs">
                    <span class="font-caption text-gray-500">
                        Menampilkan {{ idpels.to || 0 }} dari {{ formatNumber(idpels.total) }} IDPEL Terdaftar • Sinkronisasi otomatis setiap 60 detik
                    </span>
                    <div class="flex items-center gap-1.5">
                        <button class="px-3 py-1.5 rounded-xl bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-300 font-caption text-xs font-semibold transition-all border border-rose-100 shadow-xs" type="button">Sebelumnya</button>
                        <button class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-primary to-secondary text-white font-caption text-xs font-bold shadow-xs" type="button">1</button>
                        <button class="px-3 py-1.5 rounded-xl bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-300 font-caption text-xs font-semibold transition-all border border-rose-100 shadow-xs" type="button">2</button>
                        <button class="px-3 py-1.5 rounded-xl bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-300 font-caption text-xs font-semibold transition-all border border-rose-100 shadow-xs" type="button">3</button>
                        <button class="px-3 py-1.5 rounded-xl bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-300 font-caption text-xs font-semibold transition-all border border-rose-100 shadow-xs" type="button">Selanjutnya</button>
                    </div>
                </div>
            </section>

        </main>
    </DashboardLayout>
</template>
