<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Audit Log & System Activity</h1>
      <button class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
        Export Log
      </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Advanced Filters -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">User</label>
          <Dropdown align="left" width="full" fullWidth>
            <template #trigger>
              <button class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white transition-colors">
                {{ filterUser }}
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </button>
            </template>
            <template #content="{ close }">
              <a href="#" v-for="user in ['All Users', 'Demo Admin', 'Sarah Jenkins', 'Michael Scott']" :key="user" @click.prevent="filterUser = user; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="filterUser === user ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ user }}</a>
            </template>
          </Dropdown>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Action Type</label>
          <Dropdown align="left" width="full" fullWidth>
            <template #trigger>
              <button class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white transition-colors">
                {{ filterAction }}
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </button>
            </template>
            <template #content="{ close }">
              <a href="#" v-for="action in ['All Actions', 'Create', 'Update', 'Delete', 'Login']" :key="action" @click.prevent="filterAction = action; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="filterAction === action ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ action }}</a>
            </template>
          </Dropdown>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Module</label>
          <Dropdown align="left" width="full" fullWidth>
            <template #trigger>
              <button class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white transition-colors">
                {{ filterModule }}
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </button>
            </template>
            <template #content="{ close }">
              <a href="#" v-for="module in ['All Modules', 'Inventory', 'Sales', 'Users', 'Settings']" :key="module" @click.prevent="filterModule = module; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="filterModule === module ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ module }}</a>
            </template>
          </Dropdown>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Date Range</label>
          <input type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Timestamp</th>
              <th class="p-4 font-semibold whitespace-nowrap">User</th>
              <th class="p-4 font-semibold whitespace-nowrap">Action</th>
              <th class="p-4 font-semibold whitespace-nowrap">Module</th>
              <th class="p-4 font-semibold whitespace-nowrap">Record</th>
              <th class="p-4 font-semibold whitespace-nowrap">Description</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="(log, i) in logs" :key="i">
              <td class="p-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ log.time }}</td>
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white text-sm">{{ log.user }}</div>
              </td>
              <td class="p-4">
                <span :class="actionClass(log.action)" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium">
                  {{ log.action }}
                </span>
              </td>
              <td class="p-4 text-sm text-gray-700 dark:text-gray-300">{{ log.module }}</td>
              <td class="p-4 text-sm font-mono text-gray-500 dark:text-gray-400">{{ log.record }}</td>
              <td class="p-4 text-sm text-gray-700 dark:text-gray-300">{{ log.description }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 bg-gray-50/50 dark:bg-gray-900/50">
        <span>Showing 1 to 5 of 1,240 events</span>
        <div class="flex gap-1">
          <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">Prev</button>
          <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">Next</button>
        </div>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { ref } from 'vue';

const filterUser = ref('All Users');
const filterAction = ref('All Actions');
const filterModule = ref('All Modules');

const logs = [
  { time: '2026-10-24 14:32:01', user: 'Sarah Jenkins', action: 'Update', module: 'Inventory', record: 'PRD-1024', description: 'Adjusted stock level from 45 to 60' },
  { time: '2026-10-24 13:15:22', user: 'Demo Admin', action: 'Delete', module: 'Users', record: 'USR-899', description: 'Removed employee access for jsmith@example.com' },
  { time: '2026-10-24 11:05:40', user: 'Michael Scott', action: 'Create', module: 'Sales', record: 'INV-2026-1001', description: 'Generated new POS Sale for Walk-in Customer' },
  { time: '2026-10-24 09:42:10', user: 'Demo Admin', action: 'Update', module: 'Settings', record: 'SYS-CONF', description: 'Changed default currency to USD' },
  { time: '2026-10-24 08:30:00', user: 'Sarah Jenkins', action: 'Login', module: 'System', record: '-', description: 'Successful login from IP 192.168.1.45' },
];

const actionClass = (action) => {
  if (action === 'Create') return 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400';
  if (action === 'Update') return 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400';
  if (action === 'Delete') return 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400';
  return 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300';
};
</script>
