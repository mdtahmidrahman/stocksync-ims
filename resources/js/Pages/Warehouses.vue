<template>
  <div>
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
      <div v-for="(wh, i) in warehouses" :key="i" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col transition-colors">
        <div class="flex justify-between items-start mb-4">
          <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-blue-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
          </div>
          <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
          </button>
        </div>
        
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ wh.name }}</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-4 flex-1">{{ wh.address }}</p>
        
        <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-800">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Manager:</span>
            <span class="font-medium text-gray-900 dark:text-white">{{ wh.manager }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Total Items:</span>
            <span class="font-medium text-gray-900 dark:text-white">{{ wh.items.toLocaleString() }}</span>
          </div>
          
          <div class="pt-2">
            <div class="flex justify-between text-xs mb-1">
              <span class="font-medium text-gray-700 dark:text-gray-300">Capacity Used</span>
              <span class="font-medium text-gray-700 dark:text-gray-300">{{ wh.capacity }}%</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
              <div class="bg-primary-500 h-1.5 rounded-full" :style="{ width: wh.capacity + '%' }"></div>
            </div>
          </div>
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
    <Modal :show="showAddModal" @close="showAddModal = false" @save="showAddModal = false">
      <template #title>Add New Warehouse</template>
      <template #body>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Warehouse Name</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="e.g. Northeast Distribution Center" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address / Location</label>
            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="123 Industrial Parkway, Suite A" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned Manager</label>
              <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm appearance-none">
                <option disabled selected>Select Manager...</option>
                <option>Michael Scott</option>
                <option>Sarah Jenkins</option>
                <option>David Miller</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Capacity (Items)</label>
              <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="50000" />
            </div>
          </div>
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
              <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm appearance-none">
                <option disabled selected>Select Source...</option>
                <option>Central Distribution Hub</option>
                <option>West Coast Storage</option>
                <option>East Coast Fulfillment</option>
              </select>
            </div>
            <div class="absolute left-1/2 top-8 -translate-x-1/2 w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex items-center justify-center text-gray-500 z-10">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Location</label>
              <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm appearance-none">
                <option disabled selected>Select Destination...</option>
                <option>Central Distribution Hub</option>
                <option>West Coast Storage</option>
                <option>East Coast Fulfillment</option>
              </select>
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
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Modal from '../Components/Modal.vue';

const showAddModal = ref(false);
const showTransferModal = ref(false);
const activeTab = ref('locations');

const warehouses = [
    { name: 'Central Distribution Hub', address: '1200 Logistics Blvd, Chicago, IL 60607', manager: 'Michael Scott', items: 45200, capacity: 82 },
    { name: 'West Coast Storage', address: '450 Industrial Way, San Jose, CA 95112', manager: 'Sarah Jenkins', items: 18500, capacity: 45 },
    { name: 'East Coast Fulfillment', address: '780 Port Road, Newark, NJ 07102', manager: 'David Miller', items: 28900, capacity: 68 }
];
</script>
