<script setup>
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-4">
        <header class="flex items-center gap-3.5 pb-4 border-b border-rose-200/80 dark:border-rose-900/40">
            <div class="w-10 h-10 rounded-2xl bg-rose-100 dark:bg-rose-900/60 text-rose-700 dark:text-rose-300 flex items-center justify-center shadow-xs border border-rose-300/60 dark:border-rose-800/40">
                <span class="material-symbols-outlined text-[22px]">warning</span>
            </div>
            <div>
                <h2 class="text-base font-bold text-rose-950 dark:text-rose-200">
                    Zona Bahaya (Hapus Akun)
                </h2>
                <p class="text-xs text-rose-800/80 dark:text-rose-300/70">
                    Tindakan ini permanen dan akan menghapus seluruh data kredensial akun operator Anda.
                </p>
            </div>
        </header>

        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
            Setelah akun Anda dihapus, semua data dan hak akses Anda ke SI-PIJAR Dishub Tanjungpinang akan dihapus secara permanen. Pastikan Anda telah melakukan koordinasi dengan Administrator Dishub sebelum menghapus akun ini.
        </p>

        <div class="pt-1">
            <button
                type="button"
                @click="confirmUserDeletion"
                class="py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-bold text-xs shadow-sm transition-all inline-flex items-center gap-2"
            >
                <span class="material-symbols-outlined text-[17px]">delete_forever</span>
                <span>Hapus Akun Saya</span>
            </button>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 sm:p-7 bg-white dark:bg-gray-900 rounded-3xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-2xl bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">error</span>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">
                            Konfirmasi Penghapusan Akun
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Tindakan ini tidak dapat dibatalkan
                        </p>
                    </div>
                </div>

                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                    Apakah Anda yakin ingin menghapus akun operator ini? Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda benar-benar pemilik sah akun ini.
                </p>

                <div class="mt-4">
                    <label for="delete_password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                        Konfirmasi Kata Sandi
                    </label>

                    <input
                        id="delete_password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="w-full px-4 py-2.5 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm placeholder-gray-400 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all"
                        placeholder="Masukkan kata sandi akun Anda"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-1.5" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal"
                        class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-xs transition-colors"
                    >
                        Batal
                    </button>

                    <button
                        type="button"
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white font-bold text-xs shadow-sm transition-all inline-flex items-center gap-2 disabled:opacity-50"
                    >
                        <span v-if="form.processing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        <span v-else class="material-symbols-outlined text-[17px]">delete_forever</span>
                        <span>{{ form.processing ? 'Menghapus...' : 'Hapus Akun Permanen' }}</span>
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
