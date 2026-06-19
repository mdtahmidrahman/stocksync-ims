<template>
  <div>
    <!-- Mobile Overlay -->
    <div 
      v-if="isMobileMenuOpen" 
      @click="closeMobileMenu"
      class="fixed inset-0 bg-gray-900/50 z-40 md:hidden backdrop-blur-sm"
    ></div>

    <!-- Sidebar Drawer -->
    <aside 
      :class="[
        'w-64 bg-primary-900 flex flex-col h-screen fixed top-0 left-0 shadow-xl z-50 transition-transform duration-300 md:translate-x-0',
        isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full'
      ]"
    >
      <!-- Logo Area -->
      <div class="h-20 flex items-center justify-between px-6 border-b border-primary-800/50">
        <BrandLogo theme="dark" size="lg" />
        <button @click="closeMobileMenu" class="md:hidden text-primary-200 hover:text-white p-1">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <!-- Navigation -->
      <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1 custom-scrollbar">
        <router-link to="/dashboard" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
          Overview
        </router-link>
        
        <router-link v-if="['admin', 'manager'].includes(currentUserRole)" to="/products" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
          Products
        </router-link>

        <router-link v-if="['admin', 'manager'].includes(currentUserRole)" to="/suppliers" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          Suppliers
        </router-link>

        <router-link v-if="['admin', 'manager'].includes(currentUserRole)" to="/categories" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
          Categories
        </router-link>
        
        <router-link to="/sales" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
          Sales & POS
        </router-link>

        <router-link to="/orders" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
          Orders
        </router-link>

        <router-link to="/customers" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          Customers
        </router-link>

        <router-link to="/inventory" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
          Inventory
        </router-link>

        <router-link v-if="['admin', 'manager'].includes(currentUserRole)" to="/warehouses" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          Warehouses
        </router-link>

        <router-link v-if="['admin', 'manager'].includes(currentUserRole)" to="/purchases" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
          <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Purchases
        </router-link>

        <div v-if="['admin', 'manager'].includes(currentUserRole)" class="pt-4 mt-4 border-t border-primary-800/50 space-y-1">
          <router-link to="/payments" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
            <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Payments
          </router-link>

          <router-link to="/reports" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
            <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Reports
          </router-link>

          <router-link v-if="currentUserRole === 'admin'" to="/roles" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
            <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Roles
          </router-link>

          <router-link to="/support" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
            <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            Support
          </router-link>

          <router-link v-if="currentUserRole === 'admin'" to="/settings" @click="closeMobileMenu" class="nav-link" active-class="nav-active">
            <svg class="w-5 h-5 mr-3 opacity-75 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            Settings
          </router-link>
        </div>
      </div>

      <!-- User Profile Area -->
      <div class="p-4 border-t border-primary-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-primary-800 flex items-center justify-center text-primary-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
          </div>
          <div>
            <p class="text-sm font-semibold text-white">Demo User</p>
            <p class="text-xs text-primary-300">{{ displayRole }}</p>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <button class="relative text-primary-300 hover:text-white p-2 rounded-lg hover:bg-primary-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-primary-900"></span>
          </button>
          <button class="text-primary-300 hover:text-white p-2 rounded-lg hover:bg-primary-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
          </button>
        </div>
      </div>
    </aside>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import BrandLogo from './BrandLogo.vue';
import { useAppState } from '../Composables/useAppState';

const { isMobileMenuOpen, closeMobileMenu, currentUserRole } = useAppState();

const displayRole = computed(() => {
  if (currentUserRole.value === 'admin') return 'Super Admin';
  if (currentUserRole.value === 'manager') return 'Store Manager';
  return 'Floor Staff';
});
</script>

<style scoped>
.nav-link {
  @apply flex items-center px-3 py-2.5 rounded-lg text-sm font-medium text-primary-100 hover:bg-primary-800 transition-colors;
}
.nav-active {
  @apply bg-primary-800 text-white shadow-sm;
}

/* Custom Scrollbar for Sidebar */
.custom-scrollbar::-webkit-scrollbar {
  width: 6px !important;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent !important;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  @apply bg-primary-800 rounded-full;
  border: none !important;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  @apply bg-primary-700;
}
</style>
