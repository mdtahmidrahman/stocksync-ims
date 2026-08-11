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
      <button @click="activeTab = 'matrix'" :class="['px-4 py-3 text-sm font-medium border-b-2 transition-colors', activeTab === 'matrix' ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300']">Stock Matrix</button>
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
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 min-w-0">
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Total Locations">Total Locations</div>
          <AutoFitText :value="warehouses.length" custom-class="text-gray-900 dark:text-white mt-1" />
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 min-w-0">
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Average Capacity Used">Average Capacity Used</div>
          <AutoFitText :value="`${warehouses.length ? Math.round(warehouses.reduce((acc, w) => acc + w.capacity_used, 0) / warehouses.length) : 0}%`" custom-class="text-gray-900 dark:text-white mt-1" />
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 min-w-0">
          <div class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Items in Transit">Items in Transit</div>
          <AutoFitText :value="transfers.filter(t => t.status === 'in_transit').reduce((acc, t) => acc + t.quantity, 0)" custom-class="text-gray-900 dark:text-white mt-1" />
        </div>
      </div>

      <!-- Grid View (Scrollable) -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-6">
        <div v-for="wh in filteredWarehouses" :key="wh.id" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col transition-colors">
          <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-800">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div class="flex items-center gap-2">
              <button @click="openEditModal(wh)" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm font-medium mr-2">Edit</button>
              <button @click="confirmDeleteWarehouse(wh.id)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm font-medium">Delete</button>
            </div>
          </div>
          
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ wh.name }}</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-2">{{ wh.location }}</p>
          <p class="text-xs text-gray-500 dark:text-gray-400 mb-4 flex-1">Manager: <span class="font-medium text-gray-700 dark:text-gray-300">{{ wh.manager?.name || 'Unassigned' }}</span></p>
          
          <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-800">
            <div class="flex justify-between text-sm">
              <span class="text-gray-500 dark:text-gray-400">Status:</span>
              <span class="font-medium text-green-600 dark:text-green-400" v-if="wh.is_active">Active</span>
              <span class="font-medium text-red-600 dark:text-red-400" v-else>Inactive</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="text-gray-500 dark:text-gray-400">Total Items:</span>
              <span class="font-medium text-gray-900 dark:text-white">{{ wh.total_items }}</span>
            </div>
            
            <div class="pt-2">
              <div class="flex justify-between text-xs mb-1">
                <span class="font-medium text-gray-700 dark:text-gray-300">Capacity Used</span>
                <span class="font-medium text-gray-700 dark:text-gray-300">{{ wh.capacity_used }}%</span>
              </div>
              <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden">
                <div class="bg-primary-500 h-1.5 rounded-full transition-all duration-500" :style="{ width: `${wh.capacity_used}%` }"></div>
              </div>
            </div>

            <button @click="openViewStockModal(wh)" class="mt-3 w-full py-2 px-3 bg-gray-50 dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-primary-950/40 text-primary-600 dark:text-primary-400 border border-gray-200 dark:border-gray-700 hover:border-primary-300 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
              View Warehouse Stock ({{ wh.products?.length || 0 }} items)
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Stock Matrix View -->
    <div v-if="activeTab === 'matrix'" class="space-y-6">
      <!-- Filters Toolbar -->
      <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-100 dark:border-gray-800">
        <div class="relative w-full sm:w-72">
          <input v-model="matrixSearch" type="text" placeholder="Search product or SKU..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
          <select v-model="matrixWarehouseFilter" class="px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white text-sm focus:ring-primary-500">
            <option value="">All Warehouse Locations</option>
            <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
          </select>
        </div>
      </div>

      <!-- Inventory Matrix Table -->
      <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto max-h-[600px] overflow-y-auto" @scroll="handleMatrixScroll">
          <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 sticky top-0 z-10 backdrop-blur-md">
              <tr>
                <th class="p-4 font-semibold">Product Name</th>
                <th class="p-4 font-semibold">SKU</th>
                <th class="p-4 font-semibold">Warehouse Location</th>
                <th class="p-4 font-semibold text-center">In-Stock Quantity</th>
                <th class="p-4 font-semibold text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
              <tr v-for="item in displayedMatrixItems" :key="item.key" class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                <td class="p-4">
                  <div class="font-bold text-gray-900 dark:text-white">{{ item.product_name }}</div>
                  <div class="text-xs text-gray-400 mt-0.5">{{ item.category_name }}</div>
                </td>
                <td class="p-4 font-mono text-xs text-gray-600 dark:text-gray-400">{{ item.sku }}</td>
                <td class="p-4">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border border-blue-200/50 dark:border-blue-800/40">
                    {{ item.warehouse_name }}
                  </span>
                </td>
                <td class="p-4 text-center">
                  <span :class="['inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold', item.quantity > 5 ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40' : item.quantity > 0 ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/40' : 'bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 border border-red-200/50 dark:border-red-800/40']">
                    {{ item.quantity }} units
                  </span>
                </td>
                <td class="p-4 text-right">
                  <button @click="quickTransfer(item)" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                    Transfer Stock &rarr;
                  </button>
                </td>
              </tr>
              <tr v-if="filteredMatrixItems.length === 0">
                <td colspan="5" class="p-8 text-center text-gray-500 dark:text-gray-400">No warehouse stock matches your search criteria.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Scrollable Chunk Pagination Footer -->
        <div v-if="filteredMatrixItems.length > 0" class="p-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-800/30">
          <div class="text-xs text-gray-500 dark:text-gray-400">
            Showing <span class="font-bold text-gray-900 dark:text-white">{{ displayedMatrixItems.length }}</span> of <span class="font-bold text-gray-900 dark:text-white">{{ filteredMatrixItems.length }}</span> items
          </div>

          <div v-if="isMatrixLoadingMore" class="text-xs font-semibold text-primary-600 dark:text-primary-400 flex items-center gap-2">
            <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            Loading next 50 items...
          </div>
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
          <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full">{{ transfers.filter(t => t.status === 'requested').length }}</span>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
          <div v-for="transfer in transfers.filter(t => t.status === 'requested')" :key="transfer.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-bold text-gray-500 dark:text-gray-400">TRF-{{ String(transfer.id).padStart(4, '0') }}</span>
              <span class="text-xs text-gray-400">{{ new Date(transfer.created_at).toLocaleDateString() }}</span>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-2">{{ transfer.product?.name }} (x{{ transfer.quantity }})</h4>
            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-md mb-3">
              <span class="truncate">{{ transfer.source_warehouse?.name }}</span>
              <svg class="w-3 h-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              <span class="truncate">{{ transfer.destination_warehouse?.name }}</span>
            </div>
            <div class="flex justify-between items-center">
              <div class="flex -space-x-2">
                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold border-2 border-white dark:border-gray-800" :title="transfer.user?.name">
                  {{ transfer.user?.name?.substring(0, 2).toUpperCase() || 'U' }}
                </div>
              </div>
              <button @click="updateTransferStatus(transfer.id, 'in_transit')" class="text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-800">Dispatch</button>
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
          <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full">{{ transfers.filter(t => t.status === 'in_transit').length }}</span>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
          <div v-for="transfer in transfers.filter(t => t.status === 'in_transit')" :key="transfer.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 transition-colors">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-bold text-gray-500 dark:text-gray-400">TRF-{{ String(transfer.id).padStart(4, '0') }}</span>
              <span class="text-xs text-gray-400">{{ new Date(transfer.created_at).toLocaleDateString() }}</span>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-2">{{ transfer.product?.name }} (x{{ transfer.quantity }})</h4>
            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-md mb-3">
              <span class="truncate">{{ transfer.source_warehouse?.name }}</span>
              <svg class="w-3 h-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              <span class="truncate">{{ transfer.destination_warehouse?.name }}</span>
            </div>
            <div class="flex justify-between items-center">
              <div class="text-xs text-blue-600 dark:text-blue-400 font-medium flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                In Transit
              </div>
              <button @click="updateTransferStatus(transfer.id, 'received')" class="text-xs font-semibold text-green-600 dark:text-green-400 hover:text-green-800">Receive</button>
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
          <span class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full">{{ transfers.filter(t => t.status === 'received').length }}</span>
        </div>
        <div class="p-4 space-y-4 overflow-y-auto flex-1 custom-scrollbar">
          <div v-for="transfer in transfers.filter(t => t.status === 'received')" :key="transfer.id" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 opacity-60">
            <div class="flex justify-between items-start mb-2">
              <span class="text-xs font-bold text-gray-500 dark:text-gray-400">TRF-{{ String(transfer.id).padStart(4, '0') }}</span>
              <span class="text-xs text-gray-400">{{ new Date(transfer.created_at).toLocaleDateString() }}</span>
            </div>
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-2">{{ transfer.product?.name }} (x{{ transfer.quantity }})</h4>
            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900 p-2 rounded-md mb-3">
              <span class="truncate">{{ transfer.source_warehouse?.name }}</span>
              <svg class="w-3 h-3 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              <span class="truncate">{{ transfer.destination_warehouse?.name }}</span>
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
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Manager</label>
            <select v-model="addForm.manager_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option :value="null">Unassigned</option>
                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maximum Capacity (Items)</label>
            <input v-model="addForm.capacity" type="number" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="1000" />
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showAddModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button @click="saveWarehouse" type="button" :disabled="addForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Save</button>
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
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Manager</label>
            <select v-model="editForm.manager_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option :value="null">Unassigned</option>
                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maximum Capacity (Items)</label>
            <input v-model="editForm.capacity" type="number" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showEditModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button @click="updateWarehouse" type="button" :disabled="editForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Update Warehouse</button>
        </div>
      </template>
    </Modal>

    <!-- Request Transfer Modal -->
    <Modal :show="showTransferModal" @close="showTransferModal = false" @save="showTransferModal = false">
      <template #title>Request Stock Transfer</template>
      <template #body>
        <form @submit.prevent="submitTransfer" id="transferForm" class="space-y-4">
          <div class="grid grid-cols-2 gap-4 relative">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Location</label>
              <select v-model="transferForm.source_warehouse_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="" disabled>Select Source...</option>
                <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
              </select>
              <div v-if="transferForm.errors.source_warehouse_id" class="text-red-500 text-xs mt-1">{{ transferForm.errors.source_warehouse_id }}</div>
            </div>
            <div class="absolute left-1/2 top-8 -translate-x-1/2 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 z-10 pointer-events-none">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Location</label>
              <select v-model="transferForm.destination_warehouse_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="" disabled>Select Destination...</option>
                <option v-for="wh in warehouses" :key="wh.id" :value="wh.id">{{ wh.name }}</option>
              </select>
              <div v-if="transferForm.errors.destination_warehouse_id" class="text-red-500 text-xs mt-1">{{ transferForm.errors.destination_warehouse_id }}</div>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product SKU</label>
            <select v-model="transferForm.product_id" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                <option value="" disabled>Select Product...</option>
                <option v-for="product in products" :key="product.id" :value="product.id">{{ product.name }} ({{ product.sku }})</option>
            </select>
            <div v-if="transferForm.errors.product_id" class="text-red-500 text-xs mt-1">{{ transferForm.errors.product_id }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Transfer Quantity</label>
            <input v-model="transferForm.quantity" type="number" required min="1" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="1" />
            <div v-if="transferForm.errors.quantity" class="text-red-500 text-xs mt-1">{{ transferForm.errors.quantity }}</div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes / Instructions</label>
            <textarea v-model="transferForm.notes" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="2" placeholder="e.g. Handle with care, fragile items."></textarea>
          </div>
        </form>
      </template>
      <template #footer>
        <div class="flex justify-end gap-2 w-full">
          <button @click="showTransferModal = false" type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors">Cancel</button>
          <button @click="submitTransfer" type="button" :disabled="transferForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm disabled:opacity-50">Submit Request</button>
        </div>
      </template>
    </Modal>
    <!-- View Warehouse Inventory Breakdown Modal -->
    <Modal :show="showViewStockModal" @close="showViewStockModal = false">
      <template #title>
        Stock Breakdown: {{ selectedWarehouseForStock?.name }}
      </template>
      <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-1">
        <div class="relative">
          <input v-model="stockModalSearch" type="text" placeholder="Search items in this warehouse..." class="w-full pl-9 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-xl text-sm bg-white dark:bg-black text-gray-900 dark:text-white" />
          <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <table class="w-full text-left text-sm">
          <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
            <tr>
              <th class="p-3 font-semibold">Product</th>
              <th class="p-3 font-semibold">SKU</th>
              <th class="p-3 font-semibold text-center">In Stock</th>
              <th class="p-3 font-semibold text-right">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            <tr v-for="p in filteredWarehouseProducts" :key="p.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
              <td class="p-3 font-semibold text-gray-900 dark:text-white">
                {{ p.name }}
                <div class="text-[10px] text-gray-400 font-normal" v-if="p.category?.name">{{ p.category.name }}</div>
              </td>
              <td class="p-3 text-xs text-gray-400 font-mono">{{ p.sku }}</td>
              <td class="p-3 text-center">
                <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40">
                  {{ p.pivot?.quantity || 0 }} units
                </span>
              </td>
              <td class="p-3 text-right">
                <button @click="quickTransferFromWarehouse(selectedWarehouseForStock, p)" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                  Transfer &rarr;
                </button>
              </td>
            </tr>
            <tr v-if="!filteredWarehouseProducts || filteredWarehouseProducts.length === 0">
              <td colspan="4" class="p-6 text-center text-gray-400 italic">No matching products recorded in this warehouse.</td>
            </tr>
          </tbody>
        </table>
      </div>
      <template #footer>
        <div class="flex justify-end w-full">
          <button @click="showViewStockModal = false" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold text-xs rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
            Close
          </button>
        </div>
      </template>
    </Modal>

    <!-- Delete Confirmation Modal -->
    <ConfirmDeleteModal 
      :show="showDeleteModal" 
      message="Are you sure you want to delete this warehouse? All inventory associated with this location may be affected."
      @close="showDeleteModal = false; itemToDelete = null"
      @confirm="executeDeleteWarehouse"
    />
  </AppLayout>
</template>
<script setup>
import Dropdown from '../Components/Dropdown.vue';
import { ref, watch } from 'vue';
import { router, useForm, Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';
import ConfirmDeleteModal from '../Components/ConfirmDeleteModal.vue';
import AutoFitText from '../Components/AutoFitText.vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    warehouses: {
        type: Array,
        default: () => []
    },
    users: {
        type: Array,
        default: () => []
    },
    products: {
        type: Array,
        default: () => []
    },
    transfers: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    }
});

import { computed } from 'vue';

const search = ref(props.filters.search || '');

// Using computed for client-side filtering since we load all warehouses
const filteredWarehouses = computed(() => {
    if (!search.value) return props.warehouses;
    const lowerSearch = search.value.toLowerCase();
    return props.warehouses.filter(wh => 
        wh.name.toLowerCase().includes(lowerSearch) || 
        wh.location.toLowerCase().includes(lowerSearch)
    );
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const showTransferModal = ref(false);
const activeTab = ref('locations');
const editingWarehouseId = ref(null);

// Stock Matrix & View Inventory State with 50-by-50 Chunk Pagination
const matrixSearch = ref('');
const matrixWarehouseFilter = ref('');
const matrixDisplayLimit = ref(50);
const isMatrixLoadingMore = ref(false);

const showViewStockModal = ref(false);
const selectedWarehouseForStock = ref(null);
const stockModalSearch = ref('');

watch([matrixSearch, matrixWarehouseFilter], () => {
    matrixDisplayLimit.value = 50;
});

const openViewStockModal = (wh) => {
    selectedWarehouseForStock.value = wh;
    stockModalSearch.value = '';
    showViewStockModal.value = true;
};

const filteredWarehouseProducts = computed(() => {
    if (!selectedWarehouseForStock.value || !selectedWarehouseForStock.value.products) return [];
    const searchVal = stockModalSearch.value.toLowerCase().trim();
    if (!searchVal) return selectedWarehouseForStock.value.products;
    return selectedWarehouseForStock.value.products.filter(p => 
        (p.name && p.name.toLowerCase().includes(searchVal)) ||
        (p.sku && p.sku.toLowerCase().includes(searchVal)) ||
        (p.category && p.category.name && p.category.name.toLowerCase().includes(searchVal))
    );
});

const filteredMatrixItems = computed(() => {
    const list = [];
    props.warehouses.forEach(wh => {
        if (matrixWarehouseFilter.value && wh.id != matrixWarehouseFilter.value) return;
        if (wh.products) {
            wh.products.forEach(p => {
                list.push({
                    key: `${wh.id}-${p.id}`,
                    warehouse_id: wh.id,
                    warehouse_name: wh.name,
                    product_id: p.id,
                    product_name: p.name,
                    sku: p.sku || 'N/A',
                    category_name: p.category?.name || 'Uncategorized',
                    quantity: p.pivot?.quantity || 0
                });
            });
        }
    });

    const searchVal = matrixSearch.value.toLowerCase().trim();
    if (!searchVal) return list;

    return list.filter(item => 
        item.product_name.toLowerCase().includes(searchVal) ||
        item.sku.toLowerCase().includes(searchVal) ||
        item.warehouse_name.toLowerCase().includes(searchVal) ||
        item.category_name.toLowerCase().includes(searchVal)
    );
});

const displayedMatrixItems = computed(() => {
    return filteredMatrixItems.value.slice(0, matrixDisplayLimit.value);
});

const hasMoreMatrixItems = computed(() => {
    return matrixDisplayLimit.value < filteredMatrixItems.value.length;
});

const loadNextMatrixChunk = () => {
    if (hasMoreMatrixItems.value && !isMatrixLoadingMore.value) {
        isMatrixLoadingMore.value = true;
        setTimeout(() => {
            matrixDisplayLimit.value += 50;
            isMatrixLoadingMore.value = false;
        }, 250);
    }
};

const handleMatrixScroll = (e) => {
    const { scrollTop, clientHeight, scrollHeight } = e.target;
    if (scrollTop + clientHeight >= scrollHeight - 120) {
        loadNextMatrixChunk();
    }
};

const quickTransfer = (item) => {
    transferForm.source_warehouse_id = item.warehouse_id;
    transferForm.product_id = item.product_id;
    transferForm.quantity = 1;
    showTransferModal.value = true;
};

const quickTransferFromWarehouse = (wh, product) => {
    showViewStockModal.value = false;
    transferForm.source_warehouse_id = wh.id;
    transferForm.product_id = product.id;
    transferForm.quantity = 1;
    showTransferModal.value = true;
};

const addForm = useForm({
    name: '',
    location: '',
    manager_id: null,
    capacity: 1000,
    is_active: true
});

const editForm = useForm({
    name: '',
    location: '',
    manager_id: null,
    capacity: 0,
    is_active: true
});

const openEditModal = (wh) => {
    editingWarehouseId.value = wh.id;
    editForm.name = wh.name;
    editForm.location = wh.location;
    editForm.manager_id = wh.manager_id;
    editForm.capacity = wh.capacity;
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

const showDeleteModal = ref(false);
const itemToDelete = ref(null);

const confirmDeleteWarehouse = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const executeDeleteWarehouse = () => {
    if (itemToDelete.value) {
        router.delete(`/warehouses/${itemToDelete.value}`, { 
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                itemToDelete.value = null;
            }
        });
    }
};

const transferForm = useForm({
    source_warehouse_id: '',
    destination_warehouse_id: '',
    product_id: '',
    quantity: 1,
    notes: ''
});

const submitTransfer = () => {
    transferForm.post('/warehouses/transfers', {
        preserveScroll: true,
        onSuccess: () => {
            showTransferModal.value = false;
            transferForm.reset();
        }
    });
};

const updateTransferStatus = (transferId, status) => {
    router.put(`/warehouses/transfers/${transferId}/status`, { status }, {
        preserveScroll: true
    });
};
</script>
