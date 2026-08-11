<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Tenants & Companies</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage all subscribed companies across the platform.</p>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-gray-50 dark:bg-black/50 border-b border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
              <th class="p-4 font-semibold">Company Name</th>
              <th class="p-4 font-semibold">Users</th>
              <th class="p-4 font-semibold">Plan Tier</th>
              <th class="p-4 font-semibold">MRR</th>
              <th class="p-4 font-semibold">Status</th>
              <th class="p-4 font-semibold text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" v-for="company in companies.data" :key="company.id">
              <td class="p-4">
                <div class="font-medium text-gray-900 dark:text-white">{{ company.name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Created {{ formatDate(company.created_at) }}</div>
              </td>
              <td class="p-4 text-sm text-gray-900 dark:text-gray-300 font-medium">{{ company.users_count || 0 }}</td>
              <td class="p-4">
                <select @change="updateTier(company, $event.target.value)" class="px-2 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                  <option value="free" :selected="company.subscription_tier === 'free'">Free</option>
                  <option value="basic" :selected="company.subscription_tier === 'basic'">Basic</option>
                  <option value="pro" :selected="company.subscription_tier === 'pro'">Pro</option>
                </select>
              </td>
              <td class="p-4 text-sm text-gray-900 dark:text-gray-300 font-medium">${{ parseFloat(company.mrr).toFixed(2) }}</td>
              <td class="p-4">
                <select @change="updateStatus(company, $event.target.value)" class="px-2 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800" :class="{
                  'text-green-600': company.subscription_status === 'active',
                  'text-red-600': company.subscription_status === 'past_due' || company.subscription_status === 'cancelled',
                  'text-blue-600': company.subscription_status === 'trialing'
                }">
                  <option value="active" :selected="company.subscription_status === 'active'">Active</option>
                  <option value="trialing" :selected="company.subscription_status === 'trialing'">Trialing</option>
                  <option value="past_due" :selected="company.subscription_status === 'past_due'">Past Due</option>
                  <option value="cancelled" :selected="company.subscription_status === 'cancelled'">Cancelled</option>
                </select>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a 
                  v-if="company.allow_support_impersonation"
                  :href="`/platform/tenants/${company.id}/impersonate`"
                  class="px-3 py-1.5 text-xs font-medium rounded transition-colors bg-primary-100 text-primary-700 hover:bg-primary-200 dark:bg-primary-900/30 dark:text-primary-400 inline-block text-center"
                >
                  Impersonate Admin
                </a>
                <button 
                  v-else
                  disabled
                  class="px-3 py-1.5 text-xs font-medium rounded transition-colors bg-gray-100 text-gray-400 cursor-not-allowed dark:bg-gray-800 dark:text-gray-600 inline-block text-center"
                  title="Company has not allowed support impersonation"
                >
                  Impersonate Admin
                </button>
              </td>
            </tr>
            <tr v-if="companies.data.length === 0">
                <td colspan="6" class="p-8 text-center text-gray-500 dark:text-gray-400">No tenants found.</td>
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
import { router, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    companies: Object
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric'
    });
};

const updateTier = (company, newTier) => {
    let mrr = 0;
    if (newTier === 'basic') mrr = 49.00;
    if (newTier === 'pro') mrr = 99.00;
    
    router.put(`/platform/tenants/${company.id}/tier`, {
        tier: newTier,
        mrr: mrr
    }, { preserveScroll: true });
};

const updateStatus = (company, newStatus) => {
    router.put(`/platform/tenants/${company.id}/status`, {
        status: newStatus
    }, { preserveScroll: true });
};
</script>
