<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Audit Log & System Activity</h1>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Search Filter -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-3">
        <input v-model="formFilters.search" @input="debouncedSearch" type="text" placeholder="Search event or description..." class="col-span-1 md:col-span-2 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
        
        <Dropdown align="left" width="full" fullWidth>
          <template #trigger>
            <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
              <span :class="!formFilters.user_id ? 'text-gray-500' : ''">{{ formFilters.user_id ? users.find(u => u.id === formFilters.user_id)?.name : 'All Users' }}</span>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
          </template>
          <template #content="{ close }">
            <div class="max-h-60 overflow-y-auto">
              <a href="#" @click.prevent="formFilters.user_id = ''; applyFilters(); close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="formFilters.user_id === '' ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">All Users</a>
              <a href="#" v-for="user in users" :key="user.id" @click.prevent="formFilters.user_id = user.id; applyFilters(); close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="formFilters.user_id === user.id ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ user.name }}</a>
            </div>
          </template>
        </Dropdown>

        <Dropdown align="left" width="full" fullWidth>
          <template #trigger>
            <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
              <span :class="!formFilters.module ? 'text-gray-500' : ''">{{ formFilters.module ? formFilters.module : 'All Modules' }}</span>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
          </template>
          <template #content="{ close }">
            <div class="max-h-60 overflow-y-auto">
              <a href="#" v-for="mod in ['', 'Product', 'Category', 'Warehouse', 'Sale', 'Purchase', 'Inventory', 'Role', 'User']" :key="mod" @click.prevent="formFilters.module = mod; applyFilters(); close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="formFilters.module === mod ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ mod === '' ? 'All Modules' : (mod === 'Inventory' || mod === 'Role' || mod === 'User' ? mod + 's' : mod === 'Category' ? 'Categories' : mod + 's') }}</a>
            </div>
          </template>
        </Dropdown>

        <Dropdown align="left" width="full" fullWidth>
          <template #trigger>
            <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
              <span :class="!formFilters.action ? 'text-gray-500' : ''">{{ formFilters.action ? formFilters.action : 'All Actions' }}</span>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
          </template>
          <template #content="{ close }">
            <div class="max-h-60 overflow-y-auto">
              <a href="#" v-for="act in ['', 'Created', 'Updated', 'Deleted', 'Added', 'Removed', 'Changed', 'Login', 'Logout']" :key="act" @click.prevent="formFilters.action = act; applyFilters(); close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="formFilters.action === act ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ act === '' ? 'All Actions' : act }}</a>
            </div>
          </template>
        </Dropdown>

        <div class="col-span-1 md:col-span-4 lg:col-span-2 flex flex-row items-center gap-2">
           <input v-model="formFilters.date_start" @change="applyFilters" type="date" title="Start Date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
           <span class="text-gray-500 text-sm">to</span>
           <input v-model="formFilters.date_end" @change="applyFilters" type="date" title="End Date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]" @scroll="handleScroll">
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
            <tr v-if="!localLogs || localLogs.length === 0">
              <td colspan="4" class="p-6 text-center text-gray-500 dark:text-gray-400">No activity logs recorded yet.</td>
            </tr>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="log in localLogs" :key="log.id">
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

      <!-- Scrollable Chunk Pagination Footer -->
      <div v-if="localLogs && localLogs.length > 0" class="p-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-800/30">
        <div class="text-xs text-gray-500 dark:text-gray-400">
          Showing <span class="font-bold text-gray-900 dark:text-white">{{ localLogs.length }}</span> of <span class="font-bold text-gray-900 dark:text-white">{{ props.logs.total }}</span> activity events
        </div>
        <div v-if="isFetchingNextPage" class="text-xs font-semibold text-primary-600 dark:text-primary-400 flex items-center gap-2">
          <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          Loading next 50 logs...
        </div>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Dropdown from '../Components/Dropdown.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { formatDateTime } from '../Composables/useDate';

const props = defineProps({
  logs: Object,
  users: Array,
  filters: Object,
});

const localLogs = ref([]);
const isFetchingNextPage = ref(false);

watch(() => props.logs.data, (newData) => {
    if (props.logs.current_page === 1) {
        localLogs.value = newData || [];
    } else if (newData && newData.length) {
        const existingIds = new Set(localLogs.value.map(i => i.id));
        const newItems = newData.filter(i => !existingIds.has(i.id));
        localLogs.value = [...localLogs.value, ...newItems];
    }
}, { immediate: true });

const handleScroll = (e) => {
    const { scrollTop, clientHeight, scrollHeight } = e.target;
    if (scrollTop + clientHeight >= scrollHeight - 80) {
        fetchNextPage();
    }
};

const fetchNextPage = () => {
    if (props.logs.next_page_url && !isFetchingNextPage.value) {
        isFetchingNextPage.value = true;
        router.get(props.logs.next_page_url, {}, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                isFetchingNextPage.value = false;
            }
        });
    }
};

const formFilters = ref({
  search: props.filters?.search || '',
  user_id: props.filters?.user_id || '',
  module: props.filters?.module || '',
  action: props.filters?.action || '',
  date_start: props.filters?.date_start || '',
  date_end: props.filters?.date_end || '',
});

const applyFilters = () => {
  // Clear empty values from the URL
  const cleanFilters = Object.fromEntries(
    Object.entries(formFilters.value).filter(([_, v]) => v !== '')
  );
  router.get('/audit-log', cleanFilters, { preserveState: true, preserveScroll: true });
};

const debouncedSearch = debounce(applyFilters, 300);

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
