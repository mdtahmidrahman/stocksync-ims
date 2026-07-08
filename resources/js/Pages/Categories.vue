<template>
  <AppLayout>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Categories</h1>
      <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add New Category
      </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Toolbar -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="relative w-full sm:w-64">
          <input v-model="search" type="text" placeholder="Search category..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a :href="`/categories/export?search=${search}`" class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center justify-center flex-1 sm:flex-none">Export</a>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold w-12">
                <input type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600">
              </th>
              <th class="p-4 font-semibold whitespace-nowrap">Category Name</th>
              <th class="p-4 font-semibold whitespace-nowrap">Description</th>
              <th class="p-4 font-semibold whitespace-nowrap">Status</th>
              <th class="p-4 font-semibold whitespace-nowrap">Products</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="(cat, i) in categories.data" :key="cat.id">
              <td class="p-4">
                <input type="checkbox" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600">
              </td>
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white">{{ cat.name }}</div>
              </td>
              <td class="p-4 text-sm text-gray-500 dark:text-gray-400">{{ cat.description }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                  Active
                </span>
              </td>
              <td class="p-4 text-sm font-medium text-gray-900 dark:text-white">-</td>
              <td class="p-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-3">
                  <button @click="openEditModal(cat)" class="text-gray-400 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                  </button>
                  <button @click="deleteCategory(cat.id)" class="text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 bg-gray-50/50 dark:bg-gray-900/50">
        <span>Showing {{ categories.from || 0 }} to {{ categories.to || 0 }} of {{ categories.total }} results</span>
        <div class="flex gap-1">
          <component :is="categories.prev_page_url ? 'Link' : 'button'" :href="categories.prev_page_url" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800" :class="{ 'opacity-50 cursor-not-allowed': !categories.prev_page_url }" :disabled="!categories.prev_page_url">Previous</component>
          <component :is="categories.next_page_url ? 'Link' : 'button'" :href="categories.next_page_url" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800" :class="{ 'opacity-50 cursor-not-allowed': !categories.next_page_url }" :disabled="!categories.next_page_url">Next</component>
        </div>
      </div>
    </div>

    <!-- Add Category Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false" title="Add New Category">
      <template #body>
        <form @submit.prevent="saveCategory" class="space-y-4" id="addCategoryForm">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label>
            <input v-model="addForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. Disposables" />
            <div v-if="addForm.errors.name" class="text-red-500 text-xs mt-1">{{ addForm.errors.name }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
            <textarea v-model="addForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Brief description of the category..."></textarea>
            <div v-if="addForm.errors.description" class="text-red-500 text-xs mt-1">{{ addForm.errors.description }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Cancel</button>
        <button form="addCategoryForm" type="submit" :disabled="addForm.processing" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Save Category</button>
      </template>
    </Modal>

    <!-- Edit Category Modal -->
    <Modal :show="showEditModal" @close="showEditModal = false" title="Edit Category">
      <template #body>
        <form @submit.prevent="updateCategory" class="space-y-4" id="editCategoryForm">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label>
            <input v-model="editForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
            <textarea v-model="editForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm"></textarea>
            <div v-if="editForm.errors.description" class="text-red-500 text-xs mt-1">{{ editForm.errors.description }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <button @click="showEditModal = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg transition-colors">Cancel</button>
        <button form="editCategoryForm" type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Update Category</button>
      </template>
    </Modal>
  </AppLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    categories: {
        type: Object,
        default: () => ({ data: [], total: 0, from: 0, to: 0, prev_page_url: null, next_page_url: null })
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
    router.get('/categories', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

const showAddModal = ref(false);
const showEditModal = ref(false);
const editingCategoryId = ref(null);

const addForm = useForm({
    name: '',
    description: ''
});

const editForm = useForm({
    name: '',
    description: ''
});

const saveCategory = () => {
    addForm.post('/categories', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        }
    });
};

const openEditModal = (cat) => {
    editingCategoryId.value = cat.id;
    editForm.name = cat.name;
    editForm.description = cat.description;
    showEditModal.value = true;
};

const updateCategory = () => {
    editForm.put(`/categories/${editingCategoryId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
        }
    });
};

const deleteCategory = (id) => {
    if (confirm('Are you sure you want to delete this category?')) {
        router.delete(`/categories/${id}`, { preserveScroll: true });
    }
};
</script>
