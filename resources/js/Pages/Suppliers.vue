<template>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Suppliers</h1>
      <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto">
        + Add Supplier
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Supplier Cards -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-colors" v-for="i in 6" :key="i">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xl shrink-0">
              A{{ i }}
            </div>
            <div>
              <h3 class="font-bold text-gray-900 dark:text-white text-lg">Demo Company {{ i }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">Electronics Dist.</p>
            </div>
          </div>
          <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
          </button>
        </div>
        
        <div class="space-y-3 mt-6">
          <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 mr-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            contact@demo{{ i }}.com
          </div>
          <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 mr-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            +880 (100) 000-{{ 1000 + i }}
          </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
            Active
          </span>
          <button @click="showHistoryModal = true" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700">View Transactions</button>
        </div>
      </div>
    </div>
    <!-- Add Supplier Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false" @save="showAddModal = false">
      <template #title>Add New Supplier</template>
      <template #body>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. Acme Corp" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Industry / Category</label>
            <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm appearance-none">
              <option>Electronics</option>
              <option>Manufacturing</option>
              <option>Logistics</option>
            </select>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
              <input type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="contact@company.com" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
              <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="+1 (555) 000-0000" />
            </div>
          </div>
        </div>
      </template>
    </Modal>

    <!-- Transaction History Modal -->
    <Modal :show="showHistoryModal" @close="showHistoryModal = false" title="Supplier Transaction History">
      <template #body>
        <div class="space-y-4">
          <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
            <div>
              <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Spent</p>
              <p class="text-lg font-bold text-gray-900 dark:text-white">$45,200.00</p>
            </div>
            <button class="text-primary-600 hover:text-primary-800 text-sm font-medium">Download PDF</button>
          </div>
          
          <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <table class="w-full text-left text-sm">
              <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                <tr>
                  <th class="p-3 font-medium">Date</th>
                  <th class="p-3 font-medium">Order Ref</th>
                  <th class="p-3 font-medium text-right">Amount</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="i in 4" :key="i">
                  <td class="p-3 text-gray-900 dark:text-white">Oct {{ 15 - i }}, 2024</td>
                  <td class="p-3 text-primary-600 dark:text-primary-400">PO-100{{ i }}</td>
                  <td class="p-3 text-right font-medium text-gray-900 dark:text-white">${{ 1200 * i }}.00</td>
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
</template>

<script setup>
import { ref } from 'vue';
import Modal from '../Components/Modal.vue';

const showAddModal = ref(false);
const showHistoryModal = ref(false);
</script>
