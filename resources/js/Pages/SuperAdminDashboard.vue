<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Platform Overview</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Super Admin View</p>
      </div>
      <button 
        @click="showCreateModal = true" 
        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-colors shrink-0"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Register Company & Invite Admin
      </button>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 transition-colors min-w-0">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Total MRR">Total MRR</p>
            <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">${{ parseFloat(metrics.total_mrr).toLocaleString() }}</div>
          </div>
        </div>
      </div>
      <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 transition-colors min-w-0">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 v5m-4 0h4"></path></svg>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Total Companies">Total Companies</p>
            <AutoFitText :value="metrics.total_companies" default-class="text-xl sm:text-2xl font-bold" custom-class="text-gray-900 dark:text-white" />
          </div>
        </div>
      </div>
      <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 transition-colors min-w-0">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Total Users">Total Users</p>
            <AutoFitText :value="metrics.total_users" default-class="text-xl sm:text-2xl font-bold" custom-class="text-gray-900 dark:text-white" />
          </div>
        </div>
      </div>
      <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 transition-colors min-w-0">
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          </div>
          <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate" title="Total Products Tracked">Products Tracked</p>
            <AutoFitText :value="metrics.total_products" default-class="text-xl sm:text-2xl font-bold" custom-class="text-gray-900 dark:text-white" />
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- Subscription Distribution -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Plan Distribution</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-400"></span> Free</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ charts?.plan_distribution?.free || 0 }}</span>
          </div>
          <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div class="bg-gray-400 h-2 rounded-full" :style="`width: ${(charts?.plan_distribution?.free / Math.max(1, metrics.total_companies)) * 100}%`"></div>
          </div>
          
          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Basic</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ charts?.plan_distribution?.basic || 0 }}</span>
          </div>
          <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div class="bg-blue-500 h-2 rounded-full" :style="`width: ${(charts?.plan_distribution?.basic / Math.max(1, metrics.total_companies)) * 100}%`"></div>
          </div>

          <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400 flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500"></span> Pro</span>
            <span class="font-bold text-gray-900 dark:text-white">{{ charts?.plan_distribution?.pro || 0 }}</span>
          </div>
          <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
            <div class="bg-purple-500 h-2 rounded-full" :style="`width: ${(charts?.plan_distribution?.pro / Math.max(1, metrics.total_companies)) * 100}%`"></div>
          </div>
        </div>
      </div>

      <!-- Growth Chart -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 lg:col-span-2 flex flex-col">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Platform Growth (Last 30 Days)</h3>
        <div class="flex-1 flex items-end justify-between gap-1 h-48 mt-auto border-b border-gray-100 dark:border-gray-800 pb-2">
           <div v-for="(val, idx) in charts?.growth?.series" :key="idx" class="w-full bg-primary-500/80 hover:bg-primary-600 rounded-t-sm transition-all group relative" :style="`height: ${(val / 100) * 100}%`">
              <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded pointer-events-none whitespace-nowrap z-10 transition-opacity">
                 {{ val }} - {{ charts?.growth?.labels[idx] }}
              </div>
           </div>
        </div>
      </div>
    </div>

    <!-- Recent Companies Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <div class="p-4 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recently Registered Companies</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold">ID</th>
              <th class="p-4 font-semibold">Company Name</th>
              <th class="p-4 font-semibold">Registered At</th>
              <th class="p-4 font-semibold text-right">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="company in recent_companies" :key="company.id">
              <td class="p-4 text-sm text-gray-500 dark:text-gray-400">#{{ company.id }}</td>
              <td class="p-4 font-medium text-gray-900 dark:text-white">{{ company.name }}</td>
              <td class="p-4 text-sm text-gray-500 dark:text-gray-400">{{ formatDate(company.created_at) }}</td>
              <td class="p-4 text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">
                  Active
                </span>
              </td>
            </tr>
            <tr v-if="recent_companies.length === 0">
                <td colspan="4" class="p-8 text-center text-gray-500 dark:text-gray-400">No companies found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create Company Modal -->
    <Modal :show="showCreateModal" :scrollable="false" @close="showCreateModal = false" @save="handleCreateCompany">
      <template #title>Register Company Account & Send SMTP Invite</template>
      <template #body>
        <div class="space-y-4">
          <div v-if="error" class="p-3 text-xs font-medium rounded-xl bg-red-50 text-red-600 border border-red-100">
             {{ error }}
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
            <input type="text" v-model="companyName" placeholder="e.g. Apex Tech Ltd" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Admin Name</label>
            <input type="text" v-model="adminName" placeholder="e.g. Rahman Hossain" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Admin Email</label>
            <input type="email" v-model="adminEmail" placeholder="admin@apextech.com" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm" />
          </div>
          <div class="p-3 bg-blue-50 dark:bg-blue-950/30 rounded-xl border border-blue-100 dark:border-blue-900 text-xs text-blue-700 dark:text-blue-300">
            An automated SMTP invitation email with login credentials and account access link will be dispatched instantly to the admin's email address.
          </div>
        </div>
      </template>
    </Modal>
  </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from '@/Components/Modal.vue';
import AutoFitText from '@/Components/AutoFitText.vue';

const props = defineProps({
    metrics: Object,
    charts: Object,
    recent_companies: Array
});

const showCreateModal = ref(false);
const error = ref(null);
const companyName = ref('');
const adminName = ref('');
const adminEmail = ref('');

const form = useForm({
    company_name: '',
    admin_name: '',
    admin_email: '',
});

const handleCreateCompany = () => {
    error.value = null;
    form.company_name = companyName.value;
    form.admin_name = adminName.value;
    form.admin_email = adminEmail.value;

    form.post('/platform/companies', {
        preserveScroll: true,
        onSuccess: () => {
            showCreateModal.value = false;
            companyName.value = '';
            adminName.value = '';
            adminEmail.value = '';
        },
        onError: (err) => {
            error.value = Object.values(err)[0];
        }
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
};
</script>
