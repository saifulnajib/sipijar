<script setup>
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header class="flex items-center gap-3.5 pb-4 mb-5 border-b border-rose-100/80 dark:border-rose-950/80">
            <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-xs border border-amber-100/60 dark:border-amber-900/40">
                <span class="material-symbols-outlined text-[22px]">lock_reset</span>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-900 dark:text-white">
                    Perbarui Kata Sandi
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Pastikan akun Anda menggunakan kombinasi sandi yang kuat dan aman.
                </p>
            </div>
        </header>

        <form @submit.prevent="updatePassword" class="space-y-4">
            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Kata Sandi Saat Ini
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-outlined text-[19px]">key</span>
                    </div>
                    <input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        :type="showCurrentPassword ? 'text' : 'password'"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-gray-50/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                    />
                    <button
                        type="button"
                        @click="showCurrentPassword = !showCurrentPassword"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        tabindex="-1"
                        aria-label="Toggle visibility"
                    >
                        <span class="material-symbols-outlined text-[19px]">
                            {{ showCurrentPassword ? 'visibility_off' : 'visibility' }}
                        </span>
                    </button>
                </div>
                <InputError :message="form.errors.current_password" class="mt-1.5" />
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Kata Sandi Baru
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-outlined text-[19px]">lock</span>
                    </div>
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        :type="showNewPassword ? 'text' : 'password'"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-gray-50/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                    />
                    <button
                        type="button"
                        @click="showNewPassword = !showNewPassword"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        tabindex="-1"
                        aria-label="Toggle visibility"
                    >
                        <span class="material-symbols-outlined text-[19px]">
                            {{ showNewPassword ? 'visibility_off' : 'visibility' }}
                        </span>
                    </button>
                </div>
                <InputError :message="form.errors.password" class="mt-1.5" />
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Konfirmasi Kata Sandi Baru
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-outlined text-[19px]">password</span>
                    </div>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-gray-50/80 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"
                    />
                    <button
                        type="button"
                        @click="showConfirmPassword = !showConfirmPassword"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        tabindex="-1"
                        aria-label="Toggle visibility"
                    >
                        <span class="material-symbols-outlined text-[19px]">
                            {{ showConfirmPassword ? 'visibility_off' : 'visibility' }}
                        </span>
                    </button>
                </div>
                <InputError :message="form.errors.password_confirmation" class="mt-1.5" />
            </div>

            <!-- Action Controls -->
            <div class="flex items-center gap-4 pt-2">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="py-2.5 px-5 rounded-xl bg-gradient-to-r from-amber-600 to-rose-600 text-white font-bold text-xs shadow-md shadow-amber-900/20 hover:shadow-amber-900/30 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center gap-2 disabled:opacity-50"
                >
                    <span v-if="form.processing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    <span v-else class="material-symbols-outlined text-[17px]">security</span>
                    <span>{{ form.processing ? 'Memperbarui...' : 'Perbarui Sandi' }}</span>
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
                        <span>Sandi berhasil diperbarui</span>
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>
