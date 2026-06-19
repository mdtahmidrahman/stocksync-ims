<template>
  <div class="min-h-screen bg-gray-50 dark:bg-black transition-colors selection:bg-primary-500 selection:text-white">
    <!-- Topbar -->
    <header class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 shadow-sm sticky top-0 z-50 transition-colors">
      <div class="container mx-auto px-4 md:px-6 h-20 flex items-center justify-between">
        <BrandLogo size="lg" theme="dark" class="hidden dark:flex" />
        <BrandLogo size="lg" class="dark:hidden" />
        
        <nav class="hidden md:flex items-center space-x-10">
          <a href="#" class="text-sm font-semibold text-gray-900 dark:text-white border-b-2 border-primary-600 pb-1">Home</a>
          <a href="#features" class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Features</a>
          <a href="#pricing" class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">Pricing</a>
        </nav>
        
        <div class="flex items-center gap-2 md:gap-4">
          <ThemeToggle />
          <router-link to="/login" class="hidden md:flex text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 border border-transparent transition-colors">
            Login
          </router-link>
          <router-link to="/signup" class="bg-primary-600 text-white px-4 md:px-5 py-2 md:py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
            Get Started
          </router-link>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <main class="container mx-auto px-4 md:px-6 pt-12 md:pt-20 pb-20 md:pb-32 text-center">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-medium mb-8">
        <span class="flex h-2 w-2 rounded-full bg-primary-600 dark:bg-primary-400"></span>
        StockSync 2.0 is now live!
      </div>
      <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold text-gray-900 dark:text-white tracking-tight mb-6 md:mb-8 max-w-4xl mx-auto leading-tight">
        Inventory management that feels like <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">magic.</span>
      </h1>
      <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 mb-8 md:mb-10 max-w-2xl mx-auto px-4">
        Track stock, manage suppliers, and fulfill orders seamlessly. Stop using spreadsheets and upgrade to StockSync today.
      </p>
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <router-link to="/signup" class="w-full sm:w-auto bg-primary-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-primary-700 transition-colors shadow-lg shadow-primary-500/30">
          Start your free trial
        </router-link>
        <router-link to="/dashboard" class="w-full sm:w-auto bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-800 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
          View Live Demo
        </router-link>
      </div>
      
      <!-- Hero Image/Mockup placeholder -->
      <div class="mt-16 md:mt-20 relative max-w-5xl mx-auto px-4 md:px-0">
        <div class="absolute inset-0 bg-gradient-to-t from-gray-50 dark:from-gray-900 via-transparent to-transparent z-10 bottom-0 h-32 hidden md:block"></div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-2xl overflow-hidden aspect-video flex items-center justify-center relative">
          <!-- Fake UI Header -->
          <div class="absolute top-0 w-full h-8 md:h-12 bg-gray-50 dark:bg-black border-b border-gray-200 dark:border-gray-800 flex items-center px-4 gap-2">
            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-red-400"></div>
            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-yellow-400"></div>
            <div class="w-2.5 h-2.5 md:w-3 md:h-3 rounded-full bg-green-400"></div>
          </div>
          <p class="text-gray-400 dark:text-gray-500 font-medium text-lg md:text-xl mt-8 md:mt-12">Dashboard Preview Image</p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import BrandLogo from '../Components/BrandLogo.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';
</script>
