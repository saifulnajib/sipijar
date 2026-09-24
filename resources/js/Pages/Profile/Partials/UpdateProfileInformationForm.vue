<script setup>
import InputError from '@/Components/InputError.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header class="flex items-center gap-3.5 pb-4 mb-5 border-b border-rose-100/80 dark:border-rose-950/80">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-primary dark:text-rose-300 flex items-center justify-center shadow-xs border border-rose-100/60 dark:border-rose-900/40">
                <span class="material-symbols-outlined text-[22px]">badge</span>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    Informasi Profil Petugas
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Perbarui nama lengkap dan alamat email akun operator Anda.
                </p>
            </div>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="space-y-4"
        >
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Nama Lengkap Petugas
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-outlined text-[19px]">person</span>
                    </div>
                    <input
                        id="name"
                        type="text"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Nama Lengkap"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <!-- Email Petugas -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Alamat Email Resmi
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
                        autocomplete="username"
                        placeholder="email@dishub.tanjungpinangkota.go.id"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                    />
                </div>
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Email Verification Notice if Applicable -->
            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 text-xs text-amber-800 dark:text-amber-200">
                <p>
                    Alamat email Anda belum diverifikasi.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="font-bold underline hover:text-amber-900 dark:hover:text-white"
                    >
                        Klik di sini untuk mengirim ulang tautan verifikasi.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5"
                >
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    <span>Tautan verifikasi baru telah dikirim ke alamat email Anda.</span>
                </div>
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-4 pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="py-2.5 px-5 rounded-xl bg-gradient-to-r from-primary to-secondary text-white font-bold text-xs shadow-md shadow-rose-900/20 hover:shadow-rose-900/30 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center gap-2 disabled:opacity-50"
                >
                    <span v-if="form.processing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    <span v-else class="material-symbols-outlined text-[17px]">save</span>
                    <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                </button>

                <Transition
                    enter-active-class="transition ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-1"
                    leave-active-class="transition ease-in duration-200"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="form.recentlySuccessful"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-3 py-1 rounded-lg border border-emerald-200 dark:border-emerald-800"
                    >
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span>Berhasil disimpan</span>
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>
