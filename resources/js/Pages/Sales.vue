<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Sales & Invoices</h1>
      <button class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New POS Sale
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales Today</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">$4,250.00</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Invoices Generated</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">24</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Payments</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">$850.00</div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Toolbar -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="relative w-full sm:w-64">
          <input type="text" placeholder="Search invoices..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <select class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
              <option>All Statuses</option>
              <option>Paid</option>
              <option>Pending</option>
            </select>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Invoice #</th>
              <th class="p-4 font-semibold whitespace-nowrap">Customer</th>
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Total</th>
              <th class="p-4 font-semibold whitespace-nowrap">Status</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" v-for="i in 5" :key="i">
              <td class="p-4 text-sm font-medium text-primary-600 dark:text-primary-400">INV-2024-{{ 1000 + i }}</td>
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white">Walk-in Customer {{ i }}</div>
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">Today, 10:{{ 10 + i }} AM</td>
              <td class="p-4 text-gray-900 dark:text-white font-medium whitespace-nowrap">${{ 120 * i }}.00</td>
              <td class="p-4">
                <span :class="i % 3 === 0 ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ i % 3 === 0 ? 'Pending' : 'Paid' }}
                </span>
              </td>
              <td class="p-4 text-right whitespace-nowrap">
                <button class="text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 text-sm font-medium transition-colors">
                  Generate PDF
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>
