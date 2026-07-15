<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Sales & Invoices</h1>
      <button @click="openPosModal" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New POS Sale
      </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales Record</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ currencySymbol }}{{ totalSalesAmount.toFixed(2) }}</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Invoices</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ sales.total }}</div>
      </div>
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Recent Customers</div>
        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ customers.length }}</div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <div v-if="sales.data.length === 0" class="p-12 text-center">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No sales yet</h3>
          <p class="text-gray-500 dark:text-gray-400 mb-6">Process your first customer transaction using the POS system.</p>
          <button @click="openPosModal" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">New POS Sale</button>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-black/90">
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Invoice #</th>
              <th class="p-4 font-semibold whitespace-nowrap">Customer</th>
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Payment</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Total</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="sale in sales.data" :key="sale.id">
              <td class="p-4 text-sm font-medium text-primary-600 dark:text-primary-400">{{ sale.invoice_number }}</td>
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white">{{ sale.customer ? sale.customer.name : 'Walk-in Customer' }}</div>
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">{{ new Date(sale.created_at).toLocaleString() }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-300">
                  {{ sale.payment_method }}
                </span>
              </td>
              <td class="p-4 text-right text-gray-900 dark:text-white font-medium whitespace-nowrap">{{ currencySymbol }}{{ parseFloat(sale.total_amount).toFixed(2) }}</td>
              <td class="p-4 whitespace-nowrap">
                <div class="flex items-center justify-end gap-3">
                  <a :href="'/invoices/sale/' + sale.id" target="_blank" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 text-sm font-medium inline-flex items-center gap-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                      Invoice
                  </a>
                  <button @click="confirmDelete(sale.id)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- POS Sale Modal -->
    <Modal :show="showPosModal" @close="showPosModal = false" maxWidth="5xl">
      <template #title>Point of Sale (POS)</template>
      <template #body>
        <form @submit.prevent="submitSale" id="saleForm" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
          
          <!-- Left Side: Cart & Search -->
          <div class="md:col-span-2 lg:col-span-3 flex flex-col h-[550px]">
            <!-- Product Selector (Replacement for barcode scanner for now) -->
            <div class="relative mb-6">
              <select @change="quickAddProduct" v-model="quickSelectId" class="w-full px-4 py-3.5 border-2 border-primary-100 dark:border-primary-900/30 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:border-primary-500 shadow-sm text-base">
                  <option value="" disabled selected>Search products to add to cart...</option>
                  <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }} - {{ currencySymbol }}{{ parseFloat(product.price).toFixed(2) }} (Stock: {{ product.stock_quantity }})</option>
              </select>
            </div>
            
            <div v-if="addForm.errors.items" class="text-red-500 text-xs mb-2">{{ addForm.errors.items }}</div>
            <div v-if="addForm.errors.error" class="bg-red-100 text-red-700 p-3 rounded-lg text-sm mb-4">
                {{ addForm.errors.error }}
            </div>

            <!-- Cart Table -->
            <div class="flex-1 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden flex flex-col">
              <div class="overflow-y-auto flex-1 p-4 sm:p-6">
                <table class="w-full text-left">
                  <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase border-b border-gray-100 dark:border-gray-800 pb-2">
                    <tr>
                      <th class="pb-3 font-semibold">Product</th>
                      <th class="pb-3 font-semibold text-center">Qty</th>
                      <th class="pb-3 font-semibold text-right">Price</th>
                      <th class="pb-3 font-semibold text-right pl-4">Subtotal</th>
                      <th class="pb-3 pl-4"></th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <tr v-if="addForm.items.length === 0">
                        <td colspan="5" class="py-12 text-center text-gray-500 text-sm">Cart is empty. Select a product above.</td>
                    </tr>
                    <tr v-for="(item, index) in addForm.items" :key="index" class="text-sm hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                      <td class="py-4 pr-4 font-medium text-gray-900 dark:text-white max-w-[200px] sm:max-w-none truncate" :title="getProductName(item.product_id)">
                        {{ getProductName(item.product_id) }}
                      </td>
                      <td class="py-4 px-2">
                        <div class="flex items-center justify-center gap-1 sm:gap-2">
                          <button type="button" @click="item.quantity > 1 ? item.quantity-- : null" class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300 font-medium">-</button>
                          <span class="w-6 text-center font-semibold text-gray-900 dark:text-white">{{ item.quantity }}</span>
                          <button type="button" @click="item.quantity++" class="w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-gray-600 dark:text-gray-300 font-medium">+</button>
                        </div>
                      </td>
                      <td class="py-4 text-right text-gray-500 dark:text-gray-400">{{ currencySymbol }}{{ item.unit_price.toFixed(2) }}</td>
                      <td class="py-4 text-right font-semibold text-gray-900 dark:text-white pl-4">{{ currencySymbol }}{{ (item.unit_price * item.quantity).toFixed(2) }}</td>
                      <td class="py-4 text-right pl-4">
                          <button type="button" @click="removeItem(index)" class="text-gray-400 hover:text-red-500 p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Remove item">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                          </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="bg-gray-50 dark:bg-gray-900 p-5 border-t border-gray-200 dark:border-gray-800 text-sm">
                <div class="flex justify-between mb-2"><span class="text-gray-500">Subtotal</span><span class="font-medium text-gray-900 dark:text-white">{{ currencySymbol }}{{ grandTotal.toFixed(2) }}</span></div>
                <div class="flex justify-between mb-2"><span class="text-gray-500">Tax</span><span class="font-medium text-gray-900 dark:text-white">{{ currencySymbol }}0.00</span></div>
                <div class="flex justify-between mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 text-xl font-bold"><span class="text-gray-900 dark:text-white">Total</span><span class="text-primary-600 dark:text-primary-400">{{ currencySymbol }}{{ grandTotal.toFixed(2) }}</span></div>
              </div>
            </div>
          </div>

          <!-- Right Side: Payment & Customer -->
          <div class="md:col-span-1 flex flex-col gap-6">
            <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Customer (Optional)</label>
                <select v-model="addForm.customer_id" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm shadow-sm transition-colors">
                    <option value="">Walk-in Customer</option>
                    <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }} ({{ customer.phone ?? customer.email }})</option>
                </select>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800">
              <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Payment Method</label>
              <div class="flex flex-col gap-3">
                <button type="button" @click="addForm.payment_method = 'card'" :class="addForm.payment_method === 'card' ? 'border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 shadow-sm border-2' : 'border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'" class="py-3 px-4 rounded-xl text-sm font-semibold transition-all text-left flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Credit / Debit Card
                </button>
                <button type="button" @click="addForm.payment_method = 'cash'" :class="addForm.payment_method === 'cash' ? 'border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 shadow-sm border-2' : 'border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'" class="py-3 px-4 rounded-xl text-sm font-semibold transition-all text-left flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Cash
                </button>
                <button type="button" @click="addForm.payment_method = 'bank_transfer'" :class="addForm.payment_method === 'bank_transfer' ? 'border-primary-500 bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 shadow-sm border-2' : 'border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'" class="py-3 px-4 rounded-xl text-sm font-semibold transition-all text-left flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                    Bank Transfer
                </button>
              </div>
            </div>

            <div class="mt-auto">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Order Notes</label>
                <textarea v-model="addForm.notes" rows="3" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm shadow-sm transition-colors" placeholder="Any special instructions..."></textarea>
            </div>
          </div>

        </form>
      </template>
      <template #footer>
        <div class="flex justify-between items-center w-full">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 inline mr-1 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Stock levels will be deducted immediately.
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" @click="showPosModal = false" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">Cancel Sale</button>
                <button form="saleForm" type="submit" :disabled="addForm.processing || addForm.items.length === 0" class="px-6 py-2 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors shadow-sm flex items-center gap-2 disabled:opacity-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Complete Checkout
                </button>
            </div>
        </div>
      </template>
    </Modal>

    <ConfirmDeleteModal 
      :show="showDeleteModal" 
      message="Are you sure you want to delete this sale? The stock quantities that were deducted will be reverted."
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
    sales: Object,
    customers: Array,
    products: Array,
});

const showPosModal = ref(false);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const quickSelectId = ref('');

const addForm = useForm({
    customer_id: '',
    payment_method: 'cash',
    reference_number: '',
    notes: '',
    items: []
});

const openPosModal = () => {
    addForm.reset();
    quickSelectId.value = '';
    showPosModal.value = true;
};

const quickAddProduct = () => {
    if (!quickSelectId.value) return;
    
    const product = props.products.find(p => p.id === quickSelectId.value);
    if (!product) return;

    // Check if already in cart
    const existingItem = addForm.items.find(i => i.product_id === product.id);
    if (existingItem) {
        existingItem.quantity++;
    } else {
        addForm.items.push({
            product_id: product.id,
            quantity: 1,
            unit_price: parseFloat(product.price) || 0
        });
    }
    
    // Reset selector
    quickSelectId.value = '';
};

const removeItem = (index) => {
    addForm.items.splice(index, 1);
};

const getProductName = (id) => {
    const product = props.products.find(p => p.id === id);
    return product ? product.name : 'Unknown Product';
};

const grandTotal = computed(() => {
    return addForm.items.reduce((total, item) => {
        return total + ((item.quantity || 0) * (item.unit_price || 0));
    }, 0);
});

const totalSalesAmount = computed(() => {
    if(!props.sales || !props.sales.data) return 0;
    return props.sales.data.reduce((total, sale) => total + parseFloat(sale.total_amount || 0), 0);
});

const submitSale = () => {
    addForm.post('/sales', {
        preserveScroll: true,
        onSuccess: () => {
            showPosModal.value = false;
        }
    });
};

const confirmDelete = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(`/sales/${itemToDelete.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                itemToDelete.value = null;
            }
        });
    }
};
</script>
