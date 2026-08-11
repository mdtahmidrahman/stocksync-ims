<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Payments & Billing</h1>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 min-w-0">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Total Incoming (Sales)">Total Incoming (Sales)</div>
        <AutoFitText :value="`${currencySymbol}${(metrics?.total_incoming || 0).toFixed(2)}`" custom-class="text-emerald-600 dark:text-emerald-400 mt-1" />
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 min-w-0">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Total Outgoing (Purchases)">Total Outgoing (Purchases)</div>
        <AutoFitText :value="`${currencySymbol}${(metrics?.total_outgoing || 0).toFixed(2)}`" custom-class="text-rose-600 dark:text-rose-400 mt-1" />
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 min-w-0">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Net Cashflow">Net Cashflow</div>
        <AutoFitText :value="`${currencySymbol}${(metrics?.net_cashflow || 0).toFixed(2)}`" :custom-class="metrics?.net_cashflow >= 0 ? 'text-emerald-600 dark:text-emerald-400 mt-1' : 'text-rose-600 dark:text-rose-400 mt-1'" />
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Toolbar -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="flex gap-2 w-full sm:w-auto">
            <Dropdown align="left" width="48">
              <template #trigger>
                <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                  {{ paymentType }}
                  <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <a href="#" v-for="type in ['All Types', 'Incoming (Sales)', 'Outgoing (Purchases)']" :key="type" @click.prevent="changeType(type); close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="paymentType === type ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ type }}</a>
              </template>
            </Dropdown>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]" @scroll="handleScroll">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-black/90">
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Reference Number</th>
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Entity / Type</th>
              <th class="p-4 font-semibold whitespace-nowrap">Method</th>
              <th class="p-4 font-semibold whitespace-nowrap">Amount</th>
              <th class="p-4 font-semibold whitespace-nowrap">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr v-if="!localPayments || localPayments.length === 0">
              <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400">No payment transactions recorded yet.</td>
            </tr>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="pay in localPayments" :key="pay.id">
              <td class="p-4 text-sm font-medium text-primary-600 dark:text-primary-400 whitespace-nowrap">
                {{ pay.reference_number || ('PAY-' + pay.id) }}
              </td>
              <td class="p-4 text-gray-900 dark:text-white text-sm whitespace-nowrap">
                {{ formatDate(pay.payment_date || pay.created_at) }}
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">
                {{ getEntityName(pay) }}
              </td>
              <td class="p-4 text-gray-900 dark:text-white text-sm whitespace-nowrap capitalize">
                {{ pay.payment_method || 'Cash' }}
              </td>
              <td :class="['p-4 font-medium whitespace-nowrap', isIncoming(pay) ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400']">
                {{ isIncoming(pay) ? '+' : '-' }}{{ currencySymbol }}{{ Number(pay.amount || 0).toFixed(2) }}
              </td>
              <td class="p-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300">
                  Completed
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Scrollable Chunk Pagination Footer -->
      <div v-if="localPayments && localPayments.length > 0" class="p-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-800/30">
        <div class="text-xs text-gray-500 dark:text-gray-400">
          Showing <span class="font-bold text-gray-900 dark:text-white">{{ localPayments.length }}</span> of <span class="font-bold text-gray-900 dark:text-white">{{ props.payments.total }}</span> payment transactions
        </div>
        <div v-if="isFetchingNextPage" class="text-xs font-semibold text-primary-600 dark:text-primary-400 flex items-center gap-2">
          <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
          Loading next 50 payments...
        </div>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Dropdown from '../Components/Dropdown.vue';
import AutoFitText from '../Components/AutoFitText.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useCurrency } from '../Composables/useCurrency';
import { formatDate } from '../Composables/useDate';

const props = defineProps({
  payments: Object,
  metrics: Object,
  filters: Object,
});

const localPayments = ref([]);
const isFetchingNextPage = ref(false);

watch(() => props.payments.data, (newData) => {
    if (props.payments.current_page === 1) {
        localPayments.value = newData || [];
    } else if (newData && newData.length) {
        const existingIds = new Set(localPayments.value.map(i => i.id));
        const newItems = newData.filter(i => !existingIds.has(i.id));
        localPayments.value = [...localPayments.value, ...newItems];
    }
}, { immediate: true });

const handleScroll = (e) => {
    const { scrollTop, clientHeight, scrollHeight } = e.target;
    if (scrollTop + clientHeight >= scrollHeight - 80) {
        fetchNextPage();
    }
};

const fetchNextPage = () => {
    if (props.payments.next_page_url && !isFetchingNextPage.value) {
        isFetchingNextPage.value = true;
        router.get(props.payments.next_page_url, {}, {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                isFetchingNextPage.value = false;
            }
        });
    }
};

const { currencySymbol } = useCurrency();
const paymentType = ref(props.filters?.type || 'All Types');

const changeType = (newType) => {
  paymentType.value = newType;
  router.get('/payments', { type: newType }, { preserveState: true, preserveScroll: true });
};

const isIncoming = (pay) => {
  return pay.payable_type && !pay.payable_type.includes('Purchase');
};

const getEntityName = (pay) => {
  if (!pay.payable_type) return 'N/A';
  const parts = pay.payable_type.split('\\');
  const modelName = parts[parts.length - 1];
  return `${modelName} #${pay.payable_id}`;
};
</script>
