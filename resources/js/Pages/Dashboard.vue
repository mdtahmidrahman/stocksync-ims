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
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between min-w-0">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-950/30 dark:text-green-400 px-2 py-0.5 rounded-md flex items-center gap-1 shrink-0">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
              12.4%
            </span>
          </div>
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 truncate" title="Total Inventory Value">Total Inventory Value</div>
            <AutoFitText 
              :value="`${currencySymbol}${Number(kpis.totalInventoryValue).toLocaleString('en-US', {minimumFractionDigits: 2})}`"
              custom-class="text-gray-900 dark:text-white mt-1"
            />
          </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between min-w-0">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-950/30 dark:text-red-400 px-2 py-0.5 rounded-md flex items-center gap-1 shrink-0">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg>
              3.2%
            </span>
          </div>
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 truncate" title="Low Stock Alerts">Low Stock Alerts</div>
            <AutoFitText 
              :value="kpis.lowStockCount"
              custom-class="text-gray-900 dark:text-white mt-1"
            />
          </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between min-w-0">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center text-yellow-600 dark:text-yellow-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-yellow-700 bg-yellow-100/50 dark:bg-yellow-950/30 dark:text-yellow-400 px-2.5 py-0.5 rounded-md flex items-center gap-1 shrink-0">
              Active Warnings
            </span>
          </div>
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 truncate" title="Today's Sales">Today's Sales</div>
            <AutoFitText 
              :value="`${currencySymbol}${Number(kpis.todaySales).toLocaleString('en-US', {minimumFractionDigits: 2})}`"
              custom-class="text-gray-900 dark:text-white mt-1"
            />
          </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between min-w-0">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400 shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <span class="text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-950/30 dark:text-green-400 px-2 py-0.5 rounded-md flex items-center gap-1 shrink-0">
              99.98% SLA
            </span>
          </div>
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-500 dark:text-gray-400 truncate" title="Pending Deliveries (POs)">Pending Deliveries (POs)</div>
            <AutoFitText 
              :value="kpis.pendingDeliveries"
              custom-class="text-gray-900 dark:text-white mt-1"
            />
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
            <!-- Dynamic SVG Donut Chart -->
            <div class="relative w-32 h-32">
              <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="10" fill="none" class="text-primary-50 dark:text-gray-800" />
                <circle cx="50" cy="50" r="40" stroke="#4F46E5" stroke-width="10" fill="none" 
                        :stroke-dasharray="251.3" 
                        :stroke-dashoffset="251.3 - (251.3 * stockStatus.percentage) / 100" 
                        stroke-linecap="round" class="transition-all duration-1000 ease-out" />
              </svg>
              <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ stockStatus.percentage }}%</span>
                <span class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Healthy</span>
              </div>
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex justify-between items-center text-sm">
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <span class="w-2.5 h-2.5 rounded-full bg-primary-600"></span> Healthy (In Stock)
              </span>
              <span class="font-bold text-gray-900 dark:text-white">{{ stockStatus.healthy }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span> Low Stock Alert
              </span>
              <span class="font-bold text-gray-900 dark:text-white">{{ stockStatus.low }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
              <span class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Out of Stock
              </span>
              <span class="font-bold text-gray-900 dark:text-white">{{ stockStatus.out }}</span>
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
                <tr v-for="item in topProducts" :key="item.product_id" class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                  <td class="p-4">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center shrink-0 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900 dark:text-white">{{ item.product?.name || 'Unknown' }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ item.product?.sku || 'N/A' }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="p-4 text-right font-medium text-gray-900 dark:text-white">{{ item.total_sold }}</td>
                  <td class="p-4">
                    <div class="flex items-end justify-center gap-1 h-8">
                      <!-- Dummy sparkline for visual effect -->
                      <div class="w-1.5 bg-primary-200 dark:bg-primary-900/50 rounded-t-sm" :style="{ height: `${20 + Math.random() * 50}%` }"></div>
                      <div class="w-1.5 bg-primary-200 dark:bg-primary-900/50 rounded-t-sm" :style="{ height: `${40 - Math.random() * 20}%` }"></div>
                      <div class="w-1.5 bg-primary-300 dark:bg-primary-800/50 rounded-t-sm" :style="{ height: `${60 - Math.random() * 20}%` }"></div>
                      <div class="w-1.5 bg-primary-400 dark:bg-primary-700/50 rounded-t-sm" :style="{ height: `${50 + Math.random() * 30}%` }"></div>
                      <div class="w-1.5 bg-primary-600 dark:bg-primary-500 rounded-t-sm" :style="{ height: `${100 - Math.random() * 20}%` }"></div>
                    </div>
                  </td>
                  <td class="p-4 text-right font-medium text-green-600 dark:text-green-400">{{ currencySymbol }}{{ Number(item.total_revenue).toLocaleString('en-US', {minimumFractionDigits: 2}) }}</td>
                </tr>
                <tr v-if="topProducts.length === 0">
                    <td colspan="4" class="p-4 text-center text-gray-500 dark:text-gray-400">No sales data available.</td>
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
            <div v-for="(activity, index) in recentActivity" :key="index" class="relative pl-6">
              <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full ring-4 ring-white dark:ring-gray-900" :class="activity.type === 'sale' ? 'bg-green-500' : 'bg-blue-500'"></div>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ activity.title }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ activity.description }}</p>
              <span class="text-xs font-medium text-gray-400 mt-1 inline-block">{{ activity.time }}</span>
            </div>
            
            <div v-for="(alert, index) in attentionFeed" :key="'alert-'+index" class="relative pl-6">
              <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-yellow-500 ring-4 ring-white dark:ring-gray-900"></div>
              <p class="text-sm font-semibold text-gray-900 dark:text-white">Attention Required</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><Link :href="`/purchases`" class="hover:underline">{{ alert.message }}</Link></p>
              <span class="text-xs font-medium text-gray-400 mt-1 inline-block">Triggered</span>
            </div>

            <div v-if="!recentActivity.length && !attentionFeed.length" class="text-sm text-gray-500">
                No recent activity.
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import AutoFitText from '../Components/AutoFitText.vue';
import { useCurrency } from '../Composables/useCurrency';

const { currencySymbol } = useCurrency();
import { useAppState } from '../Composables/useAppState';

const { currentUser } = useAppState();

const props = defineProps({
  kpis: Object,
  stockStatus: Object,
  weeklySalesRestock: Array,
  topProducts: Array,
  attentionFeed: Array,
  recentActivity: Array
});

// Since the data is pre-calculated in the controller, we can just use the prop directly
// Though `salesRestockChart` expects specific formatting which the controller matches.
const salesRestockChart = props.weeklySalesRestock;
</script>
