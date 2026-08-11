<template>
  <AppLayout>
  <div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white">Billing & Subscription</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your active plan and payment methods.</p>
      </div>
    </div>

    <!-- Current Plan Banner -->
    <div class="bg-gradient-to-r from-primary-900 to-slate-900 rounded-xl p-6 text-white shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 mb-8">
      <div>
        <h3 class="text-lg font-bold text-white mb-2">Current Plan: <span class="capitalize text-primary-200">{{ currentPlan }}</span></h3>
        <p class="text-primary-100 text-sm">Your subscription is currently <strong>active</strong>. You have full access to {{ currentPlan }} features.</p>
      </div>
      <div class="text-right">
         <div class="text-3xl font-bold">৳{{ parseFloat($page.props.auth.company.mrr || 0).toLocaleString() }}<span class="text-sm font-normal text-primary-200"> / month</span></div>
      </div>
    </div>

    <!-- Upgrade / Change Plan Card -->
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col sm:flex-row items-center justify-between gap-6 transition-colors">
      <div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Looking for more features?</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Upgrade your plan to unlock advanced inventory tools, B2B portals, and priority support.</p>
      </div>
      <Link href="/pricing" class="px-6 py-2.5 bg-primary-600 text-white rounded-xl text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm shrink-0">
        View Pricing & Plans
      </Link>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const page = usePage();
const currentPlan = computed(() => page.props.auth.company?.subscription_tier || 'free');
</script>
