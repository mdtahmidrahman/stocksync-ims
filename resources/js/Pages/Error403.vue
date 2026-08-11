<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="text-center max-w-2xl mx-auto z-10">
      <div class="flex justify-center mb-6">
        <div class="w-24 h-24 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center border border-red-200 dark:border-red-800/50 shadow-xl shadow-red-500/10">
          <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
          </svg>
        </div>
      </div>

      <h1 class="text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-red-400 tracking-tighter mb-4 drop-shadow-sm">
        403
      </h1>
      
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
        Access Denied.
      </h2>
      
      <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 mb-10 px-4">
        You don't have permission to access this page. If you believe this is a mistake, please contact your system administrator to adjust your role.
      </p>
      
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <Link href="/dashboard" class="w-full sm:w-auto bg-primary-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-primary-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-primary-500/20 active:translate-y-0 flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
          Return to Dashboard
        </Link>
        
        <button @click="goBack" class="w-full sm:w-auto bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-800 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm active:translate-y-0">
          Go Back
        </button>
      </div>
    </div>
    
    <!-- Decorative abstract shapes matching landing page vibe but with red warning theme -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 pointer-events-none w-full max-w-4xl opacity-20 dark:opacity-10 blur-3xl">
      <div class="aspect-[1155/678] w-full bg-gradient-to-tr from-red-400 to-primary-500 rounded-full"></div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useAppState } from '../Composables/useAppState';

// Initialize app state so theme preferences (dark mode) apply
const { isDark } = useAppState();

const goBack = () => {
  if (window.history.length > 1) {
    window.history.back();
  } else {
    window.location.href = '/dashboard';
  }
};
</script>
