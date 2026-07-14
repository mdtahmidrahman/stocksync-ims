<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Orders</h1>
      <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto">
        Create Order
      </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Tabs -->
      <div class="border-b border-gray-100 dark:border-gray-800 flex overflow-x-auto">
        <button class="px-6 py-3 text-sm font-medium text-primary-600 dark:text-primary-400 border-b-2 border-primary-600 dark:border-primary-400 whitespace-nowrap">All Orders</button>
        <button class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Pending</button>
        <button class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Processing</button>
        <button class="px-6 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap">Completed</button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Order ID</th>
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Customer</th>
              <th class="p-4 font-semibold whitespace-nowrap">Status</th>
              <th class="p-4 font-semibold whitespace-nowrap">Total</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="i in 5" :key="i">
              <td class="p-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">#ORD-2024-{{ i }}42</td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">Oct {{ i + 10 }}, 2024</td>
              <td class="p-4">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300 shrink-0">
                    C
                  </div>
                  <span class="text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap">Customer Name</span>
                </div>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 whitespace-nowrap">
                  Pending
                </span>
              </td>
              <td class="p-4 text-gray-900 dark:text-white font-medium whitespace-nowrap">{{ currencySymbol }}{{ 100 * i }}.00</td>
              <td class="p-4 text-right whitespace-nowrap">
                <button @click="showViewModal = true" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 text-sm font-medium">View</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Create Order Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false" @save="showAddModal = false">
      <template #title>Create New Order</template>
      <template #body>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer Name</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. John Doe" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Status</label>
              <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">Pending</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Pending</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Processing</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Completed</a>
                  </template>
                </Dropdown>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Amount ({{ currencySymbol }})</label>
              <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="0.00" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="3" placeholder="Optional notes..."></textarea>
          </div>
        </div>
      </template>
    </Modal>

    <!-- View Order Modal -->
    <Modal :show="showViewModal" @close="showViewModal = false" @save="showViewModal = false">
      <template #title>Order Details</template>
      <template #body>
        <div class="space-y-4">
          <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4">
            <div>
              <div class="text-sm text-gray-500 dark:text-gray-400">Order ID</div>
              <div class="font-bold text-gray-900 dark:text-white">#ORD-2024-142</div>
            </div>
            <div class="text-right">
              <div class="text-sm text-gray-500 dark:text-gray-400">Date</div>
              <div class="font-bold text-gray-900 dark:text-white">Oct 11, 2024</div>
            </div>
          </div>
          <div>
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Customer</div>
            <div class="font-bold text-gray-900 dark:text-white">John Doe</div>
          </div>
        </div>
      </template>
    </Modal>
  </div>
  </AppLayout>
</template>

<script setup>
import Dropdown from '../Components/Dropdown.vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { ref } from 'vue';
import { useCurrency } from '../Composables/useCurrency';

const { currencySymbol } = useCurrency();
import Modal from '../Components/Modal.vue';

const showAddModal = ref(false);
const showViewModal = ref(false);
</script>
