import re

with open('resources/js/Pages/Dashboard.vue', 'r') as f:
    content = f.read()

# Update props
content = re.sub(
    r'const props = defineProps\(\{\n    kpi: Object\n\}\);',
    r'const props = defineProps({\n    kpi: Object,\n    idpels: Object\n});',
    content
)

# Update tbody
tbody_start = content.find('<tbody class="divide-y divide-rose-100/50 dark:divide-rose-950/30 font-body-sm text-xs text-gray-800 dark:text-gray-200 bg-white/50 dark:bg-gray-900/50">')
tbody_end = content.find('</tbody>', tbody_start) + len('</tbody>')

new_tbody = """<tbody class="divide-y divide-rose-100/50 dark:divide-rose-950/30 font-body-sm text-xs text-gray-800 dark:text-gray-200 bg-white/50 dark:bg-gray-900/50">
                            <tr v-for="idpel in idpels.data" :key="idpel.id" class="hover:bg-rose-50/40 dark:hover:bg-rose-950/20 transition-colors">
                                <td class="p-3.5 pl-4 font-telemetry-unit font-bold text-primary">
                                    {{ idpel.idpel_number }}
                                </td>
                                <td class="p-3.5">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-950 dark:text-white">{{ idpel.name }}</span>
                                        <span class="font-caption text-[11px] text-gray-500">{{ idpel.address || 'Alamat tidak tersedia' }}</span>
                                    </div>
                                </td>
                                <td class="p-3.5 text-gray-600">{{ idpel.district ? idpel.district.name : '-' }}</td>
                                <td class="p-3.5">
                                    <span v-if="idpel.status === 'meterisasi'" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-caption text-[11px] font-bold border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Meter Digital
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 font-caption text-[11px] font-bold border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Non-Meter (Flat)
                                    </span>
                                </td>
                                <td class="p-3.5">
                                    <div class="flex flex-col">
                                        <span v-if="idpel.status === 'meterisasi'" class="font-telemetry-data font-bold text-gray-900 dark:text-white">
                                            {{ idpel.substations && idpel.substations.length > 0 ? idpel.substations[0].current_kw : '0' }} kW
                                        </span>
                                        <span v-else class="font-telemetry-data font-bold text-gray-700">Beban Flat Kontrak</span>
                                        <span class="font-caption text-[11px] text-gray-500">
                                            {{ idpel.status === 'meterisasi' ? 'Monitoring Aktif' : 'Estimasi ' + (idpel.substations && idpel.substations.length > 0 ? (idpel.substations[0].current_kw * 1000) : '6.600') + ' VA' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-caption text-[11px] font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> 100% Nyala (Normal)
                                    </span>
                                </td>
                                <td class="p-3.5 pr-4 text-right">
                                    <button class="p-1.5 rounded-lg hover:bg-rose-100 text-gray-500 hover:text-primary transition-colors" title="Lihat Detail Telemetri" type="button">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="idpels.data.length === 0">
                                <td colspan="7" class="p-3.5 text-center text-gray-500 font-caption py-8">
                                    Tidak ada data IDPEL ditemukan.
                                </td>
                            </tr>
                        </tbody>"""

content = content[:tbody_start] + new_tbody + content[tbody_end:]

# Update pagination footer
pagination_start = content.find('Menampilkan 5 dari 1.482 IDPEL Terdaftar')
pagination_end = content.find('Sinkronisasi otomatis setiap 60 detik') + len('Sinkronisasi otomatis setiap 60 detik')

new_pagination = 'Menampilkan {{ idpels.to || 0 }} dari {{ formatNumber(idpels.total) }} IDPEL Terdaftar • Sinkronisasi otomatis setiap 60 detik'
if pagination_start != -1:
    content = content[:pagination_start] + new_pagination + content[pagination_end:]

with open('resources/js/Pages/Dashboard.vue', 'w') as f:
    f.write(content)
