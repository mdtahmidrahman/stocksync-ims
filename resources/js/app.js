import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import AppLayout from './Layouts/AppLayout.vue';
import PublicLayout from './Layouts/PublicLayout.vue';
import '../css/app.css';

// Views
import Landing from './Pages/Landing.vue';
import Features from './Pages/Features.vue';
import Pricing from './Pages/Pricing.vue';
import Login from './Pages/Login.vue';
import Signup from './Pages/Signup.vue';
import Dashboard from './Pages/Dashboard.vue';
import Products from './Pages/Products.vue';
import Suppliers from './Pages/Suppliers.vue';
import Orders from './Pages/Orders.vue';
import Inventory from './Pages/Inventory.vue';
import Purchases from './Pages/Purchases.vue';
import Reports from './Pages/Reports.vue';
import Settings from './Pages/Settings.vue';
import Categories from './Pages/Categories.vue';
import Warehouses from './Pages/Warehouses.vue';
import Payments from './Pages/Payments.vue';
import Roles from './Pages/Roles.vue';
import Support from './Pages/Support.vue';
import Sales from './Pages/Sales.vue';
import Customers from './Pages/Customers.vue';

import Dropdown from './Components/Dropdown.vue';
import Tooltip from './Components/Tooltip.vue';

const routes = [
    { 
        path: '/', 
        component: PublicLayout,
        children: [
            { path: '', component: Landing },
            { path: 'features', component: Features },
            { path: 'pricing', component: Pricing },
        ]
    },
    { path: '/login', component: Login },
    { path: '/signup', component: Signup },
    { 
        path: '/', 
        component: AppLayout,
        children: [
            { path: '/dashboard', component: Dashboard },
            { path: '/products', component: Products },
            { path: '/suppliers', component: Suppliers },
            { path: '/orders', component: Orders },
            { path: '/inventory', component: Inventory },
            { path: '/purchases', component: Purchases },
            { path: '/reports', component: Reports },
            { path: '/settings', component: Settings },
            { path: '/categories', component: Categories },
            { path: '/warehouses', component: Warehouses },
            { path: '/payments', component: Payments },
            { path: '/roles', component: Roles },
            { path: '/support', component: Support },
            { path: '/sales', component: Sales },
            { path: '/customers', component: Customers },
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App);
app.use(router);

// Global Component Registration
app.component('Dropdown', Dropdown);
app.component('Tooltip', Tooltip);

app.mount('#app');
