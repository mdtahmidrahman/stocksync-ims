import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import '../css/app.css';

// Global Components
import Dropdown from './Components/Dropdown.vue';
import Tooltip from './Components/Tooltip.vue';

createInertiaApp({
    title: (title) => title ? `${title} - StockSync IMS` : 'StockSync IMS',
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        
        // Global Component Registration
        app.component('Link', Link);
        app.component('Dropdown', Dropdown);
        app.component('Tooltip', Tooltip);
        
        app.mount(el);
    },
    progress: {
        color: '#4f46e5',
    },
});
