<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto">
      <h1 class="text-9xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400 tracking-tighter mb-4">
        404
      </h1>
      
      <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
        Oops! Page not found.
      </h2>
      
      <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 mb-10 px-4">
        We can't seem to find the page you're looking for. It might have been moved, deleted, or perhaps never existed at all.
      </p>
      
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <Link href="/" class="w-full sm:w-auto bg-primary-600 text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-primary-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-primary-500/20 active:translate-y-0 flex items-center justify-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
          Back to Home
        </Link>
        
        <button @click="window.history.back()" class="w-full sm:w-auto bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-800 px-8 py-4 rounded-xl text-lg font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm active:translate-y-0">
          Go Back
        </button>
      </div>
    </div>
    
    <!-- Decorative abstract shapes matching landing page vibe -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 pointer-events-none w-full max-w-4xl opacity-30 dark:opacity-20 blur-3xl">
      <div class="aspect-[1155/678] w-full bg-gradient-to-tr from-primary-200 to-primary-500 rounded-full"></div>
    </div>
  </div>
</template>

<script setup>
// No complex logic needed for 404 page
</script>
