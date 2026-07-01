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
import AuditLog from './Pages/AuditLog.vue';

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
    { path: '/login', component: Login, meta: { guestOnly: true } },
    { path: '/signup', component: Signup, meta: { guestOnly: true } },
    { 
        path: '/', 
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '/dashboard', component: Dashboard },
            { path: '/products', component: Products, meta: { roles: ['admin', 'manager'] } },
            { path: '/suppliers', component: Suppliers, meta: { roles: ['admin', 'manager'] } },
            { path: '/orders', component: Orders },
            { path: '/inventory', component: Inventory },
            { path: '/purchases', component: Purchases, meta: { roles: ['admin', 'manager'] } },
            { path: '/reports', component: Reports, meta: { roles: ['admin', 'manager'] } },
            { path: '/settings', component: Settings, meta: { roles: ['admin'] } },
            { path: '/categories', component: Categories, meta: { roles: ['admin', 'manager'] } },
            { path: '/warehouses', component: Warehouses, meta: { roles: ['admin', 'manager'] } },
            { path: '/payments', component: Payments, meta: { roles: ['admin', 'manager'] } },
            { path: '/roles', component: Roles, meta: { roles: ['admin'] } },
            { path: '/support', component: Support },
            { path: '/sales', component: Sales },
            { path: '/customers', component: Customers },
            { path: '/audit-log', component: AuditLog, meta: { roles: ['admin', 'manager'] } },
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

import { useAppState } from './Composables/useAppState';

let hasCheckedAuth = false;

router.beforeEach(async (to, from, next) => {
    const { isAuthenticated, currentUserRole, checkAuth } = useAppState();

    if (!hasCheckedAuth) {
        await checkAuth();
        hasCheckedAuth = true;
    }

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const guestOnly = to.matched.some(record => record.meta.guestOnly);

    if (requiresAuth && !isAuthenticated.value) {
        next('/login');
    } else if (guestOnly && isAuthenticated.value) {
        next('/dashboard');
    } else if (requiresAuth && to.meta.roles && !to.meta.roles.includes(currentUserRole.value)) {
        // Access forbidden, fallback to dashboard
        next('/dashboard');
    } else {
        next();
    }
});

const app = createApp(App);
app.use(router);

// Global Component Registration
app.component('Dropdown', Dropdown);
app.component('Tooltip', Tooltip);

app.mount('#app');
