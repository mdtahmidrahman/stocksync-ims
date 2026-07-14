<template>
  <AppLayout>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Products</h1>
      <div class="flex items-center gap-3 self-start sm:self-auto">
        <button @click="showBulkUploadModal = true" class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 border border-gray-300 dark:border-gray-700 transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
          Bulk Upload
        </button>
        <button @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
          Add New Product
        </button>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <!-- Toolbar -->
      <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row gap-4 justify-between items-center bg-gray-50/50 dark:bg-gray-900/50">
        <div class="relative w-full sm:w-64">
          <input v-model="search" type="text" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <Dropdown align="right" width="48" class="flex-1 sm:flex-none w-full sm:w-48">
              <template #trigger>
                <button type="button" class="flex justify-between items-center px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 w-full transition-colors">
                  <span class="truncate">{{ categoryId ? categories.find(c => String(c.id) === String(categoryId))?.name || 'All Categories' : 'All Categories' }}</span>
                  <svg class="w-4 h-4 text-gray-400 ml-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content="{ close }">
                <a href="#" @click.prevent="categoryId = ''; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="categoryId === '' ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">All Categories</a>
                <a href="#" v-for="cat in categories" :key="cat.id" @click.prevent="categoryId = String(cat.id); close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="categoryId === String(cat.id) ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ cat.name }}</a>
              </template>
            </Dropdown>
            <Dropdown align="right" width="48" class="flex-1 sm:flex-none">
              <template #trigger>
                <button type="button" class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center justify-center w-full sm:w-auto transition-colors">
                  <span>Export</span>
                  <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
              </template>
              <template #content>
                <a :href="`/products/export?search=${search}&category_id=${categoryId}&format=csv`" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Export as CSV</a>
                <a :href="`/products/export?search=${search}&category_id=${categoryId}&format=xlsx`" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Export as Excel</a>
              </template>
            </Dropdown>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold whitespace-nowrap">Product Name</th>
              <th class="p-4 font-semibold whitespace-nowrap">SKU</th>
              <th class="p-4 font-semibold whitespace-nowrap">Category</th>
              <th class="p-4 font-semibold whitespace-nowrap">Price</th>
              <th class="p-4 font-semibold whitespace-nowrap">Stock Level</th>
              <th class="p-4 font-semibold whitespace-nowrap text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="product in products.data" :key="product.id">
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0 overflow-hidden">
                    <img v-if="product.image_path" :src="`/storage/${product.image_path}`" class="w-full h-full object-cover" />
                    <svg v-else class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                  </div>
                  <div>
                    <div class="font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ product.name }}</div>
                  </div>
                </div>
              </td>
              <td class="p-4 text-gray-500 dark:text-gray-400 text-sm whitespace-nowrap">{{ product.sku }}</td>
              <td class="p-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/50 text-primary-800 dark:text-primary-300">
                  {{ product.category?.name || 'Uncategorized' }}
                </span>
              </td>
              <td class="p-4 text-gray-900 dark:text-white font-medium whitespace-nowrap">{{ currencySymbol }}{{ parseFloat(product.price).toFixed(2) }}</td>
              <td class="p-4">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ product.stock_quantity }}</span>
              </td>
              <td class="p-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                  <button @click="openEditModal(product)" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium mr-2">Edit</button>
                  <button @click="deleteProduct(product.id)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 bg-gray-50/50 dark:bg-gray-900/50">
        <span>Showing {{ products.from || 0 }} to {{ products.to || 0 }} of {{ products.total }} results</span>
        <div class="flex gap-1">
          <component :is="products.prev_page_url ? 'Link' : 'button'" :href="products.prev_page_url" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800" :class="{ 'opacity-50 cursor-not-allowed': !products.prev_page_url }" :disabled="!products.prev_page_url">Prev</component>
          <component :is="products.next_page_url ? 'Link' : 'button'" :href="products.next_page_url" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800" :class="{ 'opacity-50 cursor-not-allowed': !products.next_page_url }" :disabled="!products.next_page_url">Next</component>
        </div>
      </div>
    </div>

    <!-- Add Product Modal -->
    <Modal :show="showAddModal" :scrollable="false" @close="showAddModal = false">
      <template #title>Add New Product</template>
      <template #body>
        <form @submit.prevent="saveProduct" id="addProductForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Image</label>
            <div class="flex items-center gap-4">
              <div class="w-20 h-20 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl flex items-center justify-center text-gray-400 overflow-hidden relative">
                <img v-if="imagePreview" :src="imagePreview" class="w-full h-full object-cover" />
                <svg v-else class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              </div>
              <div class="relative">
                <input type="file" accept="image/*" @change="handleImageUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                <button type="button" class="text-sm text-primary-600 font-medium">Upload Image</button>
              </div>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Name</label>
            <input v-model="addForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. Wireless Headphones" />
            <div v-if="addForm.errors.name" class="text-red-500 text-xs mt-1">{{ addForm.errors.name }}</div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU</label>
              <input v-model="addForm.sku" type="text" required placeholder="e.g. TECH-102" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="addForm.errors.sku" class="text-red-500 text-xs mt-1">{{ addForm.errors.sku }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
              <Dropdown align="left" width="full" fullWidth>
                <template #trigger>
                  <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                    <span :class="!addForm.category_id ? 'text-gray-500' : ''">{{ addForm.category_id ? categories.find(c => c.id === addForm.category_id)?.name : 'Select a category' }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                  </button>
                </template>
                <template #content="{ close }">
                  <a href="#" v-for="cat in categories" :key="cat.id" @click.prevent="addForm.category_id = cat.id; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="addForm.category_id === cat.id ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ cat.name }}</a>
                </template>
              </Dropdown>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price ({{ currencySymbol }})</label>
              <input v-model="addForm.price" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="0.00" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Initial Stock</label>
              <input v-model="addForm.stock_level" type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="0" />
            </div>
          </div>
          
          <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Smart Reorder Engine</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Reorder Point</label>
                <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. 20" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Reorder Qty</label>
                <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. 100" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Supplier</label>
                <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">Select...</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">TechCorp Inc.</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Global Supplies Ltd</a>
                  </template>
                </Dropdown>
              </div>
            </div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-between w-full items-center">
          <button @click="showAddModal = false; showCustomProductModal = true" type="button" class="text-sm font-semibold text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">Add Customized Product</button>
          <div class="flex gap-2">
            <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
            <button form="addProductForm" type="submit" :disabled="addForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Save Product</button>
          </div>
        </div>
      </template>
    </Modal>

    <!-- Edit Product Modal -->
    <Modal :show="showEditModal" @close="showEditModal = false">
      <template #title>Edit Product</template>
      <template #body>
        <form @submit.prevent="updateProduct" id="editProductForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Name</label>
            <input v-model="editForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU</label>
              <input v-model="editForm.sku" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="editForm.errors.sku" class="text-red-500 text-xs mt-1">{{ editForm.errors.sku }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price ({{ currencySymbol }})</label>
              <input v-model="editForm.price" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            </div>
          </div>
          
          <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Smart Reorder Engine</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Reorder Point</label>
                <input type="number" value="20" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. 20" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Reorder Qty</label>
                <input type="number" value="150" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. 100" />
              </div>
              <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Supplier</label>
                <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">TechCorp Inc.</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">TechCorp Inc.</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Global Supplies Ltd</a>
                  </template>
                </Dropdown>
              </div>
            </div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showEditModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="editProductForm" type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Update Product</button>
        </div>
      </template>
    </Modal>

    <!-- Bulk Upload Modal -->
    <Modal :show="showBulkUploadModal" @close="showBulkUploadModal = false">
      <template #title>Bulk Upload Products</template>
      <template #body>
        <form @submit.prevent="importProducts" id="importProductsForm" class="space-y-4">
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-500 dark:text-gray-400">Upload Spreadsheet or ZIP File</span>
            <a href="/products/export" class="text-primary-600 dark:text-primary-400 font-medium hover:underline">Download Template</a>
          </div>
          <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-xs rounded-lg border border-blue-100 dark:border-blue-800/30">
            <strong>Pro Tip:</strong> Upload a <code>.csv</code> or <code>.xlsx</code> for text data. To bulk import images offline, upload a <code>.zip</code> file containing your spreadsheet and an images folder!
          </div>
          <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-8 flex flex-col items-center justify-center text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors relative">
            <input type="file" accept=".csv, .xlsx, .zip" @change="e => importForm.file = e.target.files[0]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required />
            <div class="w-16 h-16 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-full flex items-center justify-center mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            </div>
            <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ importForm.file ? importForm.file.name : 'Click to upload or drag and drop' }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">CSV, XLSX, ZIP (MAX. 50MB)</p>
          </div>
          <div v-if="importForm.errors.file" class="text-red-500 text-xs mt-1">{{ importForm.errors.file }}</div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showBulkUploadModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="importProductsForm" type="submit" :disabled="importForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Upload</button>
        </div>
      </template>
    </Modal>

    <!-- Custom Product Modal -->
    <Modal :show="showCustomProductModal" @close="showCustomProductModal = false" @save="showCustomProductModal = false">
      <template #title>Add Customized Product</template>
      <template #body>
        <div class="space-y-4">
          <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-blue-800 dark:text-blue-300 flex gap-3">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Custom products are unique variants made to order. They require a base product and defined customization details.</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Base Product</label>
            <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">Select a base product...</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Wireless Headphones (WH-1002)</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Smart Watch (SW-2001)</a>
                  </template>
                </Dropdown>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customization Details</label>
            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="3" placeholder="e.g. Matte black finish, gold engraving on side plate."></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Base Price ({{ currencySymbol }})</label>
              <input type="number" value="129.99" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 sm:text-sm cursor-not-allowed" disabled />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Customization Surcharge ({{ currencySymbol }})</label>
              <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="45.00" />
            </div>
          </div>
        </div>
      </template>
    </Modal>

    <!-- Barcode Preview Modal -->
    <Modal :show="showBarcodeModal" @close="showBarcodeModal = false" @save="showBarcodeModal = false">
      <template #title>Print Barcode</template>
      <template #body>
        <div class="flex flex-col items-center justify-center p-6 space-y-6">
          <div class="text-center">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Wireless Headphones</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">WH-1002</p>
          </div>
          <div class="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
            <!-- Pure CSS Mock Barcode -->
            <div class="h-20 w-64 flex bg-white">
              <div class="h-full w-2 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-4 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-3 bg-black mx-px"></div>
              <div class="h-full w-2 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-4 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-3 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-2 bg-black mx-px"></div>
              <div class="h-full w-5 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-2 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-3 bg-black mx-px"></div>
              <div class="h-full w-2 bg-black mx-px"></div>
              <div class="h-full w-1 bg-black mx-px"></div>
              <div class="h-full w-4 bg-black mx-px"></div>
            </div>
            <div class="text-center font-mono text-sm tracking-[0.2em] mt-2 text-black">
              WH-1002-884
            </div>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showBarcodeModal = false" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Close</button>
          <button @click="showBarcodeModal = false" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Send to Thermal Printer
          </button>
        </div>
      </template>
    </Modal>
  </AppLayout>
</template>

<script setup>
import Dropdown from '../Components/Dropdown.vue';
import { ref, watch } from 'vue';
import { router, useForm, Link, usePage } from '@inertiajs/vue3';
import { useCurrency } from '../Composables/useCurrency';

const { currencySymbol } = useCurrency();
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    products: {
        type: Object,
        default: () => ({ data: [], total: 0, from: 0, to: 0, prev_page_url: null, next_page_url: null })
    },
    categories: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

const search = ref(props.filters.search || '');
const categoryId = ref(props.filters.category_id ? String(props.filters.category_id) : '');

watch([search, categoryId], debounce(([searchVal, catIdVal]) => {
    router.get('/products', { search: searchVal, category_id: catIdVal }, {
        preserveState: true,
        replace: true
    });
}, 300));

const showAddModal = ref(false);
const showEditModal = ref(false);
const showBulkUploadModal = ref(false);
const showCustomProductModal = ref(false);
const showBarcodeModal = ref(false);
const editingProductId = ref(null);

const imageFile = ref(null);
const imagePreview = ref('');

const handleImageUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
        addForm.image = file;
        editForm.image = file;
    }
};

const addForm = useForm({
    name: '',
    sku: '',
    category_id: '',
    price: 0,
    stock_level: 0,
    image: null
});

const editForm = useForm({
    name: '',
    sku: '',
    category_id: '',
    price: 0,
    stock_level: 0,
    image: null
});

const importForm = useForm({
    file: null
});

const importProducts = () => {
    importForm.post('/products/import', {
        preserveScroll: true,
        onSuccess: () => {
            showBulkUploadModal.value = false;
            importForm.reset();
        }
    });
};

const openEditModal = (product) => {
    editingProductId.value = product.id;
    editForm.name = product.name;
    editForm.sku = product.sku;
    editForm.category_id = product.category_id;
    editForm.price = product.price;
    editForm.stock_level = product.stock_quantity;
    imagePreview.value = product.image_path ? `/storage/${product.image_path}` : '';
    showEditModal.value = true;
};

const saveProduct = () => {
    addForm.post('/products', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
            imageFile.value = null;
            imagePreview.value = '';
        }
    });
};

const updateProduct = () => {
    // Inertia requires _method = put for file uploads to work properly
    editForm.transform((data) => ({
      ...data,
      _method: 'put',
    })).post(`/products/${editingProductId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
            imageFile.value = null;
            imagePreview.value = '';
        }
    });
};

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(`/products/${id}`);
    }
};
</script>
