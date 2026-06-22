<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Sales & Invoices</h1>
      <button @click="showPosModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New POS Sale
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales Today</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">$4,250.00</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Invoices Generated</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">24</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Payments</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">$850.00</div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Toolbar -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="relative w-full sm:w-64">
          <input type="text" placeholder="Search invoices..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <Dropdown align="right" width="48">
              <template #trigger>
                <button class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                  {{ statusFilter }}
                  <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <a href="#" @click.prevent="statusFilter = 'All Statuses'; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" :class="statusFilter === 'All Statuses' ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">All Statuses</a>
                <a href="#" @click.prevent="statusFilter = 'Paid'; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" :class="statusFilter === 'Paid' ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">Paid</a>
                <a href="#" @click.prevent="statusFilter = 'Pending'; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" :class="statusFilter === 'Pending' ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">Pending</a>
              </template>
            </Dropdown>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Invoice #</th>
              <th class="p-4 font-semibold whitespace-nowrap">Customer</th>
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Total</th>
              <th class="p-4 font-semibold whitespace-nowrap">Status</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" v-for="i in 5" :key="i">
              <td class="p-4 text-sm font-medium text-primary-600 dark:text-primary-400">INV-2024-{{ 1000 + i }}</td>
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white">Walk-in Customer {{ i }}</div>
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">Today, 10:{{ 10 + i }} AM</td>
              <td class="p-4 text-gray-900 dark:text-white font-medium whitespace-nowrap">${{ 120 * i }}.00</td>
              <td class="p-4">
                <span :class="i % 3 === 0 ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' : 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                  {{ i % 3 === 0 ? 'Pending' : 'Paid' }}
                </span>
              </td>
              <td class="p-4 text-right whitespace-nowrap">
                <button class="text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 text-sm font-medium transition-colors">
                  Generate PDF
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- POS Sale Modal -->
    <Modal :show="showPosModal" @close="showPosModal = false" @save="showPosModal = false" maxWidth="3xl">
      <template #title>Point of Sale (POS)</template>
      <template #body>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <!-- Left Side: Cart & Search -->
          <div class="md:col-span-2 flex flex-col h-[500px]">
            <!-- Search/Scanner -->
            <div class="relative mb-4">
              <input type="text" placeholder="Scan barcode or search products..." class="w-full pl-10 pr-4 py-3 border-2 border-primary-100 dark:border-primary-900/30 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:border-primary-500 shadow-sm" />
              <svg class="w-6 h-6 text-primary-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            
            <!-- Cart Table -->
            <div class="flex-1 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">
              <div class="overflow-y-auto flex-1 p-4">
                <table class="w-full text-left">
                  <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="text-sm">
                      <td class="py-3 font-medium text-gray-900 dark:text-white">Wireless Headphones</td>
                      <td class="py-3">
                        <div class="flex items-center gap-2">
                          <button class="w-6 h-6 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">-</button>
                          <span>1</span>
                          <button class="w-6 h-6 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">+</button>
                        </div>
                      </td>
                      <td class="py-3 text-right font-medium">$129.99</td>
                      <td class="py-3 text-right"><button class="text-red-500 hover:text-red-700"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></td>
                    </tr>
                    <tr class="text-sm">
                      <td class="py-3 font-medium text-gray-900 dark:text-white">USB-C Cable (2m)</td>
                      <td class="py-3">
                        <div class="flex items-center gap-2">
                          <button class="w-6 h-6 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">-</button>
                          <span>2</span>
                          <button class="w-6 h-6 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">+</button>
                        </div>
                      </td>
                      <td class="py-3 text-right font-medium">$39.98</td>
                      <td class="py-3 text-right"><button class="text-red-500 hover:text-red-700"><svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="bg-white dark:bg-gray-900 p-4 border-t border-gray-200 dark:border-gray-800 text-sm">
                <div class="flex justify-between mb-1"><span class="text-gray-500">Subtotal</span><span class="font-medium">$169.97</span></div>
                <div class="flex justify-between mb-1"><span class="text-gray-500">Tax (8%)</span><span class="font-medium">$13.60</span></div>
                <div class="flex justify-between mt-2 pt-2 border-t border-gray-100 dark:border-gray-700 text-lg font-bold"><span class="text-gray-900 dark:text-white">Total</span><span class="text-primary-600 dark:text-primary-400">$183.57</span></div>
              </div>
            </div>
          </div>

          <!-- Right Side: Payment & Customer -->
          <div class="flex flex-col gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer (Optional)</label>
              <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm appearance-none">
                <option>Walk-in Customer</option>
                <option>John Doe (john@example.com)</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method</label>
              <div class="grid grid-cols-2 gap-2">
                <button class="py-2 border-2 border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 rounded-lg text-sm font-semibold">Credit Card</button>
                <button class="py-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-semibold">Cash</button>
              </div>
            </div>
          </div>

        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showPosModal = false" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel Sale</button>
          <button @click="showPosModal = false" class="px-6 py-2 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Complete Checkout
          </button>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Modal from '../Components/Modal.vue';
import Dropdown from '../Components/Dropdown.vue';

const statusFilter = ref('All Statuses');
const showPosModal = ref(false);
</script>
