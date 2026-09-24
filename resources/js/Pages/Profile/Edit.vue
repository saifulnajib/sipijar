<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
</script>

<template>
    <Head title="Profil Pengguna - SI-PIJAR" />

    <DashboardLayout>
        <div class="py-6 sm:py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full space-y-6">
            
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                <Link :href="route('dashboard')" class="hover:text-primary dark:hover:text-rose-400 transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">dashboard</span>
                    <span>Beranda</span>
                </Link>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-gray-900 dark:text-white font-bold">Profil Akun Petugas</span>
            </nav>

            <!-- Hero Profile Banner Card -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary via-secondary to-[#4c0f17] text-white shadow-xl shadow-rose-950/20 p-6 sm:p-8 border border-white/10">
                <!-- Background Ambient Glow & Patterns -->
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-rose-400/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute right-1/3 -bottom-20 w-60 h-60 bg-amber-400/15 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <!-- Left: Avatar & User Identity -->
                    <div class="flex items-center gap-5">
                        <!-- Big Avatar with Glow Ring -->
                        <div class="relative flex-shrink-0">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/20 backdrop-blur-md border-2 border-white/40 flex items-center justify-center text-white text-3xl sm:text-4xl font-black shadow-lg">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-xl bg-emerald-500 border-2 border-primary flex items-center justify-center text-white shadow-xs" title="Akun Terverifikasi">
                                <span class="material-symbols-outlined text-[16px] font-bold">check</span>
                            </div>
                        </div>

                        <!-- Info details -->
                        <div class="flex flex-col">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                                    {{ user.name }}
                                </h1>
                                <span class="px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-md text-[11px] font-bold text-rose-100 uppercase tracking-wider border border-white/20">
                                    Super Operator
                                </span>
                            </div>
                            <p class="text-sm text-rose-100/90 mt-1 flex items-center gap-1.5 font-medium">
                                <span class="material-symbols-outlined text-[17px] text-rose-200">mail</span>
                                <span>{{ user.email }}</span>
                            </p>
                            <p class="text-xs text-rose-200/70 mt-1 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-rose-300">apartment</span>
                                <span>Dinas Perhubungan Kota Tanjungpinang</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right Quick Badges / Metrics -->
                    <div class="flex flex-wrap md:flex-col items-start md:items-end gap-2.5 pt-4 md:pt-0 border-t md:border-t-0 border-white/15">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold text-white">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>Akses: Administrator SCADA</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-black/20 backdrop-blur-md border border-white/10 text-xs font-medium text-rose-100">
                            <span class="material-symbols-outlined text-[16px] text-amber-300">verified_user</span>
                            <span>ID Petugas: OP-TPI-0{{ user.id }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Form Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column (Informasi Profil) - 6 Cols -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-7 shadow-xs border border-rose-100/80 dark:border-rose-950/70 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary to-secondary"></div>
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>

                    <!-- System & Security Specification Card -->
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-7 shadow-xs border border-rose-100/80 dark:border-rose-950/70">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-primary dark:text-rose-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">devices</span>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Status Sesi &amp; Perangkat</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Informasi autentikasi aktif saat ini</p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[18px]">laptop_mac</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Browser Aktif (Sesi Ini)</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold text-[10px] uppercase">
                                    Aktif
                                </span>
                            </div>

                            <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50/80 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-800 text-xs">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-primary dark:text-rose-400 text-[18px]">public</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Wilayah Otoritas</span>
                                </div>
                                <span class="font-semibold text-gray-900 dark:text-white">Kota Tanjungpinang</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Update Password & Danger Zone) - 6 Cols -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-7 shadow-xs border border-rose-100/80 dark:border-rose-950/70 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-rose-500"></div>
                        <UpdatePasswordForm />
                    </div>

                    <!-- Danger Zone Card -->
                    <div class="bg-rose-50/40 dark:bg-rose-950/20 rounded-3xl p-6 sm:p-7 shadow-xs border border-rose-200/80 dark:border-rose-900/40">
                        <DeleteUserForm />
                    </div>
                </div>

            </div>

        </div>
    </DashboardLayout>
</template>
