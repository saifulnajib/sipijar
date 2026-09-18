<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    districts: Array,
});

const form = useForm({
    idpel_number: '',
    name: '',
    address: '',
    district_id: '',
    status: 'meterisasi',
    lat: '',
    lng: '',
});

const submit = () => {
    form.post(route('idpels.store'));
};
</script>

<template>
    <Head title="Tambah IDPEL - SIPIJAR" />

    <DashboardLayout>
        <main class="w-full pt-20 pb-12 bg-transparent px-4 sm:px-6 lg:px-8 max-w-3xl mx-auto space-y-8">
            
            <section class="flex items-center gap-4 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50">
                <Link :href="route('idpels.index')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </Link>
                <div class="flex flex-col">
                    <h1 class="font-headline-lg text-2xl font-extrabold text-gray-950 dark:text-white tracking-tight">Tambah Data IDPEL</h1>
                    <p class="font-body-sm text-sm text-gray-500 dark:text-gray-400">Registrasi pelanggan gardu PLN baru.</p>
                </div>
            </section>

            <section class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl p-6 lg:p-8 rounded-3xl shadow-sm border border-rose-100/70 dark:border-rose-950/50">
                <form @submit.prevent="submit" class="space-y-6">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Nomor IDPEL -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-caption text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Nomor IDPEL PLN</label>
                            <input v-model="form.idpel_number" type="text" class="w-full bg-rose-50/40 dark:bg-rose-950/20 px-4 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 focus:border-primary focus:ring-1 focus:ring-primary transition-all font-label-md text-sm text-gray-900 dark:text-white" placeholder="Contoh: 140000000001" required />
                            <div v-if="form.errors.idpel_number" class="text-xs text-rose-600 font-bold mt-1">{{ form.errors.idpel_number }}</div>
                        </div>

                        <!-- Nama Gardu -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-caption text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Nama Gardu / Panel</label>
                            <input v-model="form.name" type="text" class="w-full bg-rose-50/40 dark:bg-rose-950/20 px-4 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 focus:border-primary focus:ring-1 focus:ring-primary transition-all font-label-md text-sm text-gray-900 dark:text-white" placeholder="Contoh: Panel PJU Arteri 1" required />
                            <div v-if="form.errors.name" class="text-xs text-rose-600 font-bold mt-1">{{ form.errors.name }}</div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-caption text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Alamat Lengkap</label>
                        <textarea v-model="form.address" rows="3" class="w-full bg-rose-50/40 dark:bg-rose-950/20 px-4 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 focus:border-primary focus:ring-1 focus:ring-primary transition-all font-label-md text-sm text-gray-900 dark:text-white" placeholder="Nama Jalan, Patokan..."></textarea>
                        <div v-if="form.errors.address" class="text-xs text-rose-600 font-bold mt-1">{{ form.errors.address }}</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Kecamatan -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-caption text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Kecamatan</label>
                            <select v-model="form.district_id" class="w-full bg-rose-50/40 dark:bg-rose-950/20 px-4 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 focus:border-primary focus:ring-1 focus:ring-primary transition-all font-label-md text-sm text-gray-900 dark:text-white cursor-pointer" required>
                                <option value="" disabled>Pilih Kecamatan</option>
                                <option v-for="d in districts" :key="d.id" :value="d.id">{{ d.name }}</option>
                            </select>
                            <div v-if="form.errors.district_id" class="text-xs text-rose-600 font-bold mt-1">{{ form.errors.district_id }}</div>
                        </div>

                        <!-- Status Meterisasi -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-caption text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Status Meterisasi</label>
                            <select v-model="form.status" class="w-full bg-rose-50/40 dark:bg-rose-950/20 px-4 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 focus:border-primary focus:ring-1 focus:ring-primary transition-all font-label-md text-sm text-gray-900 dark:text-white cursor-pointer">
                                <option value="meterisasi">Meter Digital (KWH Terukur)</option>
                                <option value="abonemen">Abonemen (Non-Meter Flat)</option>
                            </select>
                            <div v-if="form.errors.status" class="text-xs text-rose-600 font-bold mt-1">{{ form.errors.status }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Latitude -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-caption text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Latitude (Koordinat)</label>
                            <input v-model="form.lat" type="text" class="w-full bg-rose-50/40 dark:bg-rose-950/20 px-4 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 focus:border-primary focus:ring-1 focus:ring-primary transition-all font-label-md text-sm text-gray-900 dark:text-white" placeholder="-0.893112" />
                            <div v-if="form.errors.lat" class="text-xs text-rose-600 font-bold mt-1">{{ form.errors.lat }}</div>
                        </div>

                        <!-- Longitude -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-caption text-xs font-bold text-gray-700 dark:text-gray-300 uppercase">Longitude (Koordinat)</label>
                            <input v-model="form.lng" type="text" class="w-full bg-rose-50/40 dark:bg-rose-950/20 px-4 py-2.5 rounded-xl border border-rose-100/60 dark:border-rose-900/30 focus:border-primary focus:ring-1 focus:ring-primary transition-all font-label-md text-sm text-gray-900 dark:text-white" placeholder="104.45321" />
                            <div v-if="form.errors.lng" class="text-xs text-rose-600 font-bold mt-1">{{ form.errors.lng }}</div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <Link :href="route('idpels.index')" class="px-5 py-2.5 rounded-xl font-label-md text-sm font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                            Batal
                        </Link>
                        <button type="submit" :disabled="form.processing" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-primary via-secondary to-primary-container hover:from-primary-container hover:to-primary text-white px-6 py-2.5 rounded-xl font-label-md text-sm font-bold shadow-md shadow-primary/20 hover:shadow-lg transition-all disabled:opacity-70">
                            <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                            Simpan Data
                        </button>
                    </div>

                </form>
            </section>
        </main>
    </DashboardLayout>
</template>
