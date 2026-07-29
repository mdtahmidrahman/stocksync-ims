<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Reports & Analytics</h1>
      <div class="flex items-center gap-2">
        <Dropdown align="right" width="48">
          <template #trigger>
            <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
              {{ selectedRange }}
              <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
          </template>
          <template #content="{ close }">
            <a href="#" v-for="range in ['Last 30 Days', 'This Month', 'This Year']" :key="range" @click.prevent="changeRange(range); close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="selectedRange === range ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ range }}</a>
          </template>
        </Dropdown>
        <a :href="`/reports/export?range=${encodeURIComponent(selectedRange)}`" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
          Export PDF
        </a>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
      <!-- Card 1 -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Purchase</div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ currencySymbol }}{{ formatNumber(metrics.totalPurchases) }}</div>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
        </div>
        <div>
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales</div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ currencySymbol }}{{ formatNumber(metrics.totalSales) }}</div>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div>
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Purchase Due</div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ currencySymbol }}{{ formatNumber(metrics.purchaseDue) }}</div>
        </div>
      </div>
      <!-- Card 4 -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex items-center gap-4 transition-colors">
        <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/50 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <div>
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Sales Due</div>
          <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ currencySymbol }}{{ formatNumber(metrics.salesDue) }}</div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Chart -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-colors">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Sales & Purchase Overview (Last 6 Months)</h3>
        <div class="h-64 flex items-end justify-between gap-2 pb-6 border-b border-gray-100 dark:border-gray-800 relative">
          <div class="w-full flex justify-around items-end h-full mt-4">
            <div v-for="(m, idx) in chartMonths" :key="idx" class="flex flex-col items-center gap-1">
              <div class="flex items-end gap-1 h-48">
                <div class="w-4 md:w-6 bg-blue-500 rounded-t-sm transition-all" :style="{ height: getBarHeight(m.purchases) + '%' }" :title="`Purchases: ${currencySymbol}${m.purchases}`"></div>
                <div class="w-4 md:w-6 bg-primary-500 rounded-t-sm transition-all" :style="{ height: getBarHeight(m.sales) + '%' }" :title="`Sales: ${currencySymbol}${m.sales}`"></div>
              </div>
              <span class="text-xs text-gray-500 dark:text-gray-400 mt-2 font-medium">{{ m.label }}</span>
            </div>
          </div>
        </div>
        <div class="flex justify-center gap-6 mt-4">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
            <span class="text-sm text-gray-500 dark:text-gray-400">Purchases</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-primary-500"></div>
            <span class="text-sm text-gray-500 dark:text-gray-400">Sales</span>
          </div>
        </div>
      </div>

      <!-- Low Stock Alert -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-0 transition-colors flex flex-col">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quantity Alert (Low Stock)</h3>
        </div>
        <div class="overflow-y-auto flex-1">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-3 font-semibold">SKU</th>
                <th class="px-6 py-3 font-semibold">Product Name</th>
                <th class="px-6 py-3 font-semibold text-right">Stock Left</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
              <tr v-if="!lowStockProducts || lowStockProducts.length === 0">
                <td colspan="3" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">No low stock items! All products are adequately stocked.</td>
              </tr>
              <tr v-for="product in lowStockProducts" :key="product.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <td class="px-6 py-3 text-sm font-medium text-primary-600 dark:text-primary-400">{{ product.sku }}</td>
                <td class="px-6 py-3 text-sm text-gray-900 dark:text-white">{{ product.name }}</td>
                <td class="px-6 py-3 text-sm text-right">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300">
                    {{ product.stock_quantity }} left
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Dropdown from '../Components/Dropdown.vue';
import { useCurrency } from '../Composables/useCurrency';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  metrics: {
    type: Object,
    default: () => ({ totalPurchases: 0, totalSales: 0, purchaseDue: 0, salesDue: 0 }),
  },
  chartMonths: {
    type: Array,
    default: () => [],
  },
  lowStockProducts: {
    type: Array,
    default: () => [],
  },
  dateRange: {
    type: String,
    default: 'Last 30 Days',
  },
});

const { currencySymbol } = useCurrency();
const selectedRange = ref(props.dateRange);

const changeRange = (newRange) => {
  selectedRange.value = newRange;
  router.get('/reports', { range: newRange }, { preserveState: true, preserveScroll: true });
};

const formatNumber = (num) => {
  return Number(num || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const getBarHeight = (val) => {
  const maxVal = Math.max(...props.chartMonths.flatMap(m => [m.sales, m.purchases]), 1);
  return Math.min(100, Math.max(10, Math.round((val / maxVal) * 100)));
};
</script>
