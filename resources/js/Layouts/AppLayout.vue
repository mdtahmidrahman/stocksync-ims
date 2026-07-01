<template>
  <div class="flex min-h-screen bg-gray-50 dark:bg-black transition-colors">
    <Sidebar />
    
    <div :class="[
      'flex-1 flex flex-col h-screen overflow-hidden transition-all duration-300 ease-in-out',
      isSidebarCollapsed ? 'md:ml-20' : 'md:ml-64'
    ]">
      <!-- Top header -->
      <header class="h-16 px-4 md:px-8 flex items-center justify-between md:justify-end bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 shrink-0 transition-colors">
         <!-- Mobile Hamburger -->
         <button @click="toggleMobileMenu" class="md:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
         </button>

         <div class="flex items-center gap-2 md:gap-4">
             <Tooltip text="Toggle Theme" position="bottom">
               <ThemeToggle />
             </Tooltip>
             
              <Tooltip text="Notifications" position="bottom">
                <button @click="showNotificationsPanel = true" class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                   <span class="absolute top-1 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-900"></span>
                </button>
              </Tooltip>
         </div>
      </header>
      
      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto px-4 py-6 md:px-8 md:py-8">
        <router-view></router-view>
      </main>
    </div>

    <!-- Notifications Slide-over Panel -->
    <div v-if="showNotificationsPanel" @click="showNotificationsPanel = false" class="fixed inset-0 bg-gray-900/20 backdrop-blur-sm z-50 transition-opacity"></div>
    <div :class="[
      'fixed inset-y-0 right-0 w-full sm:max-w-sm bg-white dark:bg-gray-900 shadow-2xl z-50 transform transition-transform duration-300 ease-in-out flex flex-col',
      showNotificationsPanel ? 'translate-x-0' : 'translate-x-full'
    ]">
      <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-900/50">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
          Notifications
          <span class="bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 text-xs px-2 py-0.5 rounded-full">3 New</span>
        </h2>
        <button @click="showNotificationsPanel = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>
      
      <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-3">
        <!-- Notification 1 -->
        <div class="p-4 bg-red-50 dark:bg-red-900/10 rounded-xl border border-red-100 dark:border-red-900/30">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0 mt-0.5 text-red-600 dark:text-red-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
              <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Smart Reorder Triggered</h4>
              <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Draft PO created for <span class="font-medium">Wireless Headphones</span>. Stock fell below 20 units.</p>
              <div class="flex gap-2 mt-2">
                <button class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">Review PO</button>
              </div>
              <span class="text-[10px] font-medium text-gray-400 mt-2 block">Just now</span>
            </div>
          </div>
        </div>
        
        <!-- Notification 2 -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:border-gray-300 dark:hover:border-gray-600 transition-colors cursor-pointer">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0 mt-0.5 text-blue-600 dark:text-blue-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
              <h4 class="text-sm font-semibold text-gray-900 dark:text-white">New PO Approved</h4>
              <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Manager Sarah Jenkins approved PO-1025 for $4,500.</p>
              <span class="text-[10px] font-medium text-gray-400 mt-2 block">1h ago</span>
            </div>
          </div>
        </div>
        
        <!-- Notification 3 -->
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:border-gray-300 dark:hover:border-gray-600 transition-colors cursor-pointer opacity-75">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center shrink-0 mt-0.5 text-green-600 dark:text-green-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
              <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Transfer Completed</h4>
              <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Central Hub received 50 units of TRF-0009.</p>
              <span class="text-[10px] font-medium text-gray-400 mt-2 block">Yesterday</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
        <button class="w-full py-2 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 border border-primary-100 dark:border-primary-900/50 rounded-lg transition-colors">Mark all as read</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from '../Components/Sidebar.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';
import Tooltip from '../Components/Tooltip.vue';
import { useAppState } from '../Composables/useAppState';

const { toggleMobileMenu, isSidebarCollapsed } = useAppState();
const showNotificationsPanel = ref(false);
</script>
