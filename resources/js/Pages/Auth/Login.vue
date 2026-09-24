<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
        default: true,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);
const showHelpModal = ref(false);
const currentTime = ref('');
let timer = null;

const updateClock = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    }) + ' WIB';
};

onMounted(() => {
    updateClock();
    timer = setInterval(updateClock, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk - SI-PIJAR Dishub Tanjungpinang" />

    <div class="min-h-screen bg-[#fcf8f8] dark:bg-[#0e0708] text-gray-900 dark:text-gray-100 flex flex-col justify-between selection:bg-rose-500 selection:text-white relative overflow-hidden font-body-md">
        
        <!-- Ambient Glowing Background Grid & Orbs -->
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-gradient-to-br from-rose-500/20 via-pink-500/15 to-transparent rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/3 -right-32 w-[30rem] h-[30rem] bg-gradient-to-bl from-amber-500/15 via-rose-600/10 to-transparent rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 left-1/3 w-[36rem] h-[36rem] bg-gradient-to-t from-primary/20 via-secondary/10 to-transparent rounded-full blur-3xl"></div>
            
            <!-- Subtle Tech Grid Overlay -->
            <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] bg-[linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        </div>

        <!-- Top Navigation Header -->
        <header class="relative z-10 w-full border-b border-rose-100/60 dark:border-rose-950/50 bg-white/70 dark:bg-gray-950/70 backdrop-blur-xl transition-all">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                <!-- Brand / Logo -->
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary via-secondary to-rose-600 flex items-center justify-center text-white shadow-lg shadow-rose-900/25 ring-2 ring-primary/20 flex-shrink-0">
                        <span class="material-symbols-outlined text-[26px]">wb_incandescent</span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="font-headline-sm text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight leading-none">
                                SI-PIJAR
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950/80 text-primary dark:text-rose-300 uppercase tracking-wider">
                                v2.0
                            </span>
                        </div>
                        <span class="font-caption text-xs font-semibold text-gray-500 dark:text-gray-400 mt-0.5">
                            Dinas Perhubungan Kota Tanjungpinang
                        </span>
                    </div>
                </div>

                <!-- Right System Status & Assistance -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- SCADA & Time Badge -->
                    <div class="hidden sm:flex items-center gap-3 px-3.5 py-1.5 rounded-full bg-white/90 dark:bg-gray-900/90 border border-rose-100 dark:border-rose-950/60 shadow-xs backdrop-blur-md">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">SCADA ONLINE</span>
                        <div class="h-3.5 w-px bg-gray-200 dark:bg-gray-800"></div>
                        <span class="font-telemetry-unit text-xs font-bold text-primary dark:text-rose-300">{{ currentTime }}</span>
                    </div>

                    <!-- Help Trigger Button -->
                    <button 
                        @click="showHelpModal = true"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 hover:text-primary dark:hover:text-rose-300 bg-white/80 dark:bg-gray-800/80 hover:bg-rose-50 dark:hover:bg-rose-950/50 border border-rose-100/80 dark:border-rose-900/40 shadow-xs transition-all"
                    >
                        <span class="material-symbols-outlined text-[18px]">help_outline</span>
                        <span class="hidden xs:inline">Panduan &amp; Bantuan</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Hero & Login Split Content -->
        <main class="relative z-10 flex-1 flex items-center py-10 lg:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    
                    <!-- LEFT COLUMN: Hero Section & Features (7 Cols) -->
                    <div class="lg:col-span-7 flex flex-col gap-6 lg:pr-4">
                        
                        <!-- Agency Tag Badge -->
                        <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-rose-100/90 to-amber-100/70 dark:from-rose-950/60 dark:to-amber-950/40 border border-rose-200/70 dark:border-rose-900/50 w-fit shadow-xs">
                            <span class="material-symbols-outlined text-[16px] text-primary dark:text-rose-400">location_city</span>
                            <span class="text-xs font-bold text-primary dark:text-rose-200 tracking-wide uppercase">
                                Smart City • Infrastruktur PJU
                            </span>
                        </div>

                        <!-- Main Catchy Title -->
                        <div class="space-y-3">
                            <h1 class="text-3xl sm:text-4xl xl:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-[1.15]">
                                Sistem Monitoring &amp; <br class="hidden sm:inline" />
                                <span class="bg-gradient-to-r from-primary via-secondary to-amber-600 bg-clip-text text-transparent">
                                    Audit Efisiensi PJU
                                </span>
                                Kota Tanjungpinang
                            </h1>
                            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed max-w-2xl">
                                Platform sentralisasi data geospasial titik lampu jalan, pemantauan status meterisasi IDPEL PLN, serta optimalisasi transparansi pembiayaan energi penerangan umum kota.
                            </p>
                        </div>

                        <!-- 3 Feature Capability Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                            <!-- Card 1 -->
                            <div class="p-4 rounded-2xl bg-white/70 dark:bg-gray-900/60 backdrop-blur-md border border-rose-100/80 dark:border-rose-950/60 shadow-xs hover:border-primary/40 transition-all group">
                                <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-primary dark:text-rose-300 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[20px]">map</span>
                                </div>
                                <h2 class="text-xs font-bold text-gray-900 dark:text-white mb-1">Pemetaan GIS</h2>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-normal">
                                    Sebaran koordinat presisi titik lampu di 4 kecamatan se-Tanjungpinang.
                                </p>
                            </div>

                            <!-- Card 2 -->
                            <div class="p-4 rounded-2xl bg-white/70 dark:bg-gray-900/60 backdrop-blur-md border border-rose-100/80 dark:border-rose-950/60 shadow-xs hover:border-primary/40 transition-all group">
                                <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[20px]">electric_meter</span>
                                </div>
                                <h2 class="text-xs font-bold text-gray-900 dark:text-white mb-1">Audit Meterisasi</h2>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-normal">
                                    Monitoring konversi status Abonemen ke Meterisasi IDPEL PLN.
                                </p>
                            </div>

                            <!-- Card 3 -->
                            <div class="p-4 rounded-2xl bg-white/70 dark:bg-gray-900/60 backdrop-blur-md border border-rose-100/80 dark:border-rose-950/60 shadow-xs hover:border-primary/40 transition-all group">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-[20px]">query_stats</span>
                                </div>
                                <h2 class="text-xs font-bold text-gray-900 dark:text-white mb-1">Efisiensi APBD</h2>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-normal">
                                    Rasionalisasi daya &amp; estimasi penghematan beban tagihan listrik.
                                </p>
                            </div>
                        </div>

                        <!-- Highlight Statistics Counter Bar -->
                        <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-3 border-t border-rose-100/70 dark:border-rose-950/60">
                            <div class="flex flex-col">
                                <span class="font-telemetry-data text-xl sm:text-2xl font-black text-gray-900 dark:text-white">1.850+</span>
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Titik PJU</span>
                            </div>
                            <div class="h-8 w-px bg-rose-200/60 dark:bg-rose-900/40"></div>
                            <div class="flex flex-col">
                                <span class="font-telemetry-data text-xl sm:text-2xl font-black text-secondary dark:text-rose-400">65</span>
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">IDPEL Survei</span>
                            </div>
                            <div class="h-8 w-px bg-rose-200/60 dark:bg-rose-900/40"></div>
                            <div class="flex flex-col">
                                <span class="font-telemetry-data text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400">4</span>
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kecamatan</span>
                            </div>
                            <div class="h-8 w-px bg-rose-200/60 dark:bg-rose-900/40 hidden sm:block"></div>
                            <div class="hidden sm:flex flex-col">
                                <span class="font-telemetry-data text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400">100%</span>
                                <span class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanjungpinang</span>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN: Premium Operator Login Card (5 Cols) -->
                    <div class="lg:col-span-5">
                        <div class="relative">
                            <!-- Glow background under card -->
                            <div class="absolute -inset-1 bg-gradient-to-r from-rose-500 to-amber-500 rounded-3xl blur-xl opacity-20 dark:opacity-30"></div>
                            
                            <!-- Login Card Wrapper -->
                            <div class="relative rounded-3xl bg-white/95 dark:bg-gray-900/95 backdrop-blur-2xl border border-rose-100 dark:border-rose-900/50 shadow-2xl p-7 sm:p-9 transition-all">
                                
                                <!-- Card Header -->
                                <div class="flex items-center justify-between mb-6 pb-5 border-b border-rose-100/80 dark:border-rose-950/80">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-bold uppercase tracking-wider text-secondary dark:text-rose-400">Portal Petugas</span>
                                        <h2 class="text-xl font-black text-gray-900 dark:text-white mt-0.5">Masuk ke Sistem</h2>
                                    </div>
                                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-primary dark:text-rose-300 flex items-center justify-center border border-rose-100/60 dark:border-rose-900/40 shadow-xs">
                                        <span class="material-symbols-outlined text-[22px]">admin_panel_settings</span>
                                    </div>
                                </div>

                                <!-- Status Banner (if any) -->
                                <div v-if="status" class="mb-5 p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-xs font-semibold text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                    <span>{{ status }}</span>
                                </div>

                                <!-- Login Form -->
                                <form @submit.prevent="submit" class="space-y-4">
                                    <!-- Email Field -->
                                    <div>
                                        <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                            Alamat Email Operator
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <span class="material-symbols-outlined text-[19px]">mail</span>
                                            </div>
                                            <input
                                                id="email"
                                                type="email"
                                                v-model="form.email"
                                                required
                                                autofocus
                                                autocomplete="username"
                                                placeholder="nama@dishub.tanjungpinangkota.go.id"
                                                class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                            />
                                        </div>
                                        <InputError class="mt-1.5" :message="form.errors.email" />
                                    </div>

                                    <!-- Password Field -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                                Kata Sandi
                                            </label>
                                            <Link
                                                v-if="canResetPassword"
                                                :href="route('password.request')"
                                                class="text-xs font-semibold text-primary dark:text-rose-400 hover:underline"
                                            >
                                                Lupa Sandi?
                                            </Link>
                                        </div>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                                <span class="material-symbols-outlined text-[19px]">lock</span>
                                            </div>
                                            <input
                                                id="password"
                                                :type="showPassword ? 'text' : 'password'"
                                                v-model="form.password"
                                                required
                                                autocomplete="current-password"
                                                placeholder="••••••••"
                                                class="w-full pl-10 pr-11 py-3 rounded-xl bg-gray-50/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                            />
                                            <button
                                                type="button"
                                                @click="showPassword = !showPassword"
                                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                                                tabindex="-1"
                                                aria-label="Tampilkan sandi"
                                            >
                                                <span class="material-symbols-outlined text-[19px]">
                                                    {{ showPassword ? 'visibility_off' : 'visibility' }}
                                                </span>
                                            </button>
                                        </div>
                                        <InputError class="mt-1.5" :message="form.errors.password" />
                                    </div>

                                    <!-- Remember Me Checkbox -->
                                    <div class="flex items-center justify-between pt-1">
                                        <label class="flex items-center gap-2.5 cursor-pointer">
                                            <Checkbox name="remember" v-model:checked="form.remember" class="rounded text-primary focus:ring-primary" />
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Ingat sesi saya</span>
                                        </label>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="pt-2">
                                        <button
                                            type="submit"
                                            :disabled="form.processing"
                                            class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-primary via-secondary to-rose-600 text-white font-bold text-sm shadow-lg shadow-rose-900/25 hover:shadow-rose-900/40 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 group disabled:opacity-50 disabled:pointer-events-none"
                                        >
                                            <span v-if="form.processing" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                                            <span v-else class="material-symbols-outlined text-[19px] group-hover:translate-x-0.5 transition-transform">login</span>
                                            <span>{{ form.processing ? 'Memproses Masuk...' : 'Masuk ke Dashboard' }}</span>
                                        </button>
                                    </div>
                                </form>

                                <!-- Security Assurance Footer -->
                                <div class="mt-6 pt-5 border-t border-rose-100/70 dark:border-rose-950/70 flex items-center justify-center gap-2 text-center text-gray-500 dark:text-gray-400 text-xs">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600 dark:text-emerald-400">verified_user</span>
                                    <span>Terenkripsi SSL 256-bit • Akses Terbatas Dishub TPI</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <!-- Bottom Page Footer -->
        <footer class="relative z-10 w-full border-t border-rose-100/60 dark:border-rose-950/50 bg-white/60 dark:bg-gray-950/60 backdrop-blur-md py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-500 dark:text-gray-400">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-700 dark:text-gray-300">SI-PIJAR Kota Tanjungpinang</span>
                    <span>•</span>
                    <span>Hak Cipta &copy; 2026 Dinas Perhubungan</span>
                </div>
                <div class="flex items-center gap-4">
                    <span>Wilayah: 4 Kecamatan &amp; 18 Kelurahan</span>
                    <span>•</span>
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold">Server Aktif</span>
                </div>
            </div>
        </footer>

        <!-- Help & Instructions Modal -->
        <div v-if="showHelpModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-gray-900 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-rose-100 dark:border-rose-900/50 relative">
                <button 
                    @click="showHelpModal = false"
                    type="button" 
                    class="absolute top-5 right-5 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-gray-900 dark:hover:text-white flex items-center justify-center transition-colors"
                    aria-label="Tutup"
                >
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>

                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-primary dark:text-rose-300 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">contact_support</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Bantuan &amp; Layanan Petugas</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pusat Layanan SI-PIJAR Tanjungpinang</p>
                    </div>
                </div>

                <div class="space-y-3.5 text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                    <div class="p-3.5 rounded-2xl bg-rose-50/60 dark:bg-rose-950/40 border border-rose-100 dark:border-rose-900/30">
                        <span class="font-bold text-gray-900 dark:text-white block mb-1">🔐 Masalah Kredensial Masuk</span>
                        Jika Anda belum memiliki akun atau mengalami kendala login, hubungi Tim Administrator Dishub untuk verifikasi NIP/ID Petugas Lapangan.
                    </div>
                    <div class="p-3.5 rounded-2xl bg-gray-50 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700/60">
                        <span class="font-bold text-gray-900 dark:text-white block mb-1">📍 Sinkronisasi Data Survei</span>
                        Hasil survei lapangan PJU (Meterisasi &amp; Titik Tiang) terintegrasi otomatis dengan sistem geospasial setelah diinput melalui portal ini.
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                    <button 
                        @click="showHelpModal = false"
                        type="button"
                        class="px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-100 text-white dark:text-gray-900 font-bold text-xs transition-colors"
                    >
                        Tutup Panduan
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
