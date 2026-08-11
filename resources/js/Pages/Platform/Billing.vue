<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Platform Billing & Revenue</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitor active paid subscriptions and platform MRR.</p>
      </div>
    </div>

    <!-- Revenue KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
      <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-xl p-6 text-white shadow-sm flex items-center justify-between">
         <div>
            <p class="text-emerald-100 text-sm font-medium mb-1">Total Monthly Recurring Revenue (MRR)</p>
            <h2 class="text-3xl md:text-4xl font-bold">${{ parseFloat(total_mrr).toLocaleString() }}</h2>
         </div>
         <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
         </div>
      </div>
      <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-xl p-6 text-white shadow-sm flex items-center justify-between">
         <div>
            <p class="text-primary-100 text-sm font-medium mb-1">Active Paid Subscriptions</p>
            <h2 class="text-3xl md:text-4xl font-bold">{{ active_paid }}</h2>
         </div>
         <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
         </div>
      </div>
    </div>

    <!-- Active Subscriptions Table -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <div class="p-4 border-b border-gray-100 dark:border-gray-800">
         <h3 class="font-bold text-gray-900 dark:text-white">Active Paid Tenants</h3>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold">Company Name</th>
              <th class="p-4 font-semibold">Plan Tier</th>
              <th class="p-4 font-semibold">MRR</th>
              <th class="p-4 font-semibold">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="company in companies.data" :key="company.id">
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white">{{ company.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Joined {{ formatDate(company.created_at) }}</div>
              </td>
              <td class="p-4">
                 <span :class="[
                   'px-2 py-1 text-xs font-semibold rounded-full uppercase',
                   company.subscription_tier === 'pro' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-400'
                 ]">{{ company.subscription_tier }}</span>
              </td>
              <td class="p-4 text-sm text-gray-900 dark:text-gray-300 font-medium">${{ parseFloat(company.mrr).toFixed(2) }}</td>
              <td class="p-4">
                <span :class="[
                   'px-2 py-1 text-xs font-semibold rounded-full capitalize',
                   company.subscription_status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-400'
                 ]">{{ company.subscription_status }}</span>
              </td>
            </tr>
            <tr v-if="companies.data.length === 0">
                <td colspan="4" class="p-8 text-center text-gray-500 dark:text-gray-400">No active paid subscriptions found.</td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div v-if="companies.links && companies.links.length > 3" class="p-4 border-t border-gray-100 dark:border-gray-800 flex justify-center">
         <div class="flex flex-wrap gap-1">
            <Link 
              v-for="(link, i) in companies.links" :key="i"
              :href="link.url || '#'" 
              class="px-3 py-1 rounded border text-sm"
              :class="[
                  link.active ? 'bg-primary-600 text-white border-primary-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700',
                  !link.url ? 'opacity-50 cursor-not-allowed' : ''
              ]"
              v-html="link.label"
            />
         </div>
      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    companies: Object,
    total_mrr: [Number, String],
    active_paid: Number
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    });
};
</script>
