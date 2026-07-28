<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Inventory Stock</h1>
      <button @click="showSyncModal = true" class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
        Sync / Adjust Stock
      </button>
    </div>

    <!-- Alert -->
    <div v-if="lowStockCount > 0" class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
      <div class="flex items-start">
        <div class="shrink-0">
          <svg class="h-5 w-5 text-red-400 dark:text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-700 dark:text-red-400 font-medium">
            {{ lowStockCount }} {{ lowStockCount === 1 ? 'item is' : 'items are' }} running low on stock and need to be reordered soon.
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
              <th class="p-4 font-semibold whitespace-nowrap">Category</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">In Stock</th>
              <th class="p-4 font-semibold whitespace-nowrap text-center">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr v-if="!products.data || products.data.length === 0">
              <td colspan="4" class="p-6 text-center text-gray-500 dark:text-gray-400">No inventory products found.</td>
            </tr>
            <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ product.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">SKU: {{ product.sku }}</div>
                <div class="flex items-center gap-3 mt-1">
                  <button @click="openHistory(product)" class="text-primary-600 hover:text-primary-800 dark:hover:text-primary-400 text-xs font-medium transition-colors">
                    Movement History
                  </button>
                </div>
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">
                {{ product.category ? product.category.name : 'Uncategorized' }}
              </td>
              <td class="p-4 text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">
                {{ product.stock_quantity }}
              </td>
              <td class="p-4 text-center whitespace-nowrap">
                <span v-if="product.stock_quantity <= 5" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
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
    <Modal :show="showSyncModal" @close="showSyncModal = false" title="Stock Synchronization / Adjustment">
      <template #body>
        <form @submit.prevent="submitSync" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Product</label>
            <select v-model="syncForm.product_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
              <option value="" disabled>Select Product</option>
              <option v-for="prod in products.data" :key="prod.id" :value="prod.id">
                {{ prod.name }} (SKU: {{ prod.sku }} - Current: {{ prod.stock_quantity }})
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Operation Type</label>
            <select v-model="syncForm.type" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
              <option value="add">Add Stock (Restock)</option>
              <option value="remove">Remove Stock (Damaged/Lost)</option>
              <option value="transfer">Inventory Transfer</option>
            </select>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
              <input v-model="syncForm.quantity" type="number" min="1" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="1" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warehouse</label>
              <select v-model="syncForm.warehouse_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="">Default Warehouse</option>
                <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason / Remarks</label>
            <input v-model="syncForm.remarks" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. New Delivery, Audit Adjustment" />
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
            <button type="button" @click="showSyncModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Cancel</button>
            <button type="submit" :disabled="syncForm.processing" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
              {{ syncForm.processing ? 'Saving...' : 'Apply Stock Change' }}
            </button>
          </div>
        </form>
      </template>
    </Modal>

    <!-- Movement History Modal -->
    <Modal :show="showHistoryModal" @close="showHistoryModal = false" :title="activeProduct ? `Movement History: ${activeProduct.name}` : 'Product Movement History'">
      <template #body>
        <div class="space-y-4">
          <div v-if="loadingHistory" class="p-6 text-center text-gray-500 dark:text-gray-400">Loading history...</div>
          <div v-else class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <table class="w-full text-left text-sm">
              <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                <tr>
                  <th class="p-3 font-medium">Date</th>
                  <th class="p-3 font-medium">Type</th>
                  <th class="p-3 font-medium">Remarks</th>
                  <th class="p-3 font-medium text-right">Qty</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-if="historyLogs.length === 0">
                  <td colspan="4" class="p-4 text-center text-gray-500 dark:text-gray-400">No stock movements recorded yet.</td>
                </tr>
                <tr v-for="log in historyLogs" :key="log.id">
                  <td class="p-3 text-gray-900 dark:text-white whitespace-nowrap">{{ formatDateTime(log.created_at) }}</td>
                  <td class="p-3">
                    <span :class="[
                      'px-2 py-0.5 rounded text-xs capitalize',
                      log.quantity > 0 ? 'text-green-600 bg-green-100 dark:bg-green-900/30' : 'text-red-600 bg-red-100 dark:bg-red-900/30'
                    ]">{{ log.type }}</span>
                  </td>
                  <td class="p-3 text-gray-500 dark:text-gray-400">{{ log.remarks || 'Stock Movement' }}</td>
                  <td :class="['p-3 text-right font-medium', log.quantity > 0 ? 'text-green-600' : 'text-red-600']">
                    {{ log.quantity > 0 ? '+' + log.quantity : log.quantity }}
                  </td>
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
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { formatDateTime } from '../Composables/useDate';

const props = defineProps({
  products: Object,
  warehouses: Array,
  lowStockCount: Number,
  filters: Object,
});

const showSyncModal = ref(false);
const showHistoryModal = ref(false);
const activeProduct = ref(null);
const historyLogs = ref([]);
const loadingHistory = ref(false);

const syncForm = useForm({
  product_id: '',
  type: 'add',
  quantity: 1,
  warehouse_id: '',
  remarks: '',
});

const submitSync = () => {
  syncForm.post('/inventory/adjust', {
    onSuccess: () => {
      showSyncModal.value = false;
      syncForm.reset();
    },
  });
};

const openHistory = async (product) => {
  activeProduct.value = product;
  showHistoryModal.value = true;
  loadingHistory.value = true;
  try {
    const res = await fetch(`/inventory/history/${product.id}`);
    const data = await res.json();
    historyLogs.value = data.movements || [];
  } catch (err) {
    console.error(err);
  } finally {
    loadingHistory.value = false;
  }
};
</script>
