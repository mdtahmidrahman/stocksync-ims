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
                    <span v-if="unreadCount > 0" class="absolute top-1 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-900"></span>
                 </button>
              </Tooltip>
         </div>
      </header>
      
      <!-- Main Content Area -->
      <main class="flex-1 overflow-y-auto px-4 py-6 md:px-8 md:py-8">
        <slot></slot>
      </main>
    </div>

    <!-- Notifications Slide-over Panel -->
    <NotificationSlideOver 
      v-model:isOpen="showNotificationsPanel" 
      @update:unreadCount="unreadCount = $event"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from '../Components/Sidebar.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';
import Tooltip from '../Components/Tooltip.vue';
import NotificationSlideOver from '../Components/NotificationSlideOver.vue';
import { useAppState } from '../Composables/useAppState';

const { toggleMobileMenu, isSidebarCollapsed } = useAppState();
const showNotificationsPanel = ref(false);
const unreadCount = ref(0);
</script>
