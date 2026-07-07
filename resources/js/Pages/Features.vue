<template>
  <PublicLayout>
    <div class="py-16 md:py-24">
      <!-- Hero Header -->
      <div class="container mx-auto px-4 md:px-6 text-center mb-16 md:mb-24">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">
          Supercharge your operations with <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">powerful tools.</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 max-w-3xl mx-auto">
          StockSync comes loaded with everything you need to run, scale, and automate your inventory operations. No more spreadsheet headaches.
        </p>
      </div>

      <!-- Features Grid -->
      <div class="container mx-auto px-4 md:px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20 md:mb-32">
        <!-- Feature 1: Real-time Stock Tracking -->
        <div class="group bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-8 hover:shadow-xl hover:border-primary-500/20 dark:hover:border-primary-500/30 transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-primary-50 dark:bg-primary-950/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Real-time Stock Tracking</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Instantly view quantities on hand, reserved stock, and incoming shipments. Automatic low-stock alerts keep your shelves full.
          </p>
        </div>

        <!-- Feature 2: Supplier Coordination -->
        <div class="group bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-8 hover:shadow-xl hover:border-primary-500/20 dark:hover:border-primary-500/30 transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-primary-50 dark:bg-primary-950/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Supplier Portal & POs</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Create purchase orders, manage directories, and track fulfillment cycles. Benchmark delivery speed and pricing history.
          </p>
        </div>

        <!-- Feature 3: Smart Order Fulfillment -->
        <div class="group bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-8 hover:shadow-xl hover:border-primary-500/20 dark:hover:border-primary-500/30 transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-primary-50 dark:bg-primary-950/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Omnichannel Fulfillment</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Sync sales across multiple web storefronts and physical stores. Auto-route orders to nearest warehouses to optimize shipping.
          </p>
        </div>

        <!-- Feature 4: Forecasting & Analytics -->
        <div class="group bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-8 hover:shadow-xl hover:border-primary-500/20 dark:hover:border-primary-500/30 transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-primary-50 dark:bg-primary-950/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Advanced Forecasting</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Avoid stockouts and overstock costs. Smart algorithms calculate run-rate and predict when to reorder based on historical seasons.
          </p>
        </div>

        <!-- Feature 5: Multi-Warehouse Management -->
        <div class="group bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-8 hover:shadow-xl hover:border-primary-500/20 dark:hover:border-primary-500/30 transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-primary-50 dark:bg-primary-950/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Multi-Warehouse Support</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Track assets across multiple physical sites. Easily coordinate internal stock transfers, bin positioning, and audits.
          </p>
        </div>

        <!-- Feature 6: Granular Role Permissions -->
        <div class="group bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-8 hover:shadow-xl hover:border-primary-500/20 dark:hover:border-primary-500/30 transition-all duration-300 hover:-translate-y-1">
          <div class="w-12 h-12 bg-primary-50 dark:bg-primary-950/50 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Role-based Access</h3>
          <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Maintain top security controls. Assign custom permissions for staff, managers, and warehouse audits with detailed activity logs.
          </p>
        </div>
      </div>

      <!-- Secondary Call-To-Action Banner -->
      <div class="container mx-auto px-4 md:px-6">
        <div class="bg-gradient-to-r from-primary-900 to-indigo-900 dark:from-gray-900 dark:to-gray-900 border border-transparent dark:border-gray-800 rounded-3xl p-12 text-center text-white relative overflow-hidden shadow-xl">
          <div class="relative z-10 max-w-2xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to automate your inventory?</h2>
            <p class="text-primary-100 dark:text-gray-400 mb-8 text-base">
              Take less than 2 minutes to get set up and start tracking. No credit card required.
            </p>
            <Link href="/register" class="inline-block bg-white text-primary-900 hover:bg-gray-100 font-bold px-8 py-3.5 rounded-xl shadow-lg hover:scale-105 transition-all">
              Get Started Free
            </Link>
          </div>
          <!-- Decorative blur background circles -->
          <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-600/30 rounded-full blur-3xl pointer-events-none"></div>
          <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import PublicLayout from '../Layouts/PublicLayout.vue';
</script>
