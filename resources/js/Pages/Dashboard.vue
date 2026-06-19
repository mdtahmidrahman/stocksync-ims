<template>
  <div class="space-y-6">
    <!-- Command Header -->
    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6">
      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Welcome back, Demo Name 👋</h1>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Here's your inventory command center for today, October 24, 2024.</p>
      </div>
      <div class="flex flex-wrap items-center gap-3">
        <router-link to="/sales" class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          New Sale
        </router-link>
        <router-link to="/purchases" class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          Create PO
        </router-link>
        <router-link to="/products" class="bg-primary-600 text-white border border-transparent px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          Add Product
        </router-link>
      </div>
    </div>
    
    <!-- Actionable KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
        <div class="flex items-start justify-between mb-2">
          <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <span class="inline-flex items-center text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded-md">
            +4.2%
          </span>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Inventory Value</h3>
          <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">$124,500</div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-red-100 dark:border-red-900/30 p-6 flex flex-col justify-between relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-red-500/10 rounded-full -mr-8 -mt-8 blur-2xl"></div>
        <div class="flex items-start justify-between mb-2 relative z-10">
          <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
          </div>
          <span class="inline-flex items-center text-xs font-semibold text-red-600 bg-red-50 dark:bg-red-900/30 px-2 py-1 rounded-md">
            Action Req.
          </span>
        </div>
        <div class="relative z-10">
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Low Stock Alerts</h3>
          <div class="flex items-baseline gap-2 mt-1">
            <div class="text-3xl font-bold text-red-600 dark:text-red-400">12</div>
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">SKUs</div>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
        <div class="flex items-start justify-between mb-2">
          <div class="w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
          </div>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Sales</h3>
          <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">$3,420</div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col justify-between">
        <div class="flex items-start justify-between mb-2">
          <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
          </div>
        </div>
        <div>
          <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Deliveries (POs)</h3>
          <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">4</div>
        </div>
      </div>
    </div>
    
    <!-- Middle Row: Analytics & Alerts -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      
      <!-- Visual Trends (Mock Chart) -->
      <div class="xl:col-span-2 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white">Sales Volume vs. Restock</h2>
          <select class="px-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 appearance-none">
            <option>Last 7 Days</option>
            <option>Last 30 Days</option>
          </select>
        </div>
        
        <div class="flex-1 flex items-end gap-2 sm:gap-4 h-64 mt-4 relative">
          <!-- Horizontal Grid Lines -->
          <div class="absolute inset-0 flex flex-col justify-between z-0">
            <div class="border-b border-gray-100 dark:border-gray-800 w-full h-0"></div>
            <div class="border-b border-gray-100 dark:border-gray-800 w-full h-0"></div>
            <div class="border-b border-gray-100 dark:border-gray-800 w-full h-0"></div>
            <div class="border-b border-gray-100 dark:border-gray-800 w-full h-0"></div>
            <div class="border-b border-gray-100 dark:border-gray-800 w-full h-0"></div>
          </div>
          
          <!-- Bars -->
          <div class="relative z-10 flex-1 flex flex-col justify-end items-center group" v-for="(day, i) in ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']" :key="i">
            <div class="w-full max-w-[40px] flex items-end gap-1 mb-2">
              <div class="w-1/2 bg-primary-500 hover:bg-primary-400 rounded-t-sm transition-all" :style="`height: ${Math.floor(Math.random() * 60 + 20)}%`"></div>
              <div class="w-1/2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-t-sm transition-all" :style="`height: ${Math.floor(Math.random() * 40 + 10)}%`"></div>
            </div>
            <span class="text-xs font-medium text-gray-500">{{ day }}</span>
          </div>
        </div>
        
        <div class="flex items-center justify-center gap-6 mt-6">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-primary-500"></div>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Items Sold</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-gray-200 dark:bg-gray-700"></div>
            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Items Restocked</span>
          </div>
        </div>
      </div>
      
      <!-- Attention Required Feed -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/30">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white">Attention Required</h2>
          <span class="bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 text-xs font-bold px-2 py-1 rounded-full">3 Tasks</span>
        </div>
        
        <div class="flex-1 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
          <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
              </div>
              <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Critical Stock Level</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">TECH-100 and 4 other SKUs have dropped below their minimum threshold.</p>
                <button class="mt-2 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">Reorder Now &rarr;</button>
              </div>
            </div>
          </div>
          
          <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              </div>
              <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Overdue Invoices</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2 invoices pending payment for more than 15 days.</p>
                <button class="mt-2 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">View Invoices &rarr;</button>
              </div>
            </div>
          </div>
          
          <div class="p-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
              </div>
              <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Awaiting Receipt</h4>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PO-1024 is scheduled for delivery today.</p>
                <button class="mt-2 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">Receive Items &rarr;</button>
              </div>
            </div>
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
          <router-link to="/products" class="text-sm font-semibold text-primary-600 hover:text-primary-800">View All</router-link>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400">
              <tr>
                <th class="p-4 font-semibold">Product</th>
                <th class="p-4 font-semibold text-right">Units Sold</th>
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
          <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
          </button>
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
            <span class="text-xs font-medium text-gray-400 mt-1 inline-block">1 hour ago</span>
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
</template>

<script setup>
</script>
