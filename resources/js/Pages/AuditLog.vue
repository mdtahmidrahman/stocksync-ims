<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Audit Log & System Activity</h1>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Search Filter -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 flex items-center gap-4">
        <input v-model="search" @input="debouncedSearch" type="text" placeholder="Search event or description..." class="w-full max-w-sm px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
      </div>

      <!-- Table -->
      <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-black/90">
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Timestamp</th>
              <th class="p-4 font-semibold whitespace-nowrap">User</th>
              <th class="p-4 font-semibold whitespace-nowrap">Event</th>
              <th class="p-4 font-semibold whitespace-nowrap">Description</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr v-if="!logs.data || logs.data.length === 0">
              <td colspan="4" class="p-6 text-center text-gray-500 dark:text-gray-400">No activity logs recorded yet.</td>
            </tr>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="log in logs.data" :key="log.id">
              <td class="p-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ formatDateTime(log.created_at) }}</td>
              <td class="p-4 font-medium text-gray-900 dark:text-white text-sm whitespace-nowrap">
                {{ log.user ? log.user.name : 'System' }}
              </td>
              <td class="p-4 whitespace-nowrap">
                <span :class="['inline-flex items-center px-3 py-1 rounded-full text-xs font-medium tracking-wide capitalize', getEventBadgeClass(log.event)]">
                  {{ log.event }}
                </span>
              </td>
              <td class="p-4 text-sm text-gray-700 dark:text-gray-300">{{ log.description }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { formatDateTime } from '../Composables/useDate';

const props = defineProps({
  logs: Object,
  filters: Object,
});

const search = ref(props.filters?.search || '');

const debouncedSearch = debounce(() => {
  router.get('/audit-log', { search: search.value }, { preserveState: true, preserveScroll: true });
}, 300);

const getEventBadgeClass = (event) => {
  if (!event) return 'bg-gray-100 text-gray-700 dark:bg-gray-800/60 dark:text-gray-400';
  const e = event.toLowerCase();
  
  if (e.includes('deactivate')) {
    return 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400';
  }
  if (e.includes('activate')) {
    return 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400';
  }
  if (e.includes('create') || e.includes('add') || e.includes('recorded')) {
    return 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400';
  }
  if (e.includes('delete') || e.includes('remove')) {
    return 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400';
  }
  if (e.includes('update') || e.includes('edit')) {
    return 'bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400';
  }
  if (e.includes('adjust') || e.includes('transfer')) {
    return 'bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400';
  }
  return 'bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400';
};
</script>
