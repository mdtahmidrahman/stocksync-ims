<template>
  <PublicLayout>
    <!-- Hero Section -->
    <main class="container mx-auto px-4 md:px-6 pt-12 md:pt-20 pb-20 md:pb-32 text-center">
      <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold text-gray-900 dark:text-white tracking-tight mb-6 md:mb-8 max-w-4xl mx-auto leading-tight">
        Inventory management that feels like <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">magic.</span>
      </h1>
      <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 mb-8 md:mb-10 max-w-2xl mx-auto px-4">
        Track stock, manage suppliers, and fulfill orders seamlessly. Stop using spreadsheets and upgrade to StockSync today.
      </p>
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <template v-if="isAuthenticated">
          <Link :href="currentUserRole === 'super_admin' ? '/platform' : '/dashboard'" class="w-full sm:w-auto bg-primary-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-primary-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-primary-500/20 active:translate-y-0">
            {{ currentUserRole === 'super_admin' ? 'Go to Platform' : 'Go to Dashboard' }}
          </Link>
        </template>
        <template v-else>
          <Link href="/register" class="w-full sm:w-auto bg-primary-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-primary-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-primary-500/20 active:translate-y-0">
            Start Free Trial
          </Link>
          <Link href="/login" class="w-full sm:w-auto bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-800 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm active:translate-y-0">
            Sign In to Portal
          </Link>
        </template>
      </div>
      
      <!-- Hero Image -->
      <div class="mt-16 md:mt-20 relative max-w-6xl mx-auto px-4 md:px-0">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden flex flex-col">
          <!-- Window Header -->
          <div class="w-full h-8 md:h-12 bg-gray-50 dark:bg-black border-b border-gray-200 dark:border-gray-800 flex items-center px-4 gap-2 shrink-0">
            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-red-400"></div>
            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-yellow-400"></div>
            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-green-400"></div>
          </div>
          <img :src="isDark ? '/images/landing-page-dark.png' : '/images/landing-page-light.png'" alt="Dashboard Preview" class="w-full h-auto" />
        </div>
      </div>
    </main>
  </PublicLayout>
</template>

<script setup>
import PublicLayout from '../Layouts/PublicLayout.vue';
import { useAppState } from '../Composables/useAppState';
import { Link } from '@inertiajs/vue3';

const { isDark, isAuthenticated, currentUserRole } = useAppState();
</script>
