<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Purchases</h1>
      <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Purchase Order
      </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Toolbar -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="relative w-full sm:w-64">
          <input type="text" placeholder="Search reference..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                  {{ filterStatus }}
                  <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <a href="#" v-for="status in ['All Statuses', 'Received', 'Pending', 'Ordered']" :key="status" @click.prevent="filterStatus = status; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="filterStatus === status ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ status }}</a>
              </template>
            </Dropdown>
            <button class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 flex-1 sm:flex-none">Export</button>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-black/90">
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Reference</th>
              <th class="p-4 font-semibold whitespace-nowrap">Supplier</th>
              <th class="p-4 font-semibold whitespace-nowrap">Purchase Status</th>
              <th class="p-4 font-semibold whitespace-nowrap">Grand Total</th>
              <th class="p-4 font-semibold whitespace-nowrap">Paid</th>
              <th class="p-4 font-semibold whitespace-nowrap">Payment Status</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="i in 5" :key="i">
              <td class="p-4 text-gray-900 dark:text-white text-sm whitespace-nowrap">12 Oct 2024</td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">PO-2024-{{ 100 + i }}</td>
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white whitespace-nowrap">Demo Company {{ i }}</div>
              </td>
              <td class="p-4">
                <span :class="i % 2 === 0 ? 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' : 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ i % 2 === 0 ? 'Received' : 'Pending' }}
                </span>
              </td>
              <td class="p-4 text-gray-900 dark:text-white font-medium whitespace-nowrap">{{ currencySymbol }}{{ 1500 * i }}.00</td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">{{ currencySymbol }}{{ (i % 2 === 0 ? 1500 : 500) * i }}.00</td>
              <td class="p-4">
                <span :class="i % 2 === 0 ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' : 'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ i % 2 === 0 ? 'Paid' : 'Partial' }}
                </span>
              </td>
              <td class="p-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-3">
                  <button v-if="i % 3 !== 0" @click="showReceiveModal = true" class="text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 text-sm font-medium transition-colors">
                    Receive Items
                  </button>
                  <button class="text-gray-400 hover:text-primary-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                  </button>
                  <button class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium mr-2">Edit</button>
                  <button class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 bg-gray-50/50 dark:bg-gray-900/50">
        <span>Showing 1 to 5 of 12 results</span>
        <div class="flex gap-1">
          <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">Prev</button>
          <button class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">Next</button>
        </div>
      </div>
    </div>

    <!-- Create PO Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false" title="Create Purchase Order">
      <template #body>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Supplier</label>
            <Dropdown align="left" width="full" fullWidth>
              <template #trigger>
                <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                  {{ newSupplier }}
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <a href="#" v-for="sup in ['Demo Company 1', 'Demo Company 2', 'Demo Company 3']" :key="sup" @click.prevent="newSupplier = sup; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="newSupplier === sup ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ sup }}</a>
              </template>
            </Dropdown>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expected Delivery Date</label>
            <input type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Items to Order</label>
            <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="List items or SKUs here..."></textarea>
          </div>
        </div>
      </template>
      <template #footer>
        <button @click="showAddModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Cancel</button>
        <button @click="showAddModal = false" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm">Submit PO</button>
      </template>
    </Modal>

    <!-- Receive Items Modal -->
    <Modal :show="showReceiveModal" @close="showReceiveModal = false" title="Receive Items (PO-2024-1002)">
      <template #body>
        <div class="space-y-4">
          <p class="text-sm text-gray-600 dark:text-gray-400">Verify the quantities received against the original purchase order.</p>
          <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <table class="w-full text-left text-sm">
              <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                <tr>
                  <th class="p-3 font-medium">SKU</th>
                  <th class="p-3 font-medium">Ordered</th>
                  <th class="p-3 font-medium">Received</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr>
                  <td class="p-3 font-medium text-gray-900 dark:text-white">TECH-100</td>
                  <td class="p-3 text-gray-600 dark:text-gray-400">500</td>
                  <td class="p-3"><input type="number" value="500" class="w-20 px-2 py-1 border border-gray-300 dark:border-gray-700 rounded bg-white dark:bg-black text-gray-900 dark:text-white text-sm" /></td>
                </tr>
                <tr>
                  <td class="p-3 font-medium text-gray-900 dark:text-white">TECH-205</td>
                  <td class="p-3 text-gray-600 dark:text-gray-400">200</td>
                  <td class="p-3"><input type="number" value="180" class="w-20 px-2 py-1 border border-red-300 dark:border-red-700 rounded bg-white dark:bg-black text-gray-900 dark:text-white text-sm focus:ring-red-500 focus:border-red-500" /></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes / Discrepancies</label>
            <textarea rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">Missing 20 units of TECH-205.</textarea>
          </div>
        </div>
      </template>
      <template #footer>
        <button @click="showReceiveModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Cancel</button>
        <button @click="showReceiveModal = false" class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm">Confirm Receipt</button>
      </template>
    </Modal>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { ref } from 'vue';
import { useCurrency } from '../Composables/useCurrency';

const { currencySymbol } = useCurrency();
import Modal from '../Components/Modal.vue';

const showAddModal = ref(false);
const showReceiveModal = ref(false);
const filterStatus = ref('All Statuses');
const newSupplier = ref('Demo Company 1');
</script>
