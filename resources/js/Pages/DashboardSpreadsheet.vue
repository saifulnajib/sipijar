<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

const generateDummyChartData = () => {
    const data = [];
    const labels = [];
    
    let currentDate = new Date(2026, 8, 8); 
    const endDate = new Date(2026, 9, 14); 
    
    let workdaysCount = 0;
    let tempDate = new Date(currentDate);
    while (tempDate <= endDate) {
        if (tempDate.getDay() !== 0) { 
            workdaysCount++;
        }
        tempDate.setDate(tempDate.getDate() + 1);
    }
    
    let basePerDay = Math.floor(65 / workdaysCount); 
    let extraDays = 65 % workdaysCount; 
    
    while (currentDate <= endDate) {
        const dateString = currentDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        
        if (currentDate.getDay() === 0) {
            labels.push(dateString);
            data.push(0);
        } else {
            let todayValue = basePerDay;
            if (extraDays > 0) {
                todayValue += 1;
                extraDays--;
            }
            labels.push(dateString);
            data.push(todayValue);
        }
        currentDate.setDate(currentDate.getDate() + 1);
    }
    
    return { labels, data };
};

const dummyChartData = generateDummyChartData();

const chartData = {
    labels: dummyChartData.labels,
    datasets: [
        {
            label: 'IDPEL Selesai Survei',
            data: dummyChartData.data,
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#6366f1',
            pointRadius: 4,
            pointHoverRadius: 6
        }
    ]
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    return context.parsed.y + ' IDPEL Selesai';
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1,
            },
            grid: {
                color: 'rgba(0, 0, 0, 0.05)',
            }
        },
        x: {
            grid: {
                display: false,
            },
            ticks: {
                maxTicksLimit: 10
            }
        }
    }
};

const props = defineProps({
    pjuData: Object,
    kpis: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const jenisLampu = ref(props.filters.jenis_lampu || 'all');
const statusMeter = ref(props.filters.status_meter || 'all');
const survei = ref(props.filters.survei || 'all');
const meterisasi = ref(props.filters.meterisasi || 'all');
const isRefreshing = ref(false);

const applyFilters = () => {
    router.get(
        route('dashboard'),
        {
            search: search.value || undefined,
            jenis_lampu: jenisLampu.value !== 'all' ? jenisLampu.value : undefined,
            status_meter: statusMeter.value !== 'all' ? statusMeter.value : undefined,
            survei: survei.value !== 'all' ? survei.value : undefined,
            meterisasi: meterisasi.value !== 'all' ? meterisasi.value : undefined,
        },
        { preserveState: true, replace: true, preserveScroll: true }
    );
};

let searchTimeout = null;
watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 300);
});

watch([jenisLampu, statusMeter, survei, meterisasi], () => {
    applyFilters();
});

const resetFilters = () => {
    search.value = '';
    jenisLampu.value = 'all';
    statusMeter.value = 'all';
    survei.value = 'all';
    meterisasi.value = 'all';
    applyFilters();
};

const hasActiveFilters = computed(() => {
    return Boolean(search.value || jenisLampu.value !== 'all' || statusMeter.value !== 'all' || survei.value !== 'all' || meterisasi.value !== 'all');
});

const refreshCache = () => {
    isRefreshing.value = true;
    router.post(route('dashboard.clear-cache'), {}, {
        preserveScroll: true,
        onFinish: () => {
            isRefreshing.value = false;
        }
    });
};

const formatNumber = (value) => {
    if (!value && value !== 0) return '0';
    return new Intl.NumberFormat('id-ID').format(value);
};

// Percentage calculations for progress bars
const ledPercentage = computed(() => {
    if (!props.kpis.total_titik) return 0;
    return Math.round((props.kpis.led_count / props.kpis.total_titik) * 100);
});

const ballasPercentage = computed(() => {
    if (!props.kpis.total_titik) return 0;
    return Math.round((props.kpis.ballas_count / props.kpis.total_titik) * 100);
});

const meterPercentage = computed(() => {
    if (!props.kpis.total_titik) return 0;
    return Math.round((props.kpis.meter_count / props.kpis.total_titik) * 100);
});

const abonemenPercentage = computed(() => {
    if (!props.kpis.total_titik) return 0;
    return Math.round((props.kpis.abonemen_count / props.kpis.total_titik) * 100);
});

const idpelMeterPercentage = computed(() => {
    if (!props.kpis.total_idpel) return 0;
    return Math.round((props.kpis.idpel_meter_count / props.kpis.total_idpel) * 100);
});

const idpelAbonemenPercentage = computed(() => {
    if (!props.kpis.total_idpel) return 0;
    return Math.round((props.kpis.idpel_abonemen_count / props.kpis.total_idpel) * 100);
});

const surveiSudahPercentage = computed(() => {
    const totalTarget = props.kpis.abonemen_count || 1;
    if (totalTarget === 1 && !props.kpis.abonemen_count) return 0;
    return Math.round((props.kpis.survei_sudah_count / totalTarget) * 100);
});

const surveiBelumPercentage = computed(() => {
    const totalTarget = props.kpis.abonemen_count || 1;
    if (totalTarget === 1 && !props.kpis.abonemen_count) return 0;
    return Math.round((props.kpis.survei_belum_count / totalTarget) * 100);
});
</script>

<template>
    <Head title="Data Spreadsheet PJU - SIPIJAR Dishub Tanjungpinang" />

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
                                Dishub Kota Tanjungpinang • Cloud Stream API
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-caption text-[11px] font-medium border border-gray-200/60 dark:border-gray-700/60">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Google Spreadsheet Cache v1.0
                            </span>
                        </div>
                        <h1 class="font-headline-xl text-2xl sm:text-3xl font-extrabold text-gray-950 dark:text-white tracking-tight leading-tight">
                            Data Master &amp; Telemetri Titik PJU (Spreadsheet Live API)
                        </h1>
                        <p class="font-body-md text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            Integrasi aliran data {{ formatNumber(kpis.total_titik) }} titik penerangan jalan umum dan {{ formatNumber(kpis.total_idpel) }} sambungan IDPEL se-Kota Tanjungpinang secara terpusat.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap items-center gap-3">
                        <button
                            @click="refreshCache"
                            :disabled="isRefreshing"
                            class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-rose-50/80 dark:hover:bg-rose-950/40 text-gray-800 dark:text-gray-200 px-4 py-2.5 rounded-xl font-label-md text-xs font-bold transition-all duration-200 border border-rose-200/70 dark:border-rose-900/50 shadow-xs hover:shadow hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-60 cursor-pointer"
                            type="button"
                        >
                            <span class="material-symbols-outlined text-[18px] text-secondary" :class="{ 'animate-spin': isRefreshing }">sync</span>
                            <span>{{ isRefreshing ? 'Menyinkronkan...' : 'Sinkronisasi Ulang API' }}</span>
                        </button>
                        <Link
                            :href="route('maps.index')"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-primary via-secondary to-primary-container hover:from-primary-container hover:to-primary text-white px-4 py-2.5 rounded-xl font-label-md text-xs font-bold transition-all duration-200 shadow-md shadow-primary/20 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                        >
                            <span class="material-symbols-outlined text-[18px]">map</span>
                            <span>Lihat di Peta GIS</span>
                        </Link>
                    </div>
                </div>

                <!-- Quick Control Filter Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 pt-6 mt-6 border-t border-rose-100/50 dark:border-rose-950/40">
                    <!-- Search Input -->
                    <div class="flex flex-col gap-1.5 bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 hover:border-primary/30 transition-all">
                        <label class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5" for="filter-search">
                            <span class="material-symbols-outlined text-secondary text-[16px]">search</span>
                            Pencarian Kata Kunci
                        </label>
                        <div class="relative flex items-center">
                            <input 
                                v-model="search"
                                id="filter-search"
                                type="text" 
                                placeholder="Alamat, IDPEL, No Urut..." 
                                class="w-full bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none border-none p-0 focus:ring-0"
                            />
                            <button 
                                v-if="search" 
                                @click="search = ''" 
                                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 ml-1"
                                type="button"
                            >
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Jenis Lampu -->
                    <div class="flex flex-col gap-1.5 bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 hover:border-primary/30 transition-all">
                        <label class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5" for="filter-lampu">
                            <span class="material-symbols-outlined text-secondary text-[16px]">lightbulb</span>
                            Jenis Lampu
                        </label>
                        <select v-model="jenisLampu" class="w-full bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0" id="filter-lampu">
                            <option value="all">Semua Jenis (LED &amp; Ballas)</option>
                            <option value="LED">Hanya LED ({{ formatNumber(kpis.led_count) }} Titik)</option>
                            <option value="BALLAS">Hanya Ballas ({{ formatNumber(kpis.ballas_count) }} Titik)</option>
                        </select>
                    </div>

                    <!-- Filter Status Meter -->
                    <div class="flex flex-col gap-1.5 bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30 hover:border-primary/30 transition-all">
                        <label class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5" for="filter-meter">
                            <span class="material-symbols-outlined text-secondary text-[16px]">electric_meter</span>
                            Skema Tarif
                        </label>
                        <select v-model="statusMeter" class="w-full bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0" id="filter-meter">
                            <option value="all">Semua Skema</option>
                            <option value="METER">Meterisasi Terukur</option>
                            <option value="TERSEBAR">Abonemen / Tersebar</option>
                        </select>
                    </div>

                    <!-- Reset & Status Filter -->
                    <div class="flex items-center justify-between bg-rose-50/40 dark:bg-rose-950/20 p-3 rounded-2xl border border-rose-100/60 dark:border-rose-900/30">
                        <div class="flex flex-col">
                            <span class="font-caption text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Hasil Saringan</span>
                            <span class="font-label-md text-xs font-bold text-gray-900 dark:text-white">
                                {{ formatNumber(pjuData.total) }} Data Ditemukan
                            </span>
                        </div>
                        <button
                            v-if="hasActiveFilters"
                            @click="resetFilters"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-rose-100 dark:bg-rose-900/60 text-primary dark:text-rose-200 text-xs font-bold hover:bg-rose-200 transition-colors"
                            type="button"
                        >
                            <span class="material-symbols-outlined text-[14px]">restart_alt</span>
                            Reset
                        </button>
                    </div>
                </div>
            </section>

            <!-- 2. KEY KPI METRIC CARDS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Card 1: Total Titik PJU -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-rose-100/50 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Titik Lampu PJU</span>
                            <span class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-secondary flex items-center justify-center border border-rose-100/60 dark:border-rose-900/40">
                                <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-gray-950 dark:text-white font-extrabold tracking-tight">
                                {{ formatNumber(kpis.total_titik) }}
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/40 text-primary dark:text-rose-300 font-caption text-[11px] font-bold border border-rose-200/50">
                                100% Aktif
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <span class="font-caption text-[11px] text-gray-500 dark:text-gray-400">Tersebar di 4 Kecamatan Kota Tanjungpinang</span>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-primary to-secondary rounded-full" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total IDPEL Terdata -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-blue-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">IDPEL Pelanggan PLN</span>
                            <span class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 flex items-center justify-center border border-blue-100/60 dark:border-blue-900/40">
                                <span class="material-symbols-outlined text-[20px]">hub</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-gray-950 dark:text-white font-extrabold tracking-tight">
                                {{ formatNumber(kpis.idpel_meter_count+kpis.idpel_abonemen_count) }}
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 font-caption text-[11px] font-bold border border-blue-200/50">
                                IDPEL Unik
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between font-caption text-[11px] text-gray-500 dark:text-gray-400">
                            <span>Meter: {{ formatNumber(kpis.idpel_meter_count) }} ({{ idpelMeterPercentage }}%)</span>
                            <span>Abonemen: {{ formatNumber(kpis.idpel_abonemen_count) }} ({{ idpelAbonemenPercentage }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                            <div class="h-full bg-blue-500 rounded-l-full" :style="`width: ${idpelMeterPercentage}%;`"></div>
                            <div class="h-full bg-indigo-400 rounded-r-full" :style="`width: ${idpelAbonemenPercentage}%;`"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Status Survei -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-indigo-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Survei (Non-Meter)</span>
                            <span class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 flex items-center justify-center border border-indigo-100/60 dark:border-indigo-900/40">
                                <span class="material-symbols-outlined text-[20px]">fact_check</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-2xl sm:text-3xl text-indigo-600 dark:text-indigo-400 font-extrabold tracking-tight">
                                {{ formatNumber(kpis.survei_sudah_count) }} <span class="text-xs font-sans text-gray-500 dark:text-gray-400 font-normal">Sudah</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-indigo-100/80 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 font-caption text-[11px] font-bold border border-indigo-200/50">
                                {{ surveiSudahPercentage }}% Selesai
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between font-caption text-[11px] text-gray-500 dark:text-gray-400">
                            <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Sudah: {{ formatNumber(kpis.survei_sudah_count) }} ({{ surveiSudahPercentage }}%)</span>
                            <span class="text-gray-600 dark:text-gray-400 font-semibold">Belum: {{ formatNumber(kpis.survei_belum_count) }} ({{ surveiBelumPercentage }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                            <div class="h-full bg-indigo-500 rounded-l-full" :style="`width: ${surveiSudahPercentage}%;`"></div>
                            <div class="h-full bg-gray-300 dark:bg-gray-700 rounded-r-full" :style="`width: ${surveiBelumPercentage}%;`"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Survei per Unit PLN -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-violet-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Survei Unit PLN (Non-Meter)</span>
                            <span class="w-9 h-9 rounded-xl bg-violet-50 dark:bg-violet-950/40 text-violet-600 flex items-center justify-center border border-violet-100/60 dark:border-violet-900/40">
                                <span class="material-symbols-outlined text-[20px]">domain</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex flex-col gap-1.5 h-[52px] overflow-y-auto">
                            <div v-for="(data, unit) in kpis.survei_per_unit" :key="unit" class="flex items-center justify-between">
                                <span class="font-label-md text-xs font-bold text-gray-700 dark:text-gray-300 truncate w-32" :title="unit">{{ unit }}</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-caption text-[10px] text-gray-500 font-semibold">{{ data.percentage }}%</span>
                                    <span class="font-telemetry font-bold text-violet-600 dark:text-violet-400 bg-violet-50 dark:bg-violet-900/30 px-2 py-0.5 rounded-md border border-violet-100/50 dark:border-violet-800/50 text-sm">
                                        {{ formatNumber(data.count) }}
                                    </span>
                                </div>
                            </div>
                            <div v-if="!kpis.survei_per_unit || Object.keys(kpis.survei_per_unit).length === 0" class="text-xs text-gray-400 italic">Belum ada data</div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <span class="font-caption text-[11px] text-gray-500 dark:text-gray-400 text-center">Distribusi titik yang sudah disurvei</span>
                    </div>
                </div>

                <!-- Card untuk tiap Unit PLN -->
                <div v-for="(stats, unit) in kpis.stats_per_unit" :key="'card-' + unit" class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-cyan-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider truncate w-40" :title="unit">Unit {{ unit }}</span>
                            <span class="w-9 h-9 rounded-xl bg-cyan-50 dark:bg-cyan-950/40 text-cyan-600 flex items-center justify-center border border-cyan-100/60 dark:border-cyan-900/40 flex-shrink-0">
                                <span class="material-symbols-outlined text-[20px]">leaderboard</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-2xl sm:text-3xl text-cyan-600 dark:text-cyan-400 font-extrabold tracking-tight">
                                {{ formatNumber(stats.total_titik) }} <span class="text-xs font-sans text-gray-500 dark:text-gray-400 font-normal">Titik</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-cyan-100/80 dark:bg-cyan-950/60 text-cyan-800 dark:text-cyan-300 font-caption text-[11px] font-bold border border-cyan-200/50">
                                Unit PLN
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between font-caption text-[11px] text-gray-500 dark:text-gray-400">
                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold" :title="'Meterisasi: ' + formatNumber(stats.meter_count)">Meter: {{ formatNumber(stats.meter_count) }}</span>
                            <span class="text-amber-600 dark:text-amber-400 font-semibold" :title="'Abonemen: ' + formatNumber(stats.abonemen_count)">Belum Meter: {{ formatNumber(stats.abonemen_count) }}</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                            <div class="h-full bg-emerald-500 rounded-l-full" :style="`width: ${stats.total_titik ? (stats.meter_count/stats.total_titik*100) : 0}%;`"></div>
                            <div class="h-full bg-amber-400 rounded-r-full" :style="`width: ${stats.total_titik ? (stats.abonemen_count/stats.total_titik*100) : 0}%;`"></div>
                        </div>
                    </div>
                </div>

                 <!-- Card 3: Total Estimasi Daya -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-amber-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Kapasitas Daya</span>
                            <span class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 flex items-center justify-center border border-amber-100/60 dark:border-amber-900/40">
                                <span class="material-symbols-outlined text-[20px]">bolt</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-3xl sm:text-4xl text-gray-950 dark:text-white font-extrabold tracking-tight">
                                {{ kpis.total_daya_kw }}
                                <span class="font-telemetry-unit text-base sm:text-lg font-bold text-amber-500 ml-1">kW</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-caption text-[11px] font-bold border border-amber-200/50">
                                Terpasang
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <span class="font-caption text-[11px] text-gray-500 dark:text-gray-400">Beban daya kumulatif seluruh armatur jalan</span>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full" style="width: 100%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Komposisi Jenis Lampu -->
                <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 sm:p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-gradient-to-br from-emerald-100/40 to-transparent rounded-full opacity-60 group-hover:scale-150 transition-transform duration-500"></div>
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Komposisi Lampu</span>
                            <span class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center border border-emerald-100/60 dark:border-emerald-900/40">
                                <span class="material-symbols-outlined text-[20px]">tune</span>
                            </span>
                        </div>
                        <div class="mt-4 mb-2 flex items-baseline justify-between">
                            <div class="font-telemetry-data text-2xl sm:text-3xl text-emerald-600 dark:text-emerald-400 font-extrabold tracking-tight">
                                {{ formatNumber(kpis.led_count) }} <span class="text-xs font-sans text-gray-500 dark:text-gray-400 font-normal">LED</span>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-emerald-100/80 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-caption text-[11px] font-bold">
                                {{ ledPercentage }}% Hemat
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center justify-between font-caption text-[11px] text-gray-500 dark:text-gray-400">
                            <span class="text-emerald-600 dark:text-emerald-400 font-semibold">LED: {{ formatNumber(kpis.led_count) }} ({{ ledPercentage }}%)</span>
                            <span class="text-rose-600 dark:text-rose-400 font-semibold">Ballas: {{ formatNumber(kpis.ballas_count) }} ({{ ballasPercentage }}%)</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-0.5">
                            <div class="h-full bg-emerald-500 rounded-l-full" :style="`width: ${ledPercentage}%;`"></div>
                            <div class="h-full bg-rose-500 rounded-r-full" :style="`width: ${ballasPercentage}%;`"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. SPECIAL LUXURY HERO BANNER -->
            <section class="bg-gradient-to-br from-[#4c0f17] via-[#7a1c28] to-[#36080e] text-white rounded-3xl p-6 sm:p-8 lg:p-9 shadow-2xl border border-rose-500/20 relative overflow-hidden">
                <!-- Radial Luminous Accents -->
                <div class="absolute -right-24 -top-24 w-96 h-96 bg-rose-500/15 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-secondary/25 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col gap-6">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex flex-col gap-2 max-w-3xl">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/80 text-white font-caption text-xs font-extrabold uppercase tracking-wider backdrop-blur-sm border border-white/10">
                                    <span class="material-symbols-outlined text-[14px]">table_chart</span>
                                    Streaming Dataset PJU
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-rose-100 font-caption text-xs border border-white/10">
                                    Format Tabular Resmi Dishub Tanjungpinang
                                </span>
                            </div>
                            <h2 class="font-headline-lg text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-snug">
                                Pemantauan Efisiensi &amp; Validasi Data Lapangan PJU Kota Tanjungpinang
                            </h2>
                            <p class="font-body-sm text-xs sm:text-sm text-rose-200/90 leading-relaxed">
                                Data ini diselaraskan otomatis dengan pencatatan inventaris lapangan. Setiap baris mewakili 1 titik fisik lampu PJU beserta daya kontrak dan skema pengukurannya.
                            </p>
                        </div>

                        <!-- Highlight Pill -->
                        <div class="bg-white/10 backdrop-blur-xl p-5 sm:p-6 rounded-2xl flex flex-col items-start lg:items-end justify-center border border-white/15 shadow-inner flex-shrink-0">
                            <span class="font-caption text-xs text-rose-200 uppercase font-bold tracking-wider">Status Sinkronisasi Sistem</span>
                            <div class="font-telemetry-data text-2xl sm:text-3xl text-rose-200 font-black tracking-tight mt-1 flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                                AKTIF &amp; TERHUBUNG
                            </div>
                            <span class="inline-flex items-center gap-1.5 text-emerald-300 font-label-md text-xs font-extrabold mt-2 bg-emerald-950/40 px-3 py-1 rounded-full border border-emerald-500/30">
                                <span class="material-symbols-outlined text-[16px]">verified</span>
                                {{ formatNumber(kpis.total_titik) }} Record Valid
                            </span>
                        </div>
                    </div>

                    <!-- Mini Highlight Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div class="bg-black/20 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">energy_savings_leaf</span>
                            </div>
                            <div>
                                <span class="font-caption text-[11px] text-rose-200/80 uppercase font-bold tracking-wider block">Armatur LED Hemat Energi</span>
                                <span class="font-telemetry-data text-xl font-bold text-white">{{ formatNumber(kpis.led_count) }} Titik ({{ ledPercentage }}%)</span>
                            </div>
                        </div>

                        <div class="bg-black/20 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">speed</span>
                            </div>
                            <div>
                                <span class="font-caption text-[11px] text-rose-200/80 uppercase font-bold tracking-wider block">Sistem Terukur Meterisasi</span>
                                <span class="font-telemetry-data text-xl font-bold text-white">{{ formatNumber(kpis.meter_count) }} Titik ({{ meterPercentage }}%)</span>
                            </div>
                        </div>

                        <div class="bg-black/20 backdrop-blur-md p-4 rounded-2xl border border-white/10 flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[22px]">pending</span>
                            </div>
                            <div>
                                <span class="font-caption text-[11px] text-rose-200/80 uppercase font-bold tracking-wider block">Skema Flat / Abonemen</span>
                                <span class="font-telemetry-data text-xl font-bold text-white">{{ formatNumber(kpis.abonemen_count) }} Titik ({{ abonemenPercentage }}%)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. PROGRESS SURVEI CHART SECTION -->
            <section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 lg:p-7 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col gap-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 flex items-center justify-center border border-indigo-100/60 dark:border-indigo-900/40">
                        <span class="material-symbols-outlined text-[20px]">show_chart</span>
                    </div>
                    <div>
                        <h3 class="font-headline-sm text-lg sm:text-xl font-extrabold text-gray-950 dark:text-white tracking-tight">
                            Progress Survei IDPEL Abonemen Harian
                        </h3>
                        <p class="font-caption text-xs text-gray-500 dark:text-gray-400">Periode 8 September - 14 Oktober 2026 (Distribusi 65 IDPEL)</p>
                    </div>
                </div>
                
                <div class="h-80 w-full mt-2 bg-gray-50/50 dark:bg-gray-950/30 rounded-2xl p-4 border border-gray-100 dark:border-gray-800">
                    <Line :data="chartData" :options="chartOptions" />
                </div>
            </section>

            <!-- 5. DATA TABLE SECTION -->
            <section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 lg:p-7 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-secondary flex items-center justify-center border border-rose-100/60">
                                <span class="material-symbols-outlined text-[20px]">view_list</span>
                            </span>
                            <h3 class="font-headline-sm text-lg sm:text-xl font-extrabold text-gray-950 dark:text-white tracking-tight">
                                Daftar Lengkap Titik Lampu PJU
                            </h3>
                        </div>
                        <span class="font-caption text-xs text-gray-500 dark:text-gray-400 pl-10">
                            Menampilkan baris {{ pjuData.from || 0 }} sampai {{ pjuData.to || 0 }} dari total {{ formatNumber(pjuData.total) }} data titik
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-gray-700 dark:text-gray-300 font-caption text-xs font-semibold border border-rose-100 dark:border-rose-900/40">
                            <span class="material-symbols-outlined text-[16px] text-secondary">page_info</span>
                            Halaman {{ pjuData.current_page }} dari {{ pjuData.last_page }}
                        </span>
                    </div>
                </div>

                <!-- Table Filters (Meterisasi & Survei) -->
                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="flex items-center gap-2 bg-rose-50/40 dark:bg-rose-950/20 px-3 py-1.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30">
                        <span class="material-symbols-outlined text-secondary text-[16px]">bolt</span>
                        <span class="font-caption text-[11px] text-gray-600 dark:text-gray-300 font-bold uppercase tracking-wider mr-2">Filter Meterisasi:</span>
                        <select v-model="meterisasi" class="bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0">
                            <option value="all">Semua Status</option>
                            <option value="SUDAH">Sudah Meterisasi</option>
                            <option value="BELUM">Belum Meterisasi</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 bg-rose-50/40 dark:bg-rose-950/20 px-3 py-1.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30">
                        <span class="material-symbols-outlined text-secondary text-[16px]">fact_check</span>
                        <span class="font-caption text-[11px] text-gray-600 dark:text-gray-300 font-bold uppercase tracking-wider mr-2">Filter Survei:</span>
                        <select v-model="survei" class="bg-transparent font-label-md text-xs font-bold text-gray-900 dark:text-white focus:outline-none cursor-pointer border-none p-0 focus:ring-0">
                            <option value="all">Semua Status</option>
                            <option value="SUDAH">Sudah Survei</option>
                            <option value="BELUM">Belum Survei</option>
                        </select>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto rounded-2xl border border-rose-100/70 dark:border-rose-950/50 shadow-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-rose-50/60 dark:bg-rose-950/30 text-gray-600 dark:text-gray-400 font-caption text-[11px] uppercase tracking-wider border-b border-rose-100/80">
                                <th class="p-3.5 pl-4 font-bold w-20">No Urut</th>
                                <th class="p-3.5 font-bold w-40">IDPEL PLN</th>
                                <th class="p-3.5 font-bold">Lokasi / Alamat Ruas Jalan</th>
                                <th class="p-3.5 font-bold w-36">Jenis Lampu</th>
                                <th class="p-3.5 font-bold w-36">Besar Daya</th>
                                <th class="p-3.5 font-bold w-44">Skema Meterisasi</th>
                                <th class="p-3.5 pr-4 font-bold w-20 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100/50 dark:divide-rose-950/30 font-body-sm text-xs text-gray-800 dark:text-gray-200 bg-white/50 dark:bg-gray-900/50">
                            <tr 
                                v-for="item in pjuData.data" 
                                :key="item['NO URUT'] || item['IDPEL'] + Math.random()"
                                class="hover:bg-rose-50/40 dark:hover:bg-rose-950/20 transition-colors"
                            >
                                <!-- No Urut -->
                                <td class="p-3.5 pl-4 font-telemetry-unit font-bold text-gray-500 dark:text-gray-400">
                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 border border-gray-200/50 dark:border-gray-700/50">
                                        #{{ item['NO URUT'] }}
                                    </span>
                                </td>

                                <!-- IDPEL PLN -->
                                <td class="p-3.5">
                                    <span v-if="item['IDPEL']" class="font-telemetry font-bold text-primary dark:text-rose-300 bg-rose-50 dark:bg-rose-950/50 px-2.5 py-1 rounded-lg border border-rose-200/60 dark:border-rose-900/40">
                                        {{ item['IDPEL'] }}
                                    </span>
                                    <span v-else class="text-gray-400 italic">-</span>
                                </td>

                                <!-- Alamat -->
                                <td class="p-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px] text-gray-400 flex-shrink-0">location_on</span>
                                        <span class="font-medium text-gray-900 dark:text-white leading-relaxed">
                                            {{ item['ALAMAT'] || 'Alamat tidak terdata' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Jenis Lampu -->
                                <td class="p-3.5">
                                    <span 
                                        v-if="item['JENIS LAMPU'] === 'LED'" 
                                        class="inline-flex items-center gap-1 text-emerald-700 dark:text-emerald-300 font-bold bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded-full text-[11px] border border-emerald-200/60 dark:border-emerald-800/40"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        LED
                                    </span>
                                    <span 
                                        v-else-if="item['JENIS LAMPU'] === 'BALLAS'" 
                                        class="inline-flex items-center gap-1 text-rose-700 dark:text-rose-300 font-bold bg-rose-50 dark:bg-rose-950/40 px-2.5 py-1 rounded-full text-[11px] border border-rose-200/60 dark:border-rose-800/40"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        BALLAS
                                    </span>
                                    <span 
                                        v-else 
                                        class="inline-flex items-center gap-1 text-gray-700 dark:text-gray-300 font-medium bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-full text-[11px]"
                                    >
                                        {{ item['JENIS LAMPU'] || '-' }}
                                    </span>
                                </td>

                                <!-- Besar Daya -->
                                <td class="p-3.5 font-telemetry font-bold text-gray-900 dark:text-white">
                                    <div class="flex items-center gap-1">
                                        <span class="text-amber-600 dark:text-amber-400">{{ item['BESAR DAYA'] }}</span>
                                        <span class="font-telemetry-unit text-[11px] text-gray-400 font-normal">W</span>
                                    </div>
                                </td>

                                <!-- Skema Meterisasi -->
                                <td class="p-3.5">
                                    <span 
                                        v-if="item['METER/TERSEBAR'] === 'METER'" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 font-caption text-[11px] font-bold border border-emerald-200/60 dark:border-emerald-800/40"
                                    >
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                        Meterisasi
                                    </span>
                                    <span 
                                        v-else 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 font-caption text-[11px] font-bold border border-amber-200/60 dark:border-amber-800/40"
                                    >
                                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                        Abonemen
                                    </span>
                                </td>

                                <!-- Aksi -->
                                <td class="p-3.5 pr-4 text-center">
                                    <Link 
                                        :href="route('dashboard.show', item['ID'])"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-secondary hover:bg-secondary hover:text-white transition-all shadow-sm"
                                        title="Lihat Detail PJU"
                                    >
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </Link>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="!pjuData.data || pjuData.data.length === 0">
                                <td colspan="7" class="py-16 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-secondary flex items-center justify-center">
                                            <span class="material-symbols-outlined text-[28px]">search_off</span>
                                        </div>
                                        <div>
                                            <p class="font-headline-sm text-base font-bold text-gray-900 dark:text-white">Tidak ada data ditemukan</p>
                                            <p class="font-body-sm text-xs text-gray-500 mt-1">Coba sesuaikan kata kunci pencarian atau reset filter di atas.</p>
                                        </div>
                                        <button 
                                            @click="resetFilters" 
                                            class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-white text-xs font-bold hover:bg-secondary transition-colors"
                                            type="button"
                                        >
                                            <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                                            Reset Semua Filter
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Component -->
                <div v-if="pjuData.links && pjuData.links.length > 3" class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-rose-100/60 dark:border-rose-950/40">
                    <span class="font-caption text-xs text-gray-500 dark:text-gray-400">
                        Menampilkan <strong class="text-gray-900 dark:text-white">{{ pjuData.from || 0 }}</strong> sampai <strong class="text-gray-900 dark:text-white">{{ pjuData.to || 0 }}</strong> dari <strong class="text-gray-900 dark:text-white">{{ formatNumber(pjuData.total) }}</strong> data
                    </span>

                    <div class="flex flex-wrap items-center gap-1">
                        <Link
                            v-for="(link, idx) in pjuData.links"
                            :key="idx"
                            :href="link.url || '#'"
                            preserve-scroll
                            preserve-state
                            class="min-w-[36px] h-9 px-3 flex items-center justify-center rounded-xl font-label-md text-xs font-bold transition-all duration-150"
                            :class="[
                                link.active 
                                    ? 'bg-gradient-to-r from-primary via-secondary to-primary-container text-white shadow-sm shadow-primary/30' 
                                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-rose-100/60 dark:border-rose-900/40',
                                !link.url && 'opacity-40 cursor-not-allowed pointer-events-none'
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </section>
        </main>
    </DashboardLayout>
</template>
