<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Warehouses</h1>
      <button class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add New Warehouse
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Locations</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">3</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Capacity Used</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">68%</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Items in Transit</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">1,204</div>
      </div>
    </div>

    <!-- Grid View -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="(wh, i) in warehouses" :key="i" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col transition-colors">
        <div class="flex justify-between items-start mb-4">
          <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          </div>
          <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
          </button>
        </div>
        
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ wh.name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4 flex-1">{{ wh.address }}</p>
        
        <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-800">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Manager:</span>
            <span class="font-medium text-gray-900 dark:text-white">{{ wh.manager }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Total Items:</span>
            <span class="font-medium text-gray-900 dark:text-white">{{ wh.items.toLocaleString() }}</span>
          </div>
          
          <div class="pt-2">
            <div class="flex justify-between text-xs mb-1">
              <span class="font-medium text-gray-700 dark:text-gray-300">Capacity Used</span>
              <span class="font-medium text-gray-700 dark:text-gray-300">{{ wh.capacity }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
              <div class="bg-primary-500 h-1.5 rounded-full" :style="{ width: wh.capacity + '%' }"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const warehouses = [
    { name: 'Central Distribution Hub', address: '1200 Logistics Blvd, Chicago, IL 60607', manager: 'Michael Scott', items: 45200, capacity: 82 },
    { name: 'West Coast Storage', address: '450 Industrial Way, San Jose, CA 95112', manager: 'Sarah Jenkins', items: 18500, capacity: 45 },
    { name: 'East Coast Fulfillment', address: '780 Port Road, Newark, NJ 07102', manager: 'David Miller', items: 28900, capacity: 68 }
];
</script>
