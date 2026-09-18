<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

const props = defineProps({
    idpels: Array,
});

const mapContainer = ref(null);
let map = null;

onMounted(() => {
    // Initialize map centered at Tanjungpinang approx
    map = L.map(mapContainer.value, {
        zoomControl: false // We'll use custom positioning if needed, or default
    }).setView([0.9167, 104.4500], 13);
    
    // Add Zoom Control at bottom right
    L.control.zoom({
        position: 'bottomright'
    }).addTo(map);

    // Dark theme CartoDB base layer
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Custom Icon styles using DivIcon for glassmorphism/glow effect
    const createMarkerIcon = (status) => {
        let colorClass = '';
        let glowColor = '';
        
        if (status === 'meterisasi') {
            colorClass = 'bg-emerald-500';
            glowColor = 'shadow-[0_0_12px_rgba(16,185,129,0.8)]';
        } else if (status === 'abonemen') {
            colorClass = 'bg-amber-400';
            glowColor = 'shadow-[0_0_12px_rgba(251,191,36,0.8)]';
        } else {
            colorClass = 'bg-rose-500 animate-pulse';
            glowColor = 'shadow-[0_0_15px_rgba(244,63,94,0.9)]';
        }

        return L.divIcon({
            className: 'custom-leaflet-marker',
            html: `<div class="w-4 h-4 rounded-full border-2 border-white ${colorClass} ${glowColor}"></div>`,
            iconSize: [16, 16],
            iconAnchor: [8, 8]
        });
    };

    // Add markers for each IDPEL
    const bounds = [];
    
    props.idpels.forEach(idpel => {
        if (idpel.lat && idpel.lng) {
            const lat = parseFloat(idpel.lat);
            const lng = parseFloat(idpel.lng);
            
            if (!isNaN(lat) && !isNaN(lng)) {
                bounds.push([lat, lng]);
                
                // Get current kW from first substation if available
                const currentKw = (idpel.substations && idpel.substations.length > 0) 
                    ? idpel.substations[0].current_kw 
                    : (idpel.status === 'abonemen' ? 'Flat' : '0');

                const statusText = idpel.status === 'meterisasi' 
                    ? '<span class="text-emerald-400 font-bold">Meter Digital</span>' 
                    : '<span class="text-amber-400 font-bold">Abonemen</span>';

                const popupContent = `
                    <div class="bg-gray-900 text-white p-1 rounded min-w-[200px]">
                        <div class="border-b border-gray-700 pb-2 mb-2">
                            <div class="font-caption text-[10px] text-gray-400 uppercase tracking-wider">IDPEL PLN</div>
                            <div class="font-bold text-rose-300 text-sm font-telemetry-unit">${idpel.idpel_number}</div>
                            <div class="font-bold text-white text-base leading-tight mt-1">${idpel.name}</div>
                        </div>
                        <div class="flex flex-col gap-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Status:</span>
                                <span>${statusText}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Daya Terukur:</span>
                                <span class="font-bold text-white">${currentKw} kW</span>
                            </div>
                            <div class="text-gray-400 mt-1 line-clamp-2 leading-snug">${idpel.address || 'Alamat tidak tersedia'}</div>
                        </div>
                    </div>
                `;

                const marker = L.marker([lat, lng], {
                    icon: createMarkerIcon(idpel.status)
                }).addTo(map);

                marker.bindPopup(popupContent, {
                    className: 'glass-popup' // We will style this via global CSS if needed
                });
            }
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }
});
</script>

<style>
/* Leaflet popup customization to match dark glassmorphism theme */
.leaflet-popup-content-wrapper {
    background: rgba(17, 24, 39, 0.9) !important;
    backdrop-filter: blur(12px) !important;
    border: 1px solid rgba(244, 63, 94, 0.2) !important;
    border-radius: 1rem !important;
    color: white !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
}
.leaflet-popup-tip {
    background: rgba(17, 24, 39, 0.9) !important;
}
.leaflet-popup-content {
    margin: 12px !important;
}
</style>

<template>
    <Head title="GIS Monitoring - SIPIJAR" />

    <DashboardLayout>
        <main class="w-full h-[calc(100vh-64px)] pt-16 bg-gray-950 relative overflow-hidden flex flex-col">
            
            <!-- Map HUD / Floating Control Panel -->
            <div class="absolute top-20 left-6 z-[400] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-xl p-4 rounded-2xl border border-white/10 shadow-2xl pointer-events-auto">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-300 flex items-center justify-center border border-rose-500/30">
                        <span class="material-symbols-outlined text-[20px]">map</span>
                    </div>
                    <div class="flex flex-col">
                        <h1 class="font-headline-sm text-lg font-extrabold text-white tracking-tight">GIS Telemetri</h1>
                        <p class="font-caption text-[11px] text-gray-400">Peta Sebaran Titik Lampu PJU Tanjungpinang</p>
                    </div>
                </div>

                <!-- Legend -->
                <div class="bg-white/10 backdrop-blur-xl p-4 rounded-2xl border border-white/10 shadow-2xl pointer-events-auto flex flex-col gap-2.5">
                    <span class="font-caption text-[10px] text-gray-400 uppercase tracking-widest font-bold">Keterangan Peta</span>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)] border border-white"></span>
                        <span class="font-label-md text-xs text-gray-200">IDPEL Terukur (Digital)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.8)] border border-white"></span>
                        <span class="font-label-md text-xs text-gray-200">IDPEL Abonemen (Flat)</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1 pt-2 border-t border-white/10">
                        <span class="font-telemetry-unit text-xs text-emerald-400 font-bold">Total: {{ idpels.length }} Titik Terdaftar</span>
                    </div>
                </div>
            </div>

            <!-- Leaflet Container -->
            <div ref="mapContainer" class="w-full flex-1 z-0 bg-gray-950"></div>

        </main>
    </DashboardLayout>
</template>
