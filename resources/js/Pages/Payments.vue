<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Payments & Billing</h1>
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
      <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
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
            <tr v-if="!payments.data || payments.data.length === 0">
              <td colspan="6" class="p-6 text-center text-gray-500 dark:text-gray-400">No payment transactions recorded yet.</td>
            </tr>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="pay in payments.data" :key="pay.id">
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
              <td :class="['p-4 font-medium whitespace-nowrap', isIncoming(pay) ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400']">
                {{ isIncoming(pay) ? '+' : '-' }}{{ currencySymbol }}{{ Number(pay.amount || 0).toFixed(2) }}
              </td>
              <td class="p-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                  Completed
                </span>
              </td>
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
import Dropdown from '../Components/Dropdown.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useCurrency } from '../Composables/useCurrency';
import { formatDate } from '../Composables/useDate';

const props = defineProps({
  payments: Object,
  filters: Object,
});

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
