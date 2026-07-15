<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Customers</h1>
      <div class="flex items-center gap-3 self-start sm:self-auto">
        <Dropdown align="right" width="48" class="flex-1 sm:flex-none">
            <template #trigger>
              <button type="button" class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center justify-center w-full sm:w-auto transition-colors">
                <span>Export</span>
                <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </button>
            </template>
            <template #content>
              <a :href="`/customers/export?search=${search}&format=csv`" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Export as CSV</a>
              <a :href="`/customers/export?search=${search}&format=xlsx`" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Export as Excel</a>
            </template>
        </Dropdown>
        <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Add Customer
        </button>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="relative w-full sm:w-96">
          <input v-model="search" type="text" placeholder="Search customers by name, email, or phone..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
      </div>

      <div v-if="localCustomers.length === 0" class="p-12 text-center">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No customers found</h3>
          <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by adding your first customer to the system.</p>
          <button @click="showAddModal = true" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">Add Customer</button>
      </div>

      <div v-else class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)]">
        <table class="w-full text-left border-collapse">
          <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-black/90">
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Customer Name</th>
              <th class="p-4 font-semibold whitespace-nowrap">Contact Info</th>
              <th class="p-4 font-semibold whitespace-nowrap">Loyalty Points</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="customer in localCustomers" :key="customer.id">
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white">{{ customer.name }}</div>
                <div class="text-xs text-gray-500 truncate max-w-[200px]" :title="customer.address">{{ customer.address }}</div>
              </td>
              <td class="p-4">
                <div class="text-sm text-gray-900 dark:text-white">{{ customer.email || 'N/A' }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ customer.phone || 'N/A' }}</div>
              </td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400">
                  {{ customer.loyalty_points }} pts
                </span>
              </td>
              <td class="p-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-3">
                  <button @click="openEditModal(customer)" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium transition-colors">Edit</button>
                  <button @click="confirmDeleteCustomer(customer.id)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium transition-colors">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div ref="loadMoreTarget" class="h-4"></div>
    </div>

    <!-- Add Customer Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false">
      <template #title>Add New Customer</template>
      <template #body>
        <form @submit.prevent="saveCustomer" id="addCustomerForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name / Company Name *</label>
            <input v-model="addForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="John Doe" />
            <div v-if="addForm.errors.name" class="text-red-500 text-xs mt-1">{{ addForm.errors.name }}</div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
              <input v-model="addForm.email" type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="john.doe@example.com" />
              <div v-if="addForm.errors.email" class="text-red-500 text-xs mt-1">{{ addForm.errors.email }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
              <input v-model="addForm.phone" type="tel" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="+1 (555) 000-0000" />
              <div v-if="addForm.errors.phone" class="text-red-500 text-xs mt-1">{{ addForm.errors.phone }}</div>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Billing Address</label>
            <textarea v-model="addForm.address" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="3" placeholder="123 Main St, City, Country"></textarea>
            <div v-if="addForm.errors.address" class="text-red-500 text-xs mt-1">{{ addForm.errors.address }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loyalty Points</label>
            <input v-model="addForm.loyalty_points" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="0" />
            <div v-if="addForm.errors.loyalty_points" class="text-red-500 text-xs mt-1">{{ addForm.errors.loyalty_points }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="addCustomerForm" type="submit" :disabled="addForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Save</button>
        </div>
      </template>
    </Modal>

    <!-- Edit Customer Modal -->
    <Modal :show="showEditModal" @close="showEditModal = false">
      <template #title>Edit Customer</template>
      <template #body>
        <form @submit.prevent="updateCustomer" id="editCustomerForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name / Company Name *</label>
            <input v-model="editForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
              <input v-model="editForm.email" type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="editForm.errors.email" class="text-red-500 text-xs mt-1">{{ editForm.errors.email }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
              <input v-model="editForm.phone" type="tel" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="editForm.errors.phone" class="text-red-500 text-xs mt-1">{{ editForm.errors.phone }}</div>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Billing Address</label>
            <textarea v-model="editForm.address" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="3"></textarea>
            <div v-if="editForm.errors.address" class="text-red-500 text-xs mt-1">{{ editForm.errors.address }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loyalty Points</label>
            <input v-model="editForm.loyalty_points" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            <div v-if="editForm.errors.loyalty_points" class="text-red-500 text-xs mt-1">{{ editForm.errors.loyalty_points }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showEditModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="editCustomerForm" type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Update</button>
        </div>
      </template>
    </Modal>

    <ConfirmDeleteModal 
      :show="showDeleteModal" 
      message="Are you sure you want to delete this customer? This will affect any related sales."
      @close="showDeleteModal = false; itemToDelete = null"
      @confirm="executeDeleteCustomer"
    />
  </div>
  </AppLayout>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import ConfirmDeleteModal from '../Components/ConfirmDeleteModal.vue';
import Dropdown from '../Components/Dropdown.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    customers: Object,
    filters: {
        type: Object,
        default: () => ({})
    }
});

const search = ref(props.filters.search || '');

// Infinite Scroll Logic
const localCustomers = ref([]);
watch(() => props.customers.data, (newData) => {
    if (props.customers.current_page === 1) {
        localCustomers.value = newData;
    } else {
        localCustomers.value = [...localCustomers.value, ...newData];
    }
}, { immediate: true });

const loadMoreTarget = ref(null);
let observer = null;

onMounted(() => {
    observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && props.customers.next_page_url) {
            router.get(props.customers.next_page_url, {}, {
                preserveState: true,
                preserveScroll: true,
            });
        }
    }, { rootMargin: '100px' });
    
    if (loadMoreTarget.value) observer.observe(loadMoreTarget.value);
});

onUnmounted(() => {
    if (observer) observer.disconnect();
});

watch(search, debounce((searchVal) => {
    router.get('/customers', { search: searchVal }, {
        preserveState: true,
        replace: true
    });
}, 300));

const showAddModal = ref(false);
const showEditModal = ref(false);
const editingCustomerId = ref(null);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);

const addForm = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    loyalty_points: 0
});

const editForm = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    loyalty_points: 0
});

const saveCustomer = () => {
    addForm.post('/customers', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        }
    });
};

const openEditModal = (customer) => {
    editingCustomerId.value = customer.id;
    editForm.name = customer.name;
    editForm.email = customer.email;
    editForm.phone = customer.phone;
    editForm.address = customer.address;
    editForm.loyalty_points = customer.loyalty_points;
    showEditModal.value = true;
};

const updateCustomer = () => {
    editForm.put(`/customers/${editingCustomerId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
        }
    });
};

const confirmDeleteCustomer = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const executeDeleteCustomer = () => {
    if (itemToDelete.value) {
        router.delete(`/customers/${itemToDelete.value}`, { 
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                itemToDelete.value = null;
            }
        });
    }
};
</script>
