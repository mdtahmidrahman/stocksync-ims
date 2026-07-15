<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Order Management</h1>
      <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        New Order
      </button>
    </div>

    <div class="bg-white dark:bg-[#151515] rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Tabs -->
      <div class="border-b border-gray-100 dark:border-gray-800 px-4 flex gap-6">
        <button v-for="tab in ['All Orders', 'Pending', 'Processing', 'Completed']" :key="tab" @click="activeTab = tab" class="py-4 text-sm font-medium transition-colors relative" :class="activeTab === tab ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
          {{ tab }}
          <div v-if="activeTab === tab" class="absolute bottom-0 left-0 w-full h-0.5 bg-primary-500 dark:bg-primary-400 rounded-t-full"></div>
        </button>
      </div>

      <div v-if="filteredOrders.length === 0" class="p-12 text-center">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No orders found</h3>
          <p class="text-gray-500 dark:text-gray-400 mb-6">Create a new order or try a different tab.</p>
          <button @click="showAddModal = true" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">Create Order</button>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-[#151515]">
            <tr class="bg-gray-50 dark:bg-transparent border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider font-semibold">
              <th class="p-4 font-semibold whitespace-nowrap">Order ID</th>
              <th class="p-4 font-semibold whitespace-nowrap">Date</th>
              <th class="p-4 font-semibold whitespace-nowrap">Customer</th>
              <th class="p-4 font-semibold whitespace-nowrap text-center">Status</th>
              <th class="p-4 font-semibold whitespace-nowrap">Total</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors border-b border-gray-100 dark:border-gray-800/50" v-for="order in filteredOrders" :key="order.id">
              <td class="p-4 font-semibold text-gray-900 dark:text-white">
                {{ order.order_number }}
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}</td>
              <td class="p-4">
                <div class="flex items-center gap-2">
                   <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-semibold text-gray-600 dark:text-gray-300 shrink-0">C</div>
                   <div class="font-medium text-gray-900 dark:text-white">{{ order.customer ? order.customer.name : 'Unknown' }}</div>
                </div>
              </td>
              <td class="p-4 text-center">
                <span :class="{
                    'bg-yellow-900/30 text-yellow-500': order.status === 'pending',
                    'bg-blue-900/30 text-blue-500': order.status === 'processing',
                    'bg-indigo-900/30 text-indigo-500': order.status === 'shipped',
                    'bg-green-900/30 text-green-500': order.status === 'delivered',
                    'bg-red-900/30 text-red-500': order.status === 'cancelled'
                }" class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide">
                  {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                </span>
              </td>
              <td class="p-4 text-gray-900 dark:text-white font-semibold whitespace-nowrap">{{ currencySymbol }}{{ parseFloat(order.total_amount).toFixed(2) }}</td>
              <td class="p-4 whitespace-nowrap">
                <div class="flex items-center justify-end gap-3">
                  <a :href="'/invoices/order/' + order.id" target="_blank" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300 font-semibold inline-flex items-center gap-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                      Invoice
                  </a>
                  <button @click="openViewModal(order)" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-semibold">View</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Record Order Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false" maxWidth="5xl">
      <template #title>Create New Order</template>
      <template #body>
        <form @submit.prevent="submitOrder" id="orderForm" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customer *</label>
              <select v-model="addForm.customer_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="" disabled>Choose a customer...</option>
                <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
              </select>
              <div v-if="addForm.errors.customer_id" class="text-red-500 text-xs mt-1">{{ addForm.errors.customer_id }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fulfillment Warehouse *</label>
              <select v-model="addForm.warehouse_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="" disabled>Choose a warehouse...</option>
                <option v-for="warehouse in warehouses" :key="warehouse.id" :value="warehouse.id">{{ warehouse.name }}</option>
              </select>
              <div v-if="addForm.errors.warehouse_id" class="text-red-500 text-xs mt-1">{{ addForm.errors.warehouse_id }}</div>
            </div>
          </div>

          <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Order Items *</label>
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
        </form>
      </template>
      <template #footer>
        <div class="flex justify-between items-center w-full">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <svg class="w-4 h-4 inline mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Stock levels are NOT deducted until the order is Shipped.
            </div>
            <div class="flex gap-2">
                <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Cancel</button>
                <button form="orderForm" type="submit" :disabled="addForm.processing || addForm.items.length === 0" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Create Order</button>
            </div>
        </div>
      </template>
    </Modal>

    <!-- Manage Order Details Modal -->
    <Modal :show="showManageModal" @close="showManageModal = false" maxWidth="3xl">
      <template #title>Manage Order: {{ activeOrder?.order_number }}</template>
      <template #body>
        <div v-if="activeOrder" class="space-y-8">
            
            <!-- Grid for Order Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50 dark:bg-gray-800/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Customer</div>
                    <div class="font-bold text-gray-900 dark:text-white text-lg">{{ activeOrder.customer.name }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Amount</div>
                    <div class="font-bold text-gray-900 dark:text-white text-lg">{{ currencySymbol }}{{ parseFloat(activeOrder.total_amount).toFixed(2) }}</div>
                </div>

                <!-- Update Status Form -->
                <div class="sm:col-span-2 mt-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <form @submit.prevent="updateStatus" class="flex flex-col sm:flex-row sm:items-end gap-4">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fulfillment Status</label>
                            <select v-model="updateForm.status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped (Deducts Stock)</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" :disabled="updateForm.processing" class="px-6 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-sm font-semibold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors shrink-0 disabled:opacity-50">
                            Update Status
                        </button>
                    </form>
                    <div v-if="updateForm.errors.status" class="text-red-500 text-xs mt-1">{{ updateForm.errors.status }}</div>
                </div>
            </div>

            <!-- Items List -->
            <div>
                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Order Items</h4>
                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="p-3 font-medium">Product</th>
                                <th class="p-3 font-medium text-center">Qty</th>
                                <th class="p-3 font-medium text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="item in activeOrder.items" :key="item.id" class="bg-white dark:bg-gray-900">
                                <td class="p-3 font-medium text-gray-900 dark:text-white">{{ item.product.name }}</td>
                                <td class="p-3 text-center">{{ item.quantity }}</td>
                                <td class="p-3 text-right">{{ currencySymbol }}{{ parseFloat(item.subtotal).toFixed(2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payments Section -->
            <div>
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Payments ({{ activeOrder.payment_status }})</h4>
                    <div class="text-sm font-medium text-gray-500">
                        Paid: {{ currencySymbol }}{{ totalPaidAmount.toFixed(2) }} / {{ currencySymbol }}{{ parseFloat(activeOrder.total_amount).toFixed(2) }}
                    </div>
                </div>
                
                <!-- Payment History -->
                <div class="space-y-2 mb-4" v-if="activeOrder.payments && activeOrder.payments.length > 0">
                    <div v-for="payment in activeOrder.payments" :key="payment.id" class="flex justify-between items-center bg-gray-50 dark:bg-gray-800/30 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">{{ currencySymbol }}{{ parseFloat(payment.amount).toFixed(2) }} via {{ payment.payment_method }}</div>
                            <div class="text-xs text-gray-500">{{ new Date(payment.payment_date).toLocaleDateString() }} - Ref: {{ payment.reference_number }}</div>
                        </div>
                    </div>
                </div>

                <!-- Add Payment Form -->
                <form v-if="activeOrder.payment_status !== 'paid'" @submit.prevent="submitPayment" class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-4 rounded-xl">
                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Record New Payment</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Amount *</label>
                            <input v-model="paymentForm.amount" type="number" step="0.01" max="999999" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-black text-sm" />
                            <div v-if="paymentForm.errors.amount" class="text-red-500 text-xs mt-1">{{ paymentForm.errors.amount }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Method *</label>
                            <select v-model="paymentForm.payment_method" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-black text-sm">
                                <option value="Cash">Cash</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Check">Check</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" :disabled="paymentForm.processing" class="w-full px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                        Record Payment
                    </button>
                </form>

            </div>
        </div>
      </template>
      <template #footer>
        <button @click="showManageModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Close</button>
      </template>
    </Modal>

    <ConfirmDeleteModal 
      :show="showDeleteModal" 
      message="Are you sure you want to delete this order? If it was shipped, the stock quantities will be restored."
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
    orders: Object,
    customers: Array,
    products: Array,
    warehouses: Array,
});

const showAddModal = ref(false);
const showManageModal = ref(false);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);
const activeOrder = ref(null);
const activeTab = ref('All Orders');

const filteredOrders = computed(() => {
    if (!props.orders || !props.orders.data) return [];
    if (activeTab.value === 'All Orders') return props.orders.data;
    if (activeTab.value === 'Pending') return props.orders.data.filter(o => o.status === 'pending');
    if (activeTab.value === 'Processing') return props.orders.data.filter(o => o.status === 'processing');
    if (activeTab.value === 'Completed') return props.orders.data.filter(o => ['shipped', 'delivered'].includes(o.status));
    return props.orders.data;
});

const pendingCount = computed(() => props.orders.data.filter(o => o.status === 'pending').length);

const addForm = useForm({
    customer_id: '',
    warehouse_id: '',
    notes: '',
    items: []
});

const updateForm = useForm({
    status: ''
});

const paymentForm = useForm({
    payable_id: '',
    payable_type: 'App\\Models\\Order',
    amount: '',
    payment_method: 'Cash',
    payment_date: new Date().toISOString().split('T')[0],
});

const totalPaidAmount = computed(() => {
    if (!activeOrder.value || !activeOrder.value.payments) return 0;
    return activeOrder.value.payments.reduce((acc, p) => acc + parseFloat(p.amount), 0);
});

const grandTotal = computed(() => {
    return addForm.items.reduce((total, item) => {
        return total + ((item.quantity || 0) * (item.unit_price || 0));
    }, 0);
});

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
        item.unit_price = parseFloat(product.price) || 0;
    }
};

const submitOrder = () => {
    addForm.post('/orders', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        }
    });
};

const openViewModal = (order) => {
    activeOrder.value = order;
    updateForm.status = order.status;
    
    // Set default payment amount to remaining balance
    const remaining = parseFloat(order.total_amount) - totalPaidAmount.value;
    paymentForm.amount = remaining > 0 ? remaining.toFixed(2) : 0;
    paymentForm.payable_id = order.id;

    showManageModal.value = true;
};

const updateStatus = () => {
    updateForm.put(`/orders/${activeOrder.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            // refresh activeOrder reference
            activeOrder.value = props.orders.data.find(o => o.id === activeOrder.value.id);
        }
    });
};

const submitPayment = () => {
    paymentForm.post('/payments', {
        preserveScroll: true,
        onSuccess: () => {
            // refresh activeOrder reference
            paymentForm.reset('amount');
            activeOrder.value = props.orders.data.find(o => o.id === activeOrder.value.id);
            const remaining = parseFloat(activeOrder.value.total_amount) - totalPaidAmount.value;
            paymentForm.amount = remaining > 0 ? remaining.toFixed(2) : 0;
        }
    });
};

const confirmDelete = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    if (itemToDelete.value) {
        router.delete(`/orders/${itemToDelete.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                itemToDelete.value = null;
            }
        });
    }
};
</script>
