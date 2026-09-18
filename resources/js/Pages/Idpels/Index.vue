<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    idpels: Object,
    districts: Array,
    filters: Object
});

const search = ref(props.filters.search || '');
const district = ref(props.filters.district || 'all');

watch(search, (value) => {
    router.get(route('idpels.index'), { search: value, district: district.value }, { preserveState: true, replace: true, preserveScroll: true });
});

watch(district, (value) => {
    router.get(route('idpels.index'), { search: search.value, district: value }, { preserveState: true, replace: true, preserveScroll: true });
});

const destroy = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus data IDPEL ini?')) {
        router.delete(route('idpels.destroy', id));
    }
};
</script>

<template>
    <Head title="Manajemen IDPEL - SIPIJAR" />

    <DashboardLayout>
        <main class="w-full pt-20 pb-12 bg-transparent px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
            
            <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50">
                <div class="flex flex-col">
                    <h1 class="font-headline-lg text-2xl font-extrabold text-gray-950 dark:text-white tracking-tight">Master Data IDPEL PLN</h1>
                    <p class="font-body-sm text-sm text-gray-500 dark:text-gray-400">Manajemen titik pelanggan Gardu dan Panel PJU Kota Tanjungpinang.</p>
                </div>
                <Link :href="route('idpels.create')" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary via-secondary to-primary-container hover:from-primary-container hover:to-primary text-white px-5 py-2.5 rounded-xl font-label-md text-sm font-bold shadow-md shadow-primary/20 hover:shadow-lg transition-all">
                    <span class="material-symbols-outlined text-[18px]">add</span> Tambah IDPEL
                </Link>
            </section>

            <section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50 flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex items-center gap-2 bg-rose-50/50 dark:bg-rose-950/20 px-3.5 py-2 rounded-xl border border-rose-100/60 dark:border-rose-900/30 flex-1">
                        <span class="material-symbols-outlined text-secondary text-[20px]">search</span>
                        <input v-model="search" type="text" placeholder="Cari IDPEL atau Nama Gardu..." class="bg-transparent font-label-md text-sm text-gray-900 dark:text-white focus:outline-none w-full border-none p-0 focus:ring-0" />
                    </div>
                    <div class="flex items-center gap-2 bg-rose-50/50 dark:bg-rose-950/20 px-3.5 py-2 rounded-xl border border-rose-100/60 dark:border-rose-900/30 w-full sm:w-64">
                        <span class="material-symbols-outlined text-secondary text-[20px]">map</span>
                        <select v-model="district" class="bg-transparent font-label-md text-sm text-gray-900 dark:text-white focus:outline-none w-full border-none p-0 focus:ring-0 cursor-pointer">
                            <option value="all">Semua Kecamatan</option>
                            <option v-for="d in districts" :key="d.id" :value="d.id">{{ d.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-rose-100/70 dark:border-rose-950/50 shadow-xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-rose-50/60 dark:bg-rose-950/30 text-gray-600 dark:text-gray-400 font-caption text-[11px] uppercase tracking-wider border-b border-rose-100/80">
                                <th class="p-4 font-bold">IDPEL / Gardu</th>
                                <th class="p-4 font-bold">Lokasi</th>
                                <th class="p-4 font-bold">Status</th>
                                <th class="p-4 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-rose-100/50 dark:divide-rose-950/30 font-body-sm text-sm text-gray-800 dark:text-gray-200">
                            <tr v-for="idpel in idpels.data" :key="idpel.id" class="hover:bg-rose-50/40 dark:hover:bg-rose-950/20 transition-colors">
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="font-telemetry-unit font-bold text-primary">{{ idpel.idpel_number }}</span>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ idpel.name }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="text-gray-700 dark:text-gray-300">{{ idpel.address || '-' }}</span>
                                        <span class="font-caption text-[11px] text-gray-500">{{ idpel.district?.name || '-' }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span v-if="idpel.status === 'meterisasi'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-caption text-[11px] font-bold border border-emerald-200">
                                        Meter Digital
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 font-caption text-[11px] font-bold border border-amber-200">
                                        Abonemen Flat
                                    </span>
                                </td>
                                <td class="p-4 text-right space-x-2">
                                    <Link :href="route('idpels.edit', idpel.id)" class="inline-flex items-center justify-center p-2 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </Link>
                                    <button @click="destroy(idpel.id)" type="button" class="inline-flex items-center justify-center p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="idpels.data.length === 0">
                                <td colspan="4" class="p-6 text-center text-gray-500">Tidak ada data ditemukan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div v-if="idpels.links.length > 3" class="flex items-center justify-center gap-1 mt-4">
                    <Link v-for="(link, k) in idpels.links" :key="k" :href="link.url" 
                        class="px-3 py-1.5 rounded-xl font-caption text-xs font-semibold transition-all border shadow-xs"
                        :class="[
                            link.active 
                                ? 'bg-gradient-to-r from-primary to-secondary text-white border-transparent' 
                                : 'bg-white dark:bg-gray-800 hover:bg-rose-50 text-gray-700 dark:text-gray-300 border-rose-100',
                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                        v-html="link.label" />
                </div>
            </section>
        </main>
    </DashboardLayout>
</template>
