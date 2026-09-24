<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();
const activeTab = ref('dashboard-monitoring');
</script>

<template>
  <div class="bg-[#fcf9f9] dark:bg-[#12080a] font-body-md text-on-surface antialiased min-h-screen selection:bg-primary/20 selection:text-primary">
    <!-- Sidebar Navigation -->
    <aside class="fixed left-0 top-0 h-full w-72 bg-white/85 dark:bg-gray-900/85 backdrop-blur-2xl z-50 flex flex-col justify-between shadow-[0_4px_24px_rgba(122,28,40,0.06)] border-r border-rose-100/70 dark:border-rose-950/40 transition-all">
      <div class="flex flex-col">
        <!-- Brand Header -->
        <div class="p-5 flex flex-col gap-3 bg-gradient-to-b from-rose-50/50 to-transparent dark:from-rose-950/20 border-b border-rose-100/50 dark:border-rose-950/30">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary via-secondary to-primary-container flex items-center justify-center text-white shadow-md shadow-primary/25 flex-shrink-0 ring-2 ring-primary/10">
              <span class="material-symbols-outlined text-[24px]">wb_incandescent</span>
            </div>
            <div class="flex flex-col">
              <span class="font-headline-sm text-[20px] font-extrabold text-gray-900 dark:text-white tracking-tight leading-tight flex items-center gap-1">
                SI-PIJAR
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
              </span>
              <span class="font-caption text-[10px] text-secondary dark:text-rose-400 font-bold uppercase tracking-widest">Tanjungpinang</span>
            </div>
          </div>
          
          <div class="flex flex-wrap gap-1.5 pt-1">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-rose-100/70 dark:bg-rose-950/60 text-primary dark:text-rose-300 font-caption text-[10px] font-bold uppercase tracking-wide">
              Dishub Tanjungpinang
            </span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gradient-to-r from-secondary-fixed to-rose-200/60 dark:from-rose-900/40 dark:to-rose-800/40 text-on-secondary-fixed dark:text-rose-200 font-caption text-[10px] font-bold">
              Command Center PJU
            </span>
          </div>
        </div>

        <!-- Menu Section Header -->
        <div class="px-5 pt-4 pb-2">
          <span class="font-caption text-[11px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-bold">Modul Operasional</span>
        </div>

        <!-- Nav Items -->
        <nav class="flex flex-col gap-1 px-3">
          <Link
            :href="route('dashboard')"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 font-label-md text-sm font-semibold group relative overflow-hidden"
            :class="route().current('dashboard') ? 'bg-gradient-to-r from-primary via-secondary to-primary-container text-white shadow-md shadow-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-rose-950/30 hover:text-primary dark:hover:text-rose-300 font-medium'"
          >
            <div v-if="route().current('dashboard')" class="absolute left-0 top-0 bottom-0 w-1 bg-white/40 rounded-r"></div>
            <span class="material-symbols-outlined text-[20px] transition-transform" :class="route().current('dashboard') ? 'group-hover:scale-110' : 'text-gray-400 group-hover:text-primary dark:group-hover:text-rose-300'">dashboard</span>
            <span>Beranda</span>
            <span v-if="route().current('dashboard')" class="ml-auto w-2 h-2 rounded-full bg-white/80 animate-ping"></span>
          </Link>

          <Link
            :href="route('maps.index')"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 font-label-md text-sm font-semibold group relative overflow-hidden"
            :class="route().current('maps.*') ? 'bg-gradient-to-r from-primary via-secondary to-primary-container text-white shadow-md shadow-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-rose-950/30 hover:text-primary dark:hover:text-rose-300 font-medium'"
          >
            <div v-if="route().current('maps.*')" class="absolute left-0 top-0 bottom-0 w-1 bg-white/40 rounded-r"></div>
            <span class="material-symbols-outlined text-[20px] transition-transform" :class="route().current('maps.*') ? 'group-hover:scale-110' : 'text-gray-400 group-hover:text-primary dark:group-hover:text-rose-300'">map</span>
            <span>Peta Sebaran Lampu</span>
            <span v-if="route().current('maps.*')" class="ml-auto w-2 h-2 rounded-full bg-white/80 animate-ping"></span>
          </Link>

          <Link
            :href="route('idpels.index')"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 font-label-md text-sm font-semibold group relative overflow-hidden"
            :class="route().current('idpels.*') ? 'bg-gradient-to-r from-primary via-secondary to-primary-container text-white shadow-md shadow-primary/20' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-rose-950/30 hover:text-primary dark:hover:text-rose-300 font-medium'"
          >
            <div v-if="route().current('idpels.*')" class="absolute left-0 top-0 bottom-0 w-1 bg-white/40 rounded-r"></div>
            <span class="material-symbols-outlined text-[20px] transition-transform" :class="route().current('idpels.*') ? 'group-hover:scale-110' : 'text-gray-400 group-hover:text-primary dark:group-hover:text-rose-300'">electric_meter</span>
            <span>Data IDPEL &amp; Meterisasi</span>
            <span v-if="route().current('idpels.*')" class="ml-auto w-2 h-2 rounded-full bg-white/80 animate-ping"></span>
          </Link>

          <Link
            :href="route('dashboard')"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-rose-950/30 hover:text-primary dark:hover:text-rose-300 font-label-md text-sm font-medium transition-all duration-200 group"
          >
            <span class="material-symbols-outlined text-[20px] text-gray-400 group-hover:text-primary dark:group-hover:text-rose-300 transition-colors">query_stats</span>
            <span>Analisis Efisiensi Anggaran</span>
          </Link>

          <Link
            :href="route('dashboard')"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-rose-950/30 hover:text-primary dark:hover:text-rose-300 font-label-md text-sm font-medium transition-all duration-200 group"
          >
            <span class="material-symbols-outlined text-[20px] text-gray-400 group-hover:text-primary dark:group-hover:text-rose-300 transition-colors">fact_check</span>
            <span>Laporan &amp; Audit</span>
          </Link>

          <Link
            :href="route('dashboard')"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-rose-950/30 hover:text-primary dark:hover:text-rose-300 font-label-md text-sm font-medium transition-all duration-200 group"
          >
            <span class="material-symbols-outlined text-[20px] text-gray-400 group-hover:text-primary dark:group-hover:text-rose-300 transition-colors">light</span>
            <span>Manajemen Aset PJU</span>
          </Link>
        </nav>
      </div>

      <!-- User Profile Card -->
      <div class="p-3.5 bg-gradient-to-t from-rose-50/70 dark:from-rose-950/40 to-transparent border-t border-rose-100/50 dark:border-rose-950/30">
        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-md shadow-sm border border-rose-100/60 dark:border-rose-900/40 hover:border-primary/30 transition-all">
          <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-primary to-secondary flex items-center justify-center text-white shadow-sm flex-shrink-0 ring-2 ring-primary/20">
            <span class="material-symbols-outlined text-[20px]">person</span>
          </div>
          <div class="flex flex-col min-w-0 flex-1">
            <span class="font-label-md text-xs font-bold text-gray-900 dark:text-white truncate">{{ $page.props.auth.user.name }}</span>
            <span class="font-caption text-[11px] text-gray-500 dark:text-gray-400 truncate">Kota Tanjungpinang</span>
          </div>
          <span class="material-symbols-outlined text-emerald-600 text-[18px]" title="Akun Terverifikasi">verified</span>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="pl-72 flex flex-col min-h-screen">
      <!-- Top Navigation Bar -->
      <header class="fixed top-0 left-72 right-0 h-16 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl shadow-[0_1px_12px_rgba(122,28,40,0.04)] border-b border-rose-100/60 dark:border-rose-950/40 z-40 flex items-center justify-between px-6 lg:px-8 transition-all">
        <!-- Left status pill -->
        <div class="flex items-center gap-4">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white dark:bg-gray-800 shadow-xs border border-rose-100/60 dark:border-rose-900/40 ring-1 ring-emerald-500/10">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
            </span>
            <span class="font-caption text-xs text-gray-600 dark:text-gray-300 font-bold uppercase tracking-wider">SCADA:</span>
            <span class="font-caption text-xs text-emerald-600 dark:text-emerald-400 font-extrabold uppercase">ONLINE</span>
          </div>
          
          <div class="hidden sm:inline-flex items-center gap-2 text-gray-600 dark:text-gray-300 text-xs font-medium">
            <span class="material-symbols-outlined text-[16px] text-primary">schedule</span>
            <span class="font-telemetry-unit text-xs font-bold text-gray-800 dark:text-gray-200">19:42:08 WIB</span>
            <span class="text-gray-400 text-[11px]">(Tanjungpinang)</span>
          </div>
        </div>

        <!-- Right Quick Controls & Profile -->
        <div class="flex items-center gap-3">
          <!-- Notification Bell -->
          <div class="relative flex items-center justify-center">
            <button
              aria-label="Pemberitahuan Sistem"
              class="w-9 h-9 rounded-xl hover:bg-rose-50 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-primary transition-all flex items-center justify-center border border-transparent hover:border-rose-100"
              type="button"
            >
              <span class="material-symbols-outlined text-[20px]">notifications</span>
            </button>
            <span class="absolute 1.5 top-1.5 right-1.5 w-2 h-2 rounded-full bg-primary ring-2 ring-white dark:ring-gray-900"></span>
          </div>

          <div class="h-6 w-px bg-rose-200/50 dark:bg-rose-900/40"></div>

          <!-- User Profile Dropdown -->
          <div class="flex items-center gap-2.5 pl-1">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button type="button" class="flex items-center gap-2 hover:bg-rose-50 dark:hover:bg-gray-800 p-1.5 rounded-xl transition-colors border border-transparent focus:outline-none">
                  <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white shadow-xs font-bold text-xs">
                    {{ $page.props.auth.user.name.charAt(0) }}
                  </div>
                  <div class="hidden xl:flex flex-col text-left">
                    <span class="font-label-md text-xs font-bold text-gray-900 dark:text-white leading-none">{{ $page.props.auth.user.name }}</span>
                    <span class="font-caption text-[10px] text-gray-400 dark:text-gray-400 mt-0.5 leading-none">Super Operator</span>
                  </div>
                  <span class="material-symbols-outlined text-[18px] text-gray-400">expand_more</span>
                </button>
              </template>

              <template #content>
                <DropdownLink :href="route('profile.edit')">
                  <div class="flex items-center gap-2 font-label-md text-xs">
                    <span class="material-symbols-outlined text-[18px]">person</span> Profil Akun
                  </div>
                </DropdownLink>
                <DropdownLink :href="route('logout')" method="post" as="button" class="w-full text-left">
                  <div class="flex items-center gap-2 font-label-md text-xs text-rose-600 dark:text-rose-400">
                    <span class="material-symbols-outlined text-[18px]">logout</span> Keluar Sistem
                  </div>
                </DropdownLink>
              </template>
            </Dropdown>
          </div>
        </div>
      </header>

      <!-- Slot content -->
      <div class="flex-1 flex flex-col">
        <slot />
      </div>
    </div>
  </div>
</template>
