<template>
  <AppLayout>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Warehouses & Transfers</h1>
      <button v-if="activeTab === 'locations'" @click="showAddModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add New Warehouse
      </button>
      <button v-if="activeTab === 'transfers'" @click="showTransferModal = true" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm self-start sm:self-auto flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
        Request Transfer
      </button>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-4 border-b border-gray-200 dark:border-gray-700 mb-6">
      <button @click="activeTab = 'locations'" :class="['px-4 py-3 text-sm font-medium border-b-2 transition-colors', activeTab === 'locations' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300']">Locations Overview</button>
      <button @click="activeTab = 'transfers'" :class="['px-4 py-3 text-sm font-medium border-b-2 transition-colors', activeTab === 'transfers' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300']">Transfer Board</button>
    </div>

    <div v-if="activeTab === 'locations'">
      <!-- Toolbar -->
      <div class="flex justify-between items-center mb-6">
        <div class="relative w-full sm:w-64">
          <input v-model="search" type="text" placeholder="Search warehouses..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex gap-2 w-full sm:w-auto">
            <a :href="`/warehouses/export?search=${search}`" class="px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 flex items-center justify-center flex-1 sm:flex-none">Export</a>
        </div>
      </div>
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Locations</div>
          <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">3</div>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Capacity Used</div>
          <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">68%</div>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Items in Transit</div>
          <div class="text-3xl font-bold text-gray-900 dark:text-white mt-1">1,204</div>
        </div>
      </div>

      <!-- Grid View -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="(wh, i) in warehouses.data" :key="wh.id" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col transition-colors">
          <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-800">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div class="flex items-center gap-2">
              <button @click="openEditModal(wh)" class="text-gray-400 hover:text-blue-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
              </button>
              <button @click="deleteWarehouse(wh.id)" class="text-gray-400 hover:text-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              </button>
            </div>
          </div>
          
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ wh.name }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4 flex-1">{{ wh.location }}</p>
          
          <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-800">
            <div class="flex justify-between text-sm">
              <span class="text-gray-500 dark:text-gray-400">Status:</span>
              <span class="font-medium text-green-600 dark:text-green-400" v-if="wh.is_active">Active</span>
              <span class="font-medium text-red-600 dark:text-red-400" v-else>Inactive</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-500 dark:text-gray-400">Total Items:</span>
              <span class="font-medium text-gray-900 dark:text-white">-</span>
            </div>
            
            <div class="pt-2">
              <div class="flex justify-between text-xs mb-1">
                <span class="font-medium text-gray-700 dark:text-gray-300">Capacity Used</span>
                <span class="font-medium text-gray-700 dark:text-gray-300">0%</span>
              </div>
              <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                <div class="bg-primary-500 h-1.5 rounded-full" style="width: 0%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Pagination -->
      <div class="mt-6 p-4 border border-gray-100 dark:border-gray-800 rounded-xl flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-900">
        <span>Showing {{ warehouses.from || 0 }} to {{ warehouses.to || 0 }} of {{ warehouses.total }} results</span>
        <div class="flex gap-1">
          <component :is="warehouses.prev_page_url ? 'Link' : 'button'" :href="warehouses.prev_page_url" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800" :class="{ 'opacity-50 cursor-not-allowed': !warehouses.prev_page_url }" :disabled="!warehouses.prev_page_url">Previous</component>
          <component :is="warehouses.next_page_url ? 'Link' : 'button'" :href="warehouses.next_page_url" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800" :class="{ 'opacity-50 cursor-not-allowed': !warehouses.next_page_url }" :disabled="!warehouses.next_page_url">Next</component>
        </div>
      </div>
    </div>

    <!-- Transfer Kanban Board -->
    <div v-if="activeTab === 'transfers'" class="flex gap-6 overflow-x-auto pb-4 h-[calc(100vh-240px)] min-h-[500px]">
      
      <!-- Requested Column -->
      <div class="bg-gray-50/50 dark:bg-gray-900/30 rounded-xl border border-gray-200 dark:border-gray-800 flex flex-col w-80 shrink-0">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
          <h3 class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
            Requested
          </h3>
          <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full">2</span>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
          <!-- Card 1 -->
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 cursor-move transition-colors">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-bold text-gray-500 dark:text-gray-400">TRF-0012</span>
              <span class="text-xs text-gray-400">2h ago</span>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-2">Wireless Headphones (x50)</h4>
            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-md mb-3">
              <span class="truncate">East Coast</span>
              <svg class="w-3 h-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              <span class="truncate">Central Hub</span>
            </div>
            <div class="flex justify-between items-center">
              <div class="flex -space-x-2">
                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold border-2 border-white dark:border-gray-800">SM</div>
              </div>
              <button class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-800">Dispatch</button>
            </div>
          </div>
          <!-- Card 2 -->
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 cursor-move transition-colors">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-bold text-gray-500 dark:text-gray-400">TRF-0013</span>
              <span class="text-xs text-gray-400">5h ago</span>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-2">Smart Watch Series 5 (x120)</h4>
            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-md mb-3">
              <span class="truncate">West Coast</span>
              <svg class="w-3 h-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              <span class="truncate">East Coast</span>
            </div>
            <div class="flex justify-between items-center">
              <div class="flex -space-x-2">
                <div class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-[10px] font-bold border-2 border-white dark:border-gray-800">JD</div>
              </div>
              <button class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-800">Dispatch</button>
            </div>
          </div>
        </div>
      </div>

      <!-- In Transit Column -->
      <div class="bg-gray-50/50 dark:bg-gray-900/30 rounded-xl border border-gray-200 dark:border-gray-800 flex flex-col w-80 shrink-0">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
          <h3 class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            In Transit
          </h3>
          <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full">1</span>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
          <!-- Card 3 -->
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 cursor-move transition-colors">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-bold text-gray-500 dark:text-gray-400">TRF-0010</span>
              <span class="text-xs text-gray-400">1d ago</span>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-2">USB-C Cables (x500)</h4>
            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-md mb-3">
              <span class="truncate">Central Hub</span>
              <svg class="w-3 h-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              <span class="truncate">West Coast</span>
            </div>
            <div class="flex justify-between items-center">
              <div class="text-xs text-blue-600 dark:text-blue-400 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                ETA: Tomorrow
              </div>
              <button class="text-xs font-semibold text-green-600 dark:text-green-400 hover:text-green-800">Receive</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Received Column -->
      <div class="bg-gray-50/50 dark:bg-gray-900/30 rounded-xl border border-gray-200 dark:border-gray-800 flex flex-col w-80 shrink-0">
        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
          <h3 class="font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
            Received
          </h3>
          <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full">1</span>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
          <!-- Card 4 -->
          <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 opacity-60">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-bold text-gray-500 dark:text-gray-400">TRF-0009</span>
              <span class="text-xs text-gray-400">2d ago</span>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-2">Mechanical Keyboards (x20)</h4>
            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-md mb-3">
              <span class="truncate">East Coast</span>
              <svg class="w-3 h-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              <span class="truncate">Central Hub</span>
            </div>
            <div class="flex items-center gap-1 text-xs text-green-600 dark:text-green-500 font-medium">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              Received completely
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Warehouse Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false">
      <template #title>Add New Warehouse</template>
      <template #body>
        <form @submit.prevent="saveWarehouse" id="addWarehouseForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warehouse Name</label>
            <input v-model="addForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. Northeast Distribution Center" />
            <div v-if="addForm.errors.name" class="text-red-500 text-xs mt-1">{{ addForm.errors.name }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address / Location</label>
            <input v-model="addForm.location" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="123 Industrial Parkway, Suite A" />
            <div v-if="addForm.errors.location" class="text-red-500 text-xs mt-1">{{ addForm.errors.location }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="addWarehouseForm" type="submit" :disabled="addForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Save</button>
        </div>
      </template>
    </Modal>

    <!-- Edit Warehouse Modal -->
    <Modal :show="showEditModal" @close="showEditModal = false">
      <template #title>Edit Warehouse</template>
      <template #body>
        <form @submit.prevent="updateWarehouse" id="editWarehouseForm" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warehouse Name</label>
            <input v-model="editForm.name" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            <div v-if="editForm.errors.name" class="text-red-500 text-xs mt-1">{{ editForm.errors.name }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address / Location</label>
            <input v-model="editForm.location" type="text" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
            <div v-if="editForm.errors.location" class="text-red-500 text-xs mt-1">{{ editForm.errors.location }}</div>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showEditModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button form="editWarehouseForm" type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Update Warehouse</button>
        </div>
      </template>
    </Modal>

    <!-- Request Transfer Modal -->
    <Modal :show="showTransferModal" @close="showTransferModal = false" @save="showTransferModal = false">
      <template #title>Request Stock Transfer</template>
      <template #body>
        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4 relative">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Location</label>
              <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">Select Source...</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Central Distribution Hub</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">West Coast Storage</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">East Coast Fulfillment</a>
                  </template>
                </Dropdown>
            </div>
            <div class="absolute left-1/2 top-8 -translate-x-1/2 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 z-10">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Location</label>
              <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      <span class="truncate pr-2">Select Destination...</span>
                      <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">Central Distribution Hub</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">West Coast Storage</a>
                    <a href="#" @click.prevent="close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 transition-colors">East Coast Fulfillment</a>
                  </template>
                </Dropdown>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product SKU</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search product..." />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transfer Quantity</label>
            <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes / Instructions</label>
            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="2" placeholder="e.g. Handle with care, fragile items."></textarea>
          </div>
        </div>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showTransferModal = false" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button @click="showTransferModal = false" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm">Submit Request</button>
        </div>
      </template>
    </Modal>
  </AppLayout>
</template>
<script setup>
import Dropdown from '../Components/Dropdown.vue';
import { ref, watch } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    warehouses: {
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
    router.get('/warehouses', { search: value }, {
        preserveState: true,
        replace: true
    });
}, 300));

const showAddModal = ref(false);
const showEditModal = ref(false);
const showTransferModal = ref(false);
const activeTab = ref('locations');
const editingWarehouseId = ref(null);

const addForm = useForm({
    name: '',
    location: '',
    is_active: true
});

const editForm = useForm({
    name: '',
    location: '',
    capacity: 0,
    is_active: true
});

const openEditModal = (wh) => {
    editingWarehouseId.value = wh.id;
    editForm.name = wh.name;
    editForm.location = wh.location;
    editForm.is_active = wh.is_active;
    showEditModal.value = true;
};

const saveWarehouse = () => {
    addForm.post('/warehouses', {
        preserveScroll: true,
        onSuccess: () => {
            showAddModal.value = false;
            addForm.reset();
        }
    });
};

const updateWarehouse = () => {
    editForm.put(`/warehouses/${editingWarehouseId.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showEditModal.value = false;
            editForm.reset();
        }
    });
};

const deleteWarehouse = (id) => {
    if (confirm('Are you sure you want to delete this warehouse?')) {
        router.delete(`/warehouses/${id}`, { preserveScroll: true });
    }
};
</script>
