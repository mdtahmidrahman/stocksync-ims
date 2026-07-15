<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Purchases</h1>
      <button @click="openAddModal" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Record Purchase
      </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <div v-if="purchases.data.length === 0" class="p-12 text-center">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No purchases yet</h3>
          <p class="text-gray-500 dark:text-gray-400 mb-6">Start restocking your inventory by recording a new purchase.</p>
          <button @click="openAddModal" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">Record Purchase</button>
      </div>

      <div v-else class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-black/90">
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Reference</th>
              <th class="p-4 font-semibold whitespace-nowrap">Supplier</th>
              <th class="p-4 font-semibold whitespace-nowrap">Items</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Grand Total</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="purchase in purchases.data" :key="purchase.id">
              <td class="p-4 text-gray-900 dark:text-white text-sm whitespace-nowrap">{{ new Date(purchase.created_at).toLocaleDateString() }}</td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap font-medium">{{ purchase.reference_number }}</td>
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ purchase.supplier ? purchase.supplier.name : 'Unknown' }}</div>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300">
                  {{ purchase.items.length }} {{ purchase.items.length === 1 ? 'item' : 'items' }}
                </span>
              </td>
              <td class="p-4 text-right text-gray-900 dark:text-white font-bold whitespace-nowrap">{{ currencySymbol }}{{ parseFloat(purchase.total_amount).toFixed(2) }}</td>
              <td class="p-4 text-right whitespace-nowrap">
                <button @click="confirmDelete(purchase.id)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination Component here if needed, keeping it simple for now -->
    </div>

    <!-- Record Purchase Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false" maxWidth="5xl">
      <template #title>Record New Purchase</template>
      <template #body>
        <form @submit.prevent="submitPurchase" id="purchaseForm" class="space-y-6">
          <!-- Header Info -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Supplier *</label>
              <select v-model="addForm.supplier_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="" disabled>Choose a supplier...</option>
                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{ supplier.name }}</option>
              </select>
              <div v-if="addForm.errors.supplier_id" class="text-red-500 text-xs mt-1">{{ addForm.errors.supplier_id }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reference Number</label>
              <input v-model="addForm.reference_number" type="text" placeholder="Auto-generated if empty" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="addForm.errors.reference_number" class="text-red-500 text-xs mt-1">{{ addForm.errors.reference_number }}</div>
            </div>
          </div>

          <!-- Items Cart -->
          <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purchase Items *</label>
                <button type="button" @click="addItem" class="text-sm text-primary-600 dark:text-primary-400 font-semibold hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Product
                </button>
            </div>
            <div v-if="addForm.errors.items" class="text-red-500 text-xs mb-2">{{ addForm.errors.items }}</div>

            <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-white dark:bg-gray-900">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="p-3 font-medium w-1/2">Product</th>
                            <th class="p-3 font-medium w-24">Qty</th>
                            <th class="p-3 font-medium w-32">Unit Price</th>
                            <th class="p-3 font-medium text-right">Subtotal</th>
                            <th class="p-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-if="addForm.items.length === 0">
                            <td colspan="5" class="p-6 text-center text-gray-500">No items added. Click "Add Product" above.</td>
                        </tr>
                        <tr v-for="(item, index) in addForm.items" :key="index" class="group">
                            <td class="p-2">
                                <select v-model="item.product_id" @change="onProductSelect(index)" required class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                                    <option value="" disabled>Select product...</option>
                                    <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }} (Stock: {{ product.stock_quantity }})</option>
                                </select>
                            </td>
                            <td class="p-2">
                                <input v-model.number="item.quantity" type="number" min="1" required class="w-full px-2 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm" />
                            </td>
                            <td class="p-2 relative">
                                <span class="absolute left-4 top-1.5 text-gray-400 mt-0.5">{{ currencySymbol }}</span>
                                <input v-model.number="item.unit_price" type="number" min="0" step="0.01" required class="w-full pl-6 pr-2 py-1.5 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm" />
                            </td>
                            <td class="p-3 text-right font-medium text-gray-900 dark:text-white">
                                {{ currencySymbol }}{{ ((item.quantity || 0) * (item.unit_price || 0)).toFixed(2) }}
                            </td>
                            <td class="p-2 text-right">
                                <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="bg-gray-50 dark:bg-gray-800 p-4 flex justify-end items-center gap-4">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Grand Total:</span>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">{{ currencySymbol }}{{ grandTotal.toFixed(2) }}</span>
                </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Internal Notes</label>
            <textarea v-model="addForm.notes" rows="2" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Optional notes..."></textarea>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-between items-center w-full">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 inline mr-1 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Stock levels will be increased immediately.
            </div>
            <div class="flex gap-2">
                <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Cancel</button>
                <button form="purchaseForm" type="submit" :disabled="addForm.processing || addForm.items.length === 0" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Confirm Purchase</button>
            </div>
        </div>
      </template>
    </Modal>

    <ConfirmDeleteModal 
      :show="showDeleteModal" 
      message="Are you sure you want to delete this purchase? The stock quantities that were added will be reverted."
      @close="showDeleteModal = false; itemToDelete = null"
      @confirm="executeDelete"
    />
  </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import ConfirmDeleteModal from '../Components/ConfirmDeleteModal.vue';
import { useCurrency } from '../Composables/useCurrency';

const { currencySymbol } = useCurrency();

const props = defineProps({
    purchases: Object,
    suppliers: Array,
    products: Array,
});

const showAddModal = ref(false);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);

const addForm = useForm({
    supplier_id: '',
    reference_number: '',
    notes: '',
    items: []
});

const openAddModal = () => {
    addForm.reset();
    showAddModal.value = true;
    addItem(); // Start with one empty row
};

const addItem = () => {
    addForm.items.push({
        product_id: '',
        quantity: 1,
        unit_price: 0
    });
};

const removeItem = (index) => {
    addForm.items.splice(index, 1);
};

const onProductSelect = (index) => {
    const item = addForm.items[index];
    const product = props.products.find(p => p.id === item.product_id);
    if (product) {
        // Pre-fill the cost price as the default unit price for a purchase
        item.unit_price = parseFloat(product.cost) || parseFloat(product.price) || 0;
    }
};

const grandTotal = computed(() => {
    return addForm.items.reduce((total, item) => {
        return total + ((item.quantity || 0) * (item.unit_price || 0));
    }, 0);
});

const submitPurchase = () => {
    addForm.post('/purchases', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
        }
    });
};

const confirmDelete = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(`/purchases/${itemToDelete.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                itemToDelete.value = null;
            }
        });
    }
};
</script>
