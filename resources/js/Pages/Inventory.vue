<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Inventory Stock</h1>
      <button @click="showSyncModal = true" class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        Sync Stock
      </button>
    </div>

    <!-- Alert -->
    <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
      <div class="flex items-start">
        <div class="shrink-0">
          <svg class="h-5 w-5 text-red-400 dark:text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-700 dark:text-red-400 font-medium">
            3 items are running low on stock and need to be reordered soon.
          </p>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <div class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-black/90">
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">SKU / Item</th>
              <th class="p-4 font-semibold whitespace-nowrap">Location</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">In Stock</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Reserved</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Available</th>
              <th class="p-4 font-semibold whitespace-nowrap text-center">Days Until Stockout</th>
              <th class="p-4 font-semibold whitespace-nowrap text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="i in 4" :key="i">
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white whitespace-nowrap">SKU-A{{ i }}00</div>
                <div class="flex items-center gap-3 mt-1">
                  <button @click="showHistoryModal = true" class="text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 text-xs font-medium transition-colors">
                    Movement History
                  </button>
                </div>
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">Warehouse A, Aisle {{ i }}</td>
              <td class="p-4 text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ 100 - i * 15 }}</td>
              <td class="p-4 text-right text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ i * 5 }}</td>
              <td class="p-4 text-right font-bold text-primary-600 dark:text-primary-400 whitespace-nowrap">{{ 100 - i * 20 }}</td>
              <td class="p-4 text-center">
                <span v-if="i === 1" class="text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/30 px-2 py-1 rounded">3 Days</span>
                <span v-else-if="i === 2" class="text-xs font-bold text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/30 px-2 py-1 rounded">12 Days</span>
                <span v-else class="text-xs font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded">Safe (30+ Days)</span>
              </td>
              <td class="p-4 text-center whitespace-nowrap">
                <span v-if="i === 1" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                  Low Stock
                </span>
                <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                  In Stock
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Adjust Stock Modal -->
    <Modal :show="showSyncModal" @close="showSyncModal = false" title="Stock Synchronization">
      <template #body>
        <div class="space-y-4">
          <!-- Tabs -->
          <div class="flex space-x-2 border-b border-gray-200 dark:border-gray-700 mb-4">
            <button class="px-4 py-2 border-b-2 border-primary-500 text-primary-600 dark:text-primary-400 font-medium text-sm">Adjustment</button>
            <button class="px-4 py-2 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 font-medium text-sm">Transfer</button>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Operation Type</label>
            <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">Add Stock (Restock)</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Add Stock (Restock)</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Remove Stock (Damaged/Lost)</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Inventory Transfer (Between Warehouses)</a>
                  </template>
                </Dropdown>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target SKU</label>
              <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. SKU-A100" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
              <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="1" />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason</label>
            <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">New Delivery</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">New Delivery</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Damaged Goods</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Inventory Audit</a>
                  </template>
                </Dropdown>
          </div>
        </div>
      </template>
    </Modal>
    <!-- Movement History Modal -->
    <Modal :show="showHistoryModal" @close="showHistoryModal = false" title="Product Movement History">
      <template #body>
        <div class="space-y-4">
          <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <table class="w-full text-left text-sm">
              <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                <tr>
                  <th class="p-3 font-medium">Date</th>
                  <th class="p-3 font-medium">Type</th>
                  <th class="p-3 font-medium">Ref / Reason</th>
                  <th class="p-3 font-medium text-right">Qty</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr>
                  <td class="p-3 text-gray-900 dark:text-white">Today, 10:45 AM</td>
                  <td class="p-3"><span class="text-green-600 bg-green-100 dark:bg-green-900/30 px-2 py-0.5 rounded text-xs">Stock In</span></td>
                  <td class="p-3 text-gray-500 dark:text-gray-400">PO-1004 Receive</td>
                  <td class="p-3 text-right font-medium text-green-600">+500</td>
                </tr>
                <tr>
                  <td class="p-3 text-gray-900 dark:text-white">Yesterday</td>
                  <td class="p-3"><span class="text-blue-600 bg-blue-100 dark:bg-blue-900/30 px-2 py-0.5 rounded text-xs">Transfer</span></td>
                  <td class="p-3 text-gray-500 dark:text-gray-400">To West Coast</td>
                  <td class="p-3 text-right font-medium text-gray-900 dark:text-white">-200</td>
                </tr>
                <tr>
                  <td class="p-3 text-gray-900 dark:text-white">Oct 12, 2024</td>
                  <td class="p-3"><span class="text-red-600 bg-red-100 dark:bg-red-900/30 px-2 py-0.5 rounded text-xs">Stock Out</span></td>
                  <td class="p-3 text-gray-500 dark:text-gray-400">Damaged Goods</td>
                  <td class="p-3 text-right font-medium text-red-600">-12</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>
      <template #footer>
        <button @click="showHistoryModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Close</button>
      </template>
    </Modal>
  </div>
  </AppLayout>
</template>

<script setup>
import Dropdown from '../Components/Dropdown.vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { ref } from 'vue';
import Modal from '../Components/Modal.vue';

const showSyncModal = ref(false);
const showHistoryModal = ref(false);
</script>
