<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Suppliers</h1>
      <div class="flex items-center gap-3 self-start sm:self-auto">
        <Dropdown align="right" width="48" class="flex-1 sm:flex-none">
            <template #trigger>
              <button type="button" class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center justify-center w-full sm:w-auto transition-colors">
                <span>Export</span>
                <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
              </button>
            </template>
            <template #content>
              <a :href="`/suppliers/export?search=${search}&format=csv`" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Export as CSV</a>
              <a :href="`/suppliers/export?search=${search}&format=xlsx`" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Export as Excel</a>
            </template>
        </Dropdown>
        <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm">
          + Add Supplier
        </button>
      </div>
    </div>

    <!-- Toolbar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-center">
      <div class="relative w-full sm:w-96">
        <input v-model="search" type="text" placeholder="Search suppliers by name, email, or phone..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
      </div>
    </div>

    <div v-if="localSuppliers.length === 0" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-12 text-center">
        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No suppliers found</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by adding your first supplier to the system.</p>
        <button @click="showAddModal = true" class="text-primary-600 dark:text-primary-400 font-semibold hover:underline">Add Supplier</button>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Supplier Cards -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 transition-colors relative group" v-for="supplier in localSuppliers" :key="supplier.id">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3 w-full">
            <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-xl shrink-0 uppercase">
              {{ supplier.name.charAt(0) }}
            </div>
            <div class="overflow-hidden w-full">
              <h3 class="font-bold text-gray-900 dark:text-white text-lg truncate" :title="supplier.name">{{ supplier.name }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400 truncate" :title="supplier.address">{{ supplier.address || 'No address provided' }}</p>
            </div>
          </div>
          
          <Dropdown align="right" width="48" class="absolute right-4 top-4">
            <template #trigger>
              <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
              </button>
            </template>
            <template #content>
              <a href="#" @click.prevent="openEditModal(supplier)" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Edit</a>
              <a href="#" @click.prevent="confirmDeleteSupplier(supplier.id)" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">Delete</a>
            </template>
          </Dropdown>
        </div>
        
        <div class="space-y-3 mt-6">
          <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 mr-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span class="truncate">{{ supplier.email || 'N/A' }}</span>
          </div>
          <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5 mr-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
            {{ supplier.phone || 'N/A' }}
          </div>
        </div>

        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
          <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
            Active
          </span>
          <!-- Future implementation -->
          <!-- <button @click="showHistoryModal = true" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700">View Transactions</button> -->
        </div>
      </div>
    </div>
    
    <div ref="loadMoreTarget" class="h-4 mt-4"></div>

    <!-- Add Supplier Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false">
      <template #title>Add New Supplier</template>
      <template #body>
        <form @submit.prevent="saveSupplier" id="addSupplierForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name *</label>
            <input v-model="addForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. Acme Corp" />
            <div v-if="addForm.errors.name" class="text-red-500 text-xs mt-1">{{ addForm.errors.name }}</div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
              <input v-model="addForm.email" type="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="contact@company.com" />
              <div v-if="addForm.errors.email" class="text-red-500 text-xs mt-1">{{ addForm.errors.email }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
              <input v-model="addForm.phone" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="+1 (555) 000-0000" />
              <div v-if="addForm.errors.phone" class="text-red-500 text-xs mt-1">{{ addForm.errors.phone }}</div>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
            <textarea v-model="addForm.address" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="3" placeholder="123 Business Rd..."></textarea>
            <div v-if="addForm.errors.address" class="text-red-500 text-xs mt-1">{{ addForm.errors.address }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="addSupplierForm" type="submit" :disabled="addForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Save</button>
        </div>
      </template>
    </Modal>

    <!-- Edit Supplier Modal -->
    <Modal :show="showEditModal" @close="showEditModal = false">
      <template #title>Edit Supplier</template>
      <template #body>
        <form @submit.prevent="updateSupplier" id="editSupplierForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name *</label>
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
              <input v-model="editForm.phone" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="editForm.errors.phone" class="text-red-500 text-xs mt-1">{{ editForm.errors.phone }}</div>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
            <textarea v-model="editForm.address" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="3"></textarea>
            <div v-if="editForm.errors.address" class="text-red-500 text-xs mt-1">{{ editForm.errors.address }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showEditModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="editSupplierForm" type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Update</button>
        </div>
      </template>
    </Modal>
    
    <ConfirmDeleteModal 
      :show="showDeleteModal" 
      message="Are you sure you want to delete this supplier? This will affect any related purchases."
      @close="showDeleteModal = false; itemToDelete = null"
      @confirm="executeDeleteSupplier"
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
    suppliers: Object,
    filters: {
        type: Object,
        default: () => ({})
    }
});

const search = ref(props.filters.search || '');

// Infinite Scroll Logic
const localSuppliers = ref([]);
watch(() => props.suppliers.data, (newData) => {
    if (props.suppliers.current_page === 1) {
        localSuppliers.value = newData;
    } else {
        localSuppliers.value = [...localSuppliers.value, ...newData];
    }
}, { immediate: true });

const loadMoreTarget = ref(null);
let observer = null;

onMounted(() => {
    observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting && props.suppliers.next_page_url) {
            router.get(props.suppliers.next_page_url, {}, {
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
    router.get('/suppliers', { search: searchVal }, {
        preserveState: true,
        replace: true
    });
}, 300));

const showAddModal = ref(false);
const showEditModal = ref(false);
const editingSupplierId = ref(null);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);

const addForm = useForm({
    name: '',
    email: '',
    phone: '',
    address: ''
});

const editForm = useForm({
    name: '',
    email: '',
    phone: '',
    address: ''
});

const saveSupplier = () => {
    addForm.post('/suppliers', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        }
    });
};

const openEditModal = (supplier) => {
    editingSupplierId.value = supplier.id;
    editForm.name = supplier.name;
    editForm.email = supplier.email;
    editForm.phone = supplier.phone;
    editForm.address = supplier.address;
    showEditModal.value = true;
};

const updateSupplier = () => {
    editForm.put(`/suppliers/${editingSupplierId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
        }
    });
};

const confirmDeleteSupplier = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const executeDeleteSupplier = () => {
    if (itemToDelete.value) {
        router.delete(`/suppliers/${itemToDelete.value}`, { 
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                itemToDelete.value = null;
            }
        });
    }
};
</script>
