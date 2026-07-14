<template>
  <AppLayout>
    <div class="space-y-6">
      <!-- Command Header -->
      <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
        <div>
          <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Welcome back, {{ currentUser?.name || 'Demo User' }}!</h1>
          <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Here's your inventory command center for today, June 22, 2026.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
          <Link href="/sales" class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            New Sale
          </Link>
          <Link href="/purchases" class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            Create PO
          </Link>
          <Link href="/products" class="bg-primary-600 text-white border border-transparent px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Add Product
          </Link>
        </div>
      </div>
      
      <!-- Actionable KPI Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-950/30 dark:text-green-400 px-2 py-0.5 rounded-md flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
              12.4%
            </span>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Monthly Sales</div>
            <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ currencySymbol }}45,231.89</div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-950/30 dark:text-red-400 px-2 py-0.5 rounded-md flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
              3.2%
            </span>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Products Stocked</div>
            <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">1,245</div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-yellow-700 bg-yellow-100/50 dark:bg-yellow-950/30 dark:text-yellow-400 px-2.5 py-0.5 rounded-md flex items-center gap-1">
              Active Warnings
            </span>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Low Stock SKUs</div>
            <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">18</div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-950/30 dark:text-green-400 px-2 py-0.5 rounded-md flex items-center gap-1">
              99.98% SLA
            </span>
          </div>
          <div>
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Fulfillment Rate</div>
            <div class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">98.2%</div>
          </div>
        </div>
      </div>

      <!-- Advanced Analytics Charts -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales vs Restock (Bar Chart) -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h2 class="text-lg font-bold text-gray-900 dark:text-white">Sales vs. Restock</h2>
              <p class="text-xs font-medium text-gray-400 mt-0.5">Weekly volume index performance</p>
            </div>
            <div class="flex items-center gap-3">
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 dark:text-gray-400">
                <span class="w-3 h-3 rounded-full bg-primary-600"></span> Sold
              </span>
              <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 dark:text-gray-400">
                <span class="w-3 h-3 rounded-full bg-primary-300 dark:bg-primary-800/80"></span> Restocked
              </span>
            </div>
          </div>
          
          <div class="flex items-end justify-between gap-4 h-52 mt-4 px-2">
            <div v-for="(day, index) in salesRestockChart" :key="index" class="flex-1 flex flex-col items-center gap-2 group relative">
              <div class="w-full flex justify-center gap-1 items-end h-full">
                <!-- Sold Bar -->
                <div 
                  class="w-3 bg-primary-600 hover:bg-primary-500 rounded-t-sm transition-all duration-300 relative cursor-pointer"
                  :style="{ height: `${day.soldHeight}px` }"
                >
                  <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-black text-white text-[10px] font-bold px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap shadow">
                    Sold: {{ day.itemsSold }}
                  </div>
                </div>
                <!-- Restocked Bar -->
                <div 
                  class="w-3 bg-primary-300 dark:bg-primary-800/50 hover:bg-primary-400 dark:hover:bg-primary-800 rounded-t-sm transition-all duration-300 relative cursor-pointer"
                  :style="{ height: `${day.restockedHeight}px` }"
                >
                  <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 bg-black text-white text-[10px] font-bold px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity z-10 whitespace-nowrap shadow">
                    Stock: {{ day.itemsRestocked }}
                  </div>
                </div>
              </div>
              <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-2">{{ day.label }}</span>
            </div>
          </div>
        </div>

        <!-- Stock Status (Donut Chart representation) -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
          <div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Stock Status Summary</h2>
            <p class="text-xs font-medium text-gray-400 mt-0.5">Real-time status overview</p>
          </div>
          
          <div class="relative flex items-center justify-center py-6">
            <!-- Simulated Donut Chart using Tailwind ring utilities -->
            <div class="w-32 h-32 rounded-full border-8 border-primary-100 dark:border-primary-950 flex items-center justify-center relative">
              <div class="absolute inset-0 w-full h-full rounded-full border-8 border-transparent border-t-primary-600 border-r-primary-500 transform -rotate-45"></div>
              <div class="text-center">
                <span class="text-2xl font-bold text-gray-900 dark:text-white">82%</span>
                <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Healthy</span>
              </div>
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex justify-between items-center text-sm">
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span> Healthy (In Stock)
              </span>
              <span class="font-bold text-gray-900 dark:text-white">1,021</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Low Stock Alert
              </span>
              <span class="font-bold text-gray-900 dark:text-white">188</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Out of Stock
              </span>
              <span class="font-bold text-gray-900 dark:text-white">36</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Row: Granular Insights -->
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
        <!-- Top Performing Products -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
          <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Top Performing Products</h2>
            <Link href="/products" class="text-sm font-semibold text-primary-600 hover:text-primary-800">View All</Link>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
              <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400">
                <tr>
                  <th class="p-4 font-semibold">Product</th>
                  <th class="p-4 font-semibold text-right">Units Sold</th>
                  <th class="p-4 font-semibold text-center">7-Day Trend</th>
                  <th class="p-4 font-semibold text-right">Revenue</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                <tr v-for="i in 4" :key="i" class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                  <td class="p-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center shrink-0 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900 dark:text-white">Wireless Headphones Pro {{ i }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">TECH-{{ 100 + i }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="p-4 text-right font-medium text-gray-900 dark:text-white">{{ 245 - (i * 20) }}</td>
                  <td class="p-4">
                    <div class="flex items-end justify-center gap-1 h-8">
                      <div class="w-1.5 bg-primary-200 dark:bg-primary-900/50 rounded-t-sm" :style="{ height: `${20 + i * 5}%` }"></div>
                      <div class="w-1.5 bg-primary-200 dark:bg-primary-900/50 rounded-t-sm" :style="{ height: `${40 - i * 5}%` }"></div>
                      <div class="w-1.5 bg-primary-200 dark:bg-primary-900/50 rounded-t-sm" :style="{ height: `${30 + i * 10}%` }"></div>
                      <div class="w-1.5 bg-primary-300 dark:bg-primary-800/50 rounded-t-sm" :style="{ height: `${60 - i * 5}%` }"></div>
                      <div class="w-1.5 bg-primary-400 dark:bg-primary-700/50 rounded-t-sm" :style="{ height: `${50 + i * 5}%` }"></div>
                      <div class="w-1.5 bg-primary-500 dark:bg-primary-600/50 rounded-t-sm" :style="{ height: `${80 - i * 10}%` }"></div>
                      <div class="w-1.5 bg-primary-600 dark:bg-primary-500 rounded-t-sm" :style="{ height: `${100 - i * 5}%` }"></div>
                    </div>
                  </td>
                  <td class="p-4 text-right font-medium text-green-600 dark:text-green-400">${{ (245 - (i * 20)) * 120 }}.00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Recent Activity Timeline -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
          <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Activity</h2>
            <Dropdown align="right" width="48">
              <template #trigger>
                <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <Link href="/audit-log" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" @click="close">View All Activity</Link>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" @click.prevent="close">Export Log</a>
              </template>
            </Dropdown>
          </div>
          
          <div class="relative border-l border-gray-200 dark:border-gray-700 ml-3 space-y-6">
            <div class="relative pl-6">
              <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-green-500 ring-4 ring-white dark:ring-gray-900"></div>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">New POS Sale Completed</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Invoice INV-2024-1005 for $350.00</p>
              <span class="text-xs font-medium text-gray-400 mt-1 inline-block">10 mins ago</span>
            </div>
            <div class="relative pl-6">
              <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-blue-500 ring-4 ring-white dark:ring-gray-900"></div>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">Purchase Order Created</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PO-1025 created for Demo Company 1</p>
              <span class="text-xs font-medium text-gray-400 mt-1 inline-block">10 hour ago</span>
            </div>
            <div class="relative pl-6">
              <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-primary-500 ring-4 ring-white dark:ring-gray-900"></div>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">Stock Adjustment</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">50 units added to TECH-102</p>
              <span class="text-xs font-medium text-gray-400 mt-1 inline-block">2 hours ago</span>
            </div>
            <div class="relative pl-6">
              <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-yellow-500 ring-4 ring-white dark:ring-gray-900"></div>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">Low Stock Warning Triggered</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">TECH-205 dropped below minimum threshold (10 units)</p>
              <span class="text-xs font-medium text-gray-400 mt-1 inline-block">Yesterday</span>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { useCurrency } from '../Composables/useCurrency';

const { currencySymbol } = useCurrency();
import { useAppState } from '../Composables/useAppState';

const { currentUser } = useAppState();

const weeklySalesRestock = [
  { label: 'Mon', itemsSold: 84, itemsRestocked: 34 },
  { label: 'Tue', itemsSold: 112, itemsRestocked: 46 },
  { label: 'Wed', itemsSold: 76, itemsRestocked: 28 },
  { label: 'Thu', itemsSold: 138, itemsRestocked: 62 },
  { label: 'Fri', itemsSold: 156, itemsRestocked: 74 },
  { label: 'Sat', itemsSold: 121, itemsRestocked: 51 },
  { label: 'Sun', itemsSold: 98, itemsRestocked: 39 },
];

const maxChartValue = Math.max(
  ...weeklySalesRestock.flatMap((day) => [day.itemsSold, day.itemsRestocked])
);

const maxBarHeight = 208;

const salesRestockChart = weeklySalesRestock.map((day) => ({
  ...day,
  soldHeight: Math.round((day.itemsSold / maxChartValue) * maxBarHeight),
  restockedHeight: Math.round((day.itemsRestocked / maxChartValue) * maxBarHeight),
}));
</script>
