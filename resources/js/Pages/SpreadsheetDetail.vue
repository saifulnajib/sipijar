<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    pju: Object
});

const formatString = (val) => val ? val : '-';
</script>

<template>
    <Head title="Detail Titik PJU" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950 font-body p-6 sm:p-10 text-gray-900 dark:text-gray-100">
        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Header section -->
            <div class="flex items-center justify-between bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <Link :href="route('dashboard')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </Link>
                    <div>
                        <h1 class="font-headline text-2xl font-extrabold tracking-tight">Detail Titik PJU</h1>
                        <p class="font-caption text-sm text-gray-500">ID Pelanggan: <span class="font-telemetry font-bold text-primary dark:text-rose-400">{{ formatString(pju.idpel) }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Detail Grid -->
            <div class="bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    
                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">No Urut</span>
                        <div class="font-body font-medium text-lg text-gray-900 dark:text-white">{{ formatString(pju.no_urut) }}</div>
                    </div>
                    
                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Alamat</span>
                        <div class="font-body font-medium text-lg text-gray-900 dark:text-white">{{ formatString(pju.alamat) }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Jenis Lampu</span>
                        <div class="font-body font-medium text-lg text-gray-900 dark:text-white">{{ formatString(pju.jenis_lampu) }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Besar Daya (VA)</span>
                        <div class="font-body font-medium text-lg text-gray-900 dark:text-white">{{ formatString(pju.besar_daya) }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Status Meter</span>
                        <div class="font-body font-medium text-lg">
                            <span v-if="pju.status_meter === 'METER'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold border border-emerald-200">
                                Meterisasi
                            </span>
                            <span v-else class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-sm font-bold border border-amber-200">
                                {{ pju.status_meter || 'Abonemen' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Unit PLN</span>
                        <div class="font-body font-medium text-lg text-gray-900 dark:text-white">{{ formatString(pju.unit_pln) }}</div>
                    </div>

                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Bentuk / Tiang</span>
                        <div class="font-body font-medium text-lg text-gray-900 dark:text-white">
                            {{ formatString(pju.bentuk) }} / {{ formatString(pju.tiang) }}
                        </div>
                    </div>

                    <div class="space-y-1">
                        <span class="font-caption text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Koordinat Lokasi</span>
                        <div class="font-body font-medium text-lg text-gray-900 dark:text-white">
                            <span v-if="pju.lat && pju.lng">
                                <a :href="`https://maps.google.com/?q=${pju.lat},${pju.lng}`" target="_blank" class="text-blue-600 hover:underline">
                                    {{ pju.lat }}, {{ pju.lng }}
                                </a>
                            </span>
                            <span v-else>-</span>
                        </div>
                    </div>

                    <div class="space-y-1 md:col-span-2 mt-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <h3 class="font-headline text-lg font-bold mb-4">Informasi Survei & Tindak Lanjut</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
                                <span class="font-caption text-xs font-bold text-gray-500 uppercase block mb-1">Status Survei</span>
                                <span class="font-medium" :class="pju.survei === 'SUDAH' ? 'text-indigo-600' : 'text-gray-600'">
                                    {{ formatString(pju.survei) }}
                                </span>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
                                <span class="font-caption text-xs font-bold text-gray-500 uppercase block mb-1">Meterisasi</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ formatString(pju.meterisasi) }}</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
                                <span class="font-caption text-xs font-bold text-gray-500 uppercase block mb-1">Status Lampu</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ formatString(pju.status_lampu) }}</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50">
                                <span class="font-caption text-xs font-bold text-gray-500 uppercase block mb-1">Tindak Lanjut & Kebutuhan</span>
                                <span class="font-medium text-gray-900 dark:text-white block">{{ formatString(pju.tindak_lanjut) }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400 mt-1 block">{{ formatString(pju.kebutuhan_material) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
