<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    idpels: Object,
    units: Array,
    kpis: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status || 'all');
const unitPln = ref(props.filters.unit_pln || 'all');

let searchTimeout = null;

const applyFilters = () => {
    router.get(
        route('idpels.index'),
        {
            search: search.value,
            status: status.value,
            unit_pln: unitPln.value
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true
        }
    );
};

watch(search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 350);
});

watch([status, unitPln], () => {
    applyFilters();
});

const setStatus = (newStatus) => {
    status.value = newStatus;
};

const formatNumber = (val) => {
    return new Intl.NumberFormat('id-ID').format(val || 0);
};
</script>

<template>
    <Head title="Data IDPEL & Meterisasi - SIPIJAR" />

    <DashboardLayout>
        <main class="w-full pt-20 pb-12 bg-transparent px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
            
            <!-- Page Header -->
            <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary via-secondary to-primary-container flex items-center justify-center text-white shadow-md shadow-primary/25 flex-shrink-0">
                        <span class="material-symbols-outlined text-[26px]">electric_meter</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="font-headline-lg text-2xl font-extrabold text-gray-950 dark:text-white tracking-tight">Master Data IDPEL PLN</h1>
                        <p class="font-body-sm text-sm text-gray-500 dark:text-gray-400">Data ID Pelanggan dan status meterisasi bersumber dari tabel spreadsheet PJU Kota Tanjungpinang.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Link :href="route('dashboard')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-primary dark:text-rose-300 font-label-md text-xs font-bold border border-rose-100 dark:border-rose-900/40 hover:bg-rose-100/70 transition-all">
                        <span class="material-symbols-outlined text-[18px]">dashboard</span>
                        Buka Dashboard
                    </Link>
                </div>
            </section>

            <!-- KPI Summary Cards with Quick Filter -->
            <section class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <!-- Total IDPEL Card -->
                <button 
                    type="button"
                    @click="setStatus('all')" 
                    class="text-left bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 rounded-3xl shadow-sm border transition-all duration-200 hover:-translate-y-0.5"
                    :class="status === 'all' ? 'border-primary ring-2 ring-primary/20 bg-rose-50/20' : 'border-rose-100/70 dark:border-rose-950/50 hover:border-rose-300'"
                >
                    <div class="flex items-center justify-between">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total IDPEL Terdaftar</span>
                        <span class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-primary flex items-center justify-center border border-rose-100">
                            <span class="material-symbols-outlined text-[20px]">electric_meter</span>
                        </span>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="font-telemetry-data text-3xl font-extrabold text-gray-950 dark:text-white">
                            {{ formatNumber(kpis.total) }}
                            <span class="text-xs font-sans text-gray-500 dark:text-gray-400 font-normal">IDPEL</span>
                        </div>
                        <span v-if="status === 'all'" class="px-2 py-0.5 rounded-md bg-primary/10 text-primary font-caption text-[11px] font-bold">
                            Aktif
                        </span>
                    </div>
                    <p class="font-caption text-[11px] text-gray-500 mt-2">Seluruh titik meter dan abonemen PJU</p>
                </button>

                <!-- Meter IDPEL Card -->
                <button 
                    type="button"
                    @click="setStatus('meter')" 
                    class="text-left bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 rounded-3xl shadow-sm border transition-all duration-200 hover:-translate-y-0.5"
                    :class="status === 'meter' ? 'border-emerald-500 ring-2 ring-emerald-500/20 bg-emerald-50/20' : 'border-rose-100/70 dark:border-rose-950/50 hover:border-emerald-300'"
                >
                    <div class="flex items-center justify-between">
                        <span class="font-caption text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">IDPEL Meter (Digital)</span>
                        <span class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 flex items-center justify-center border border-emerald-100">
                            <span class="material-symbols-outlined text-[20px]">bolt</span>
                        </span>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="font-telemetry-data text-3xl font-extrabold text-emerald-700 dark:text-emerald-400">
                            {{ formatNumber(kpis.meter) }}
                            <span class="text-xs font-sans text-gray-500 dark:text-gray-400 font-normal">IDPEL</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-emerald-100/80 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 font-caption text-[11px] font-bold">
                            {{ kpis.total ? Math.round((kpis.meter / kpis.total) * 100) : 0 }}%
                        </span>
                    </div>
                    <p class="font-caption text-[11px] text-gray-500 mt-2">Tagihan berdasarkan kwh meter real</p>
                </button>

                <!-- Non-Meter IDPEL Card -->
                <button 
                    type="button"
                    @click="setStatus('non-meter')" 
                    class="text-left bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-5 rounded-3xl shadow-sm border transition-all duration-200 hover:-translate-y-0.5"
                    :class="status === 'non-meter' ? 'border-amber-500 ring-2 ring-amber-500/20 bg-amber-50/20' : 'border-rose-100/70 dark:border-rose-950/50 hover:border-amber-300'"
                >
                    <div class="flex items-center justify-between">
                        <span class="font-caption text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider">IDPEL Non-Meter (Abonemen)</span>
                        <span class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 flex items-center justify-center border border-amber-100">
                            <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                        </span>
                    </div>
                    <div class="mt-3 flex items-baseline justify-between">
                        <div class="font-telemetry-data text-3xl font-extrabold text-amber-700 dark:text-amber-400">
                            {{ formatNumber(kpis.non_meter) }}
                            <span class="text-xs font-sans text-gray-500 dark:text-gray-400 font-normal">IDPEL</span>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-amber-100/80 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 font-caption text-[11px] font-bold">
                            {{ kpis.total ? Math.round((kpis.non_meter / kpis.total) * 100) : 0 }}%
                        </span>
                    </div>
                    <p class="font-caption text-[11px] text-gray-500 mt-2">Target program percepatan meterisasi</p>
                </button>
            </section>

            <!-- Table & Filters Section -->
            <section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col gap-5">
                
                <!-- Filter Controls Bar -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                    
                    <!-- Search Input -->
                    <div class="flex items-center gap-2 bg-rose-50/50 dark:bg-rose-950/20 px-3.5 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 flex-1">
                        <span class="material-symbols-outlined text-secondary text-[20px]">search</span>
                        <input 
                            v-model="search" 
                            type="text" 
                            placeholder="Cari IDPEL, Alamat jalan, atau Unit PLN..." 
                            class="bg-transparent font-label-md text-sm text-gray-900 dark:text-white focus:outline-none w-full border-none p-0 focus:ring-0" 
                        />
                        <button v-if="search" @click="search = ''" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>

                    <!-- Filter Status Meter Tabs / Selector -->
                    <div class="flex items-center gap-2 bg-rose-50/50 dark:bg-rose-950/20 px-3.5 py-2 rounded-xl border border-rose-100/60 dark:border-rose-900/30">
                        <span class="material-symbols-outlined text-secondary text-[18px]">tune</span>
                        <span class="font-caption text-[11px] text-gray-600 dark:text-gray-400 font-bold uppercase tracking-wider whitespace-nowrap">Filter Status:</span>
                        <select 
                            v-model="status" 
                            class="bg-transparent font-label-md text-sm font-semibold text-gray-900 dark:text-white focus:outline-none border-none p-0 focus:ring-0 cursor-pointer"
                        >
                            <option value="all">Semua IDPEL ({{ formatNumber(kpis.total) }})</option>
                            <option value="meter">Meter Digital ({{ formatNumber(kpis.meter) }})</option>
                            <option value="non-meter">Non-Meter / Tersebar ({{ formatNumber(kpis.non_meter) }})</option>
                        </select>
                    </div>

                    <!-- Filter Unit PLN -->
                    <div class="flex items-center gap-2 bg-rose-50/50 dark:bg-rose-950/20 px-3.5 py-2 rounded-xl border border-rose-100/60 dark:border-rose-900/30 w-full md:w-64">
                        <span class="material-symbols-outlined text-secondary text-[18px]">apartment</span>
                        <select 
                            v-model="unitPln" 
                            class="bg-transparent font-label-md text-sm font-semibold text-gray-900 dark:text-white focus:outline-none w-full border-none p-0 focus:ring-0 cursor-pointer"
                        >
                            <option value="all">Semua Unit PLN</option>
                            <option v-for="unit in units" :key="unit" :value="unit">Unit {{ unit }}</option>
                        </select>
                    </div>
                </div>

                <!-- Table Header Metadata -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs text-gray-500 dark:text-gray-400 px-1">
                    <div>
                        Menampilkan <span class="font-bold text-gray-800 dark:text-gray-200">{{ idpels.from || 0 }}</span> sampai <span class="font-bold text-gray-800 dark:text-gray-200">{{ idpels.to || 0 }}</span> dari <span class="font-bold text-gray-800 dark:text-gray-200">{{ formatNumber(idpels.total) }}</span> IDPEL
                        <span v-if="status === 'meter'" class="ml-1 text-emerald-600 font-semibold">(Filter: Meter)</span>
                        <span v-else-if="status === 'non-meter'" class="ml-1 text-amber-600 font-semibold">(Filter: Non-Meter)</span>
                    </div>
                    <div class="text-gray-400 font-caption">
                        Halaman {{ idpels.current_page }} dari {{ idpels.last_page }}
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto rounded-2xl border border-rose-100/70 dark:border-rose-950/50 shadow-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-rose-50/60 dark:bg-rose-950/30 text-gray-600 dark:text-gray-400 font-caption text-[11px] uppercase tracking-wider border-b border-rose-100/80">
                                <th class="p-4 font-bold">Nomor IDPEL PLN</th>
                                <th class="p-4 font-bold">Lokasi / Ruas Jalan</th>
                                <th class="p-4 font-bold">Unit PLN</th>
                                <th class="p-4 font-bold">Beban &amp; Titik Lampu</th>
                                <th class="p-4 font-bold">Status Meter</th>
                                <th class="p-4 font-bold">Meterisasi / Survei</th>
                                <th class="p-4 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100/50 dark:divide-rose-950/30 font-body-sm text-sm text-gray-800 dark:text-gray-200 bg-white/50 dark:bg-gray-900/50">
                            <tr v-for="idpel in idpels.data" :key="idpel.idpel" class="hover:bg-rose-50/40 dark:hover:bg-rose-950/20 transition-colors">
                                <!-- IDPEL Number -->
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" :class="idpel.status_meter === 'METER' ? 'bg-emerald-500' : 'bg-amber-500'"></span>
                                        <span class="font-telemetry-unit font-bold text-primary dark:text-rose-400 tracking-wide text-sm bg-rose-50/80 dark:bg-rose-950/40 px-2.5 py-1 rounded-lg border border-rose-100 dark:border-rose-900/40">
                                            {{ idpel.idpel }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Alamat -->
                                <td class="p-4">
                                    <div class="flex items-center gap-1.5 text-gray-800 dark:text-gray-200">
                                        <span class="material-symbols-outlined text-[16px] text-gray-400 flex-shrink-0">location_on</span>
                                        <span class="font-medium line-clamp-1 max-w-xs" :title="idpel.alamat || '-'">
                                            {{ idpel.alamat || '-' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Unit PLN -->
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-cyan-50 dark:bg-cyan-950/40 text-cyan-700 dark:text-cyan-300 font-caption text-[11px] font-bold border border-cyan-100/60 dark:border-cyan-900/40">
                                        {{ idpel.unit_pln || '-' }}
                                    </span>
                                </td>

                                <!-- Beban & Titik Lampu -->
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            {{ formatNumber(idpel.total_titik) }} Titik Lampu
                                        </span>
                                        <span class="font-caption text-[11px] text-gray-500 dark:text-gray-400">
                                            {{ formatNumber(idpel.total_daya) }} VA
                                        </span>
                                    </div>
                                </td>

                                <!-- Status Meter -->
                                <td class="p-4">
                                    <span v-if="idpel.status_meter === 'METER'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 font-caption text-[11px] font-bold border border-emerald-200/80 dark:border-emerald-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Meter Digital
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-300 font-caption text-[11px] font-bold border border-amber-200/80 dark:border-amber-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Non-Meter (Abonemen)
                                    </span>
                                </td>

                                <!-- Meterisasi & Survei -->
                                <td class="p-4">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span 
                                            class="px-2 py-0.5 rounded text-[10px] font-bold font-caption uppercase"
                                            :class="idpel.meterisasi === 'SUDAH' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'"
                                        >
                                            Meter: {{ idpel.meterisasi || 'BELUM' }}
                                        </span>
                                        <span 
                                            class="px-2 py-0.5 rounded text-[10px] font-bold font-caption uppercase"
                                            :class="idpel.survei === 'SUDAH' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'"
                                        >
                                            Survei: {{ idpel.survei || 'BELUM' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <!-- Link to dashboard with search=idpel to view all lamps under this IDPEL -->
                                        <Link 
                                            :href="route('dashboard', { search: idpel.idpel })" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-rose-50 text-primary dark:bg-rose-950/40 dark:text-rose-300 hover:bg-rose-100 transition-colors font-caption text-xs font-bold"
                                            title="Lihat Daftar Titik Lampu IDPEL ini"
                                        >
                                            <span class="material-symbols-outlined text-[16px]">visibility</span>
                                            <span>Titik PJU</span>
                                        </Link>

                                        <!-- Google Maps link if coordinates exist -->
                                        <a 
                                            v-if="idpel.lat && idpel.lng"
                                            :href="`https://maps.google.com/?q=${idpel.lat},${idpel.lng}`" 
                                            target="_blank" 
                                            class="inline-flex items-center justify-center p-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 transition-colors"
                                            title="Buka Peta"
                                        >
                                            <span class="material-symbols-outlined text-[16px]">map</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!idpels.data || idpels.data.length === 0">
                                <td colspan="7" class="p-8 text-center text-gray-500 font-caption">
                                    <span class="material-symbols-outlined text-[32px] text-gray-400 block mb-1">search_off</span>
                                    Tidak ada data IDPEL yang cocok dengan filter.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div v-if="idpels.links && idpels.links.length > 3" class="flex flex-wrap items-center justify-center gap-1 mt-4">
                    <Link 
                        v-for="(link, k) in idpels.links" 
                        :key="k" 
                        :href="link.url || '#'" 
                        class="px-3 py-1.5 rounded-xl font-caption text-xs font-semibold transition-all border shadow-xs"
                        :class="[
                            link.active 
                                ? 'bg-gradient-to-r from-primary to-secondary text-white border-transparent' 
                                : 'bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-300 border-rose-100',
                            !link.url ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''
                        ]"
                        v-html="link.label" 
                    />
                </div>
            </section>
        </main>
    </DashboardLayout>
</template>
