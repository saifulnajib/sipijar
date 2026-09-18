const fs = require('fs');

const htmlContent = fs.readFileSync('sipijar_v_red.html', 'utf8');

// 1. Extract Tailwind Config
const twMatch = htmlContent.match(/tailwind\.config = (\{.*?\});<\/script>/s);
if (twMatch) {
    const configStr = twMatch[1];
    const twConfigTmpl = `import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: "class",
    theme: ${configStr.replace('theme: {', 'theme: {')},
    plugins: [forms, containerQueries],
};
`;
    fs.writeFileSync('tailwind.config.js', twConfigTmpl);
}

// 2. Extract Aside and Header
const asideMatch = htmlContent.match(/<aside[\s\S]*?<\/aside>/);
const headerMatch = htmlContent.match(/<header[\s\S]*?<\/header>/);

let layoutTmpl = `<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();
</script>

<template>
  <div class="bg-background font-body-md text-on-surface antialiased min-h-screen">
    ${asideMatch ? asideMatch[0] : ''}
    <div class="pl-72 flex flex-col min-h-screen">
      ${headerMatch ? headerMatch[0] : ''}
      <div class="pt-16 flex-1 flex flex-col">
        <slot />
      </div>
    </div>
  </div>
</template>
`;

// Inject Link components for sidebar
layoutTmpl = layoutTmpl.replace(/<a[^>]*href="#"[^>]*>([\s\S]*?)<\/a>/g, (match, content) => {
    return match.replace('<a', '<Link').replace('</a>', '</Link>').replace('href="#"', `:href="route('dashboard')"`);
});
layoutTmpl = layoutTmpl.replace('Admin Dishub', '{{ $page.props.auth.user.name }}');

fs.writeFileSync('resources/js/Layouts/DashboardLayout.vue', layoutTmpl);

// 3. Extract Main (Dashboard content)
const mainMatch = htmlContent.match(/<main[\s\S]*?<\/main>/);
if (mainMatch) {
    let mainContent = mainMatch[0];
    
    // Data bindings
    mainContent = mainContent.replace(/>1\.482</, '>{{ formatNumber(kpi.total_idpel) }}<');
    mainContent = mainContent.replace(/>8\.940</, '>{{ formatNumber(kpi.total_lamps) }}<');
    mainContent = mainContent.replace(/>1\.156</, '>{{ formatNumber(kpi.meterisasi_count) }}<');
    mainContent = mainContent.replace(/>326</, '>{{ formatNumber(kpi.abonemen_count) }}<');
    mainContent = mainContent.replace(/>78,0% Terukur</, '>{{ kpi.meterisasi_percentage }}% Terukur<');
    mainContent = mainContent.replace(/>Sisa 22,0% Kontrak Daya</, '>Sisa {{ kpi.abonemen_percentage }}% Kontrak Daya<');
    
    // For styles: 
    mainContent = mainContent.replace(/style="width: 78%;"/g, `:style="\`width: \${kpi.meterisasi_percentage}%\`"`);
    mainContent = mainContent.replace(/style="width: 22%;"/g, `:style="\`width: \${kpi.abonemen_percentage}%\`"`);
    
    // For CSS classes bound dynamically
    // Vue syntax fixes
    mainContent = mainContent.replace(/class="(.*?)"/g, (match, classes) => {
        // Just standard class is fine for most
        return match;
    });

    const dashboardTmpl = `<script setup>
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    kpi: Object
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
};

const formatNumber = (value) => {
    return new Intl.NumberFormat('id-ID').format(value);
};
</script>

<template>
    <Head title="SIPIJAR Command Center" />
    <DashboardLayout>
        ${mainContent}
    </DashboardLayout>
</template>
`;
    fs.writeFileSync('resources/js/Pages/Dashboard.vue', dashboardTmpl);
}

// 4. Update app.blade.php to include google fonts
const appBlade = fs.readFileSync('resources/views/app.blade.php', 'utf8');
if (!appBlade.includes('fonts.googleapis.com')) {
    const fontsStr = `<link href="https://fonts.googleapis.com" rel="preconnect"/><link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet"/><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet"/><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>`;
    fs.writeFileSync('resources/views/app.blade.php', appBlade.replace('</head>', fontsStr + '\n    </head>'));
}

console.log('Migration complete!');
