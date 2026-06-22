<template>
  <div class="py-16 md:py-24">
    <!-- Header -->
    <div class="container mx-auto px-4 md:px-6 text-center mb-16">
      <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">
        Simple pricing, built to <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">scale.</span>
      </h1>
      <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 max-w-3xl mx-auto">
        All plans start with a 14-day free trial. No credit card required. Cancel anytime.
      </p>

      <!-- Billing Toggle -->
      <div class="flex items-center justify-center gap-4 mt-10">
        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Monthly Billing</span>
        <button 
          @click="isAnnual = !isAnnual" 
          class="relative w-14 h-8 bg-primary-600 rounded-full transition-colors duration-300 focus:outline-none"
        >
          <span 
            class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition-transform duration-300 shadow-sm"
            :class="isAnnual ? 'translate-x-6' : 'translate-x-0'"
          ></span>
        </button>
        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-3">
          Annual Billing
          <span class="bg-green-100 dark:bg-green-950 text-green-700 dark:text-green-400 text-xs font-bold px-2 py-0.5 rounded-full">Save 20%</span>
        </span>
      </div>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="container mx-auto px-4 md:px-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 md:gap-8 max-w-6xl mb-16 md:mb-24">
      <!-- Tier 1: Starter -->
      <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-6 md:p-8 hover:shadow-xl transition-all flex flex-col justify-between">
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Starter</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">For small businesses starting their inventory journey.</p>
          <div class="flex items-baseline gap-1 mb-8">
            <span class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white">${{ isAnnual ? '24' : '29' }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400 font-semibold">/month</span>
          </div>
          <ul class="flex flex-col gap-4 text-sm text-gray-700 dark:text-gray-300">
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Up to 500 SKUs</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 2 User Seats</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 1 Warehouse Location</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Standard Reporting</li>
          </ul>
        </div>
        <router-link to="/signup" class="mt-8 block text-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-900 dark:text-white font-bold py-3.5 rounded-xl transition-colors">
          Start Free Trial
        </router-link>
      </div>

      <!-- Tier 2: Growth (Popular) -->
      <div class="relative bg-white dark:bg-gray-900 border-2 border-primary-600 rounded-3xl p-6 md:p-8 shadow-2xl hover:scale-[1.02] transition-all flex flex-col justify-between">
        <span class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary-600 text-white text-xs font-bold tracking-widest uppercase px-4 py-1.5 rounded-full shadow-md whitespace-nowrap">Most Popular</span>
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Growth</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Perfect for growing brands looking to scale automation.</p>
          <div class="flex items-baseline gap-1 mb-8">
            <span class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white">${{ isAnnual ? '64' : '79' }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400 font-semibold">/month</span>
          </div>
          <ul class="flex flex-col gap-4 text-sm text-gray-700 dark:text-gray-300">
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Up to 5,000 SKUs</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 5 User Seats</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 3 Warehouse Locations</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Intelligent Forecasting</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Omnichannel Sync</li>
          </ul>
        </div>
        <router-link to="/signup" class="mt-8 block text-center bg-primary-600 hover:bg-primary-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-primary-500/20 transition-all">
          Start Free Trial
        </router-link>
      </div>

      <!-- Tier 3: Enterprise -->
      <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-6 md:p-8 hover:shadow-xl transition-all flex flex-col justify-between">
        <div>
          <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Enterprise</h3>
          <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Designed for larger corporations requiring high performance.</p>
          <div class="flex items-baseline gap-1 mb-8">
            <span class="text-3xl md:text-5xl font-extrabold text-gray-900 dark:text-white">Custom</span>
          </div>
          <ul class="flex flex-col gap-4 text-sm text-gray-700 dark:text-gray-300">
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Unlimited SKUs</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Unlimited Seats & Locations</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Custom API & Webhooks</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> Dedicated Account Manager</li>
            <li class="flex items-center gap-3"><svg class="w-5 h-5 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg> 99.99% SLA Uptime</li>
          </ul>
        </div>
        <router-link to="/signup" class="mt-8 block text-center bg-gray-900 hover:bg-black dark:bg-gray-800 dark:hover:bg-gray-700 text-white font-bold py-3.5 rounded-xl transition-colors">
          Contact Sales
        </router-link>
      </div>
    </div>

    <!-- FAQ Section -->
    <div class="container mx-auto px-4 md:px-6 max-w-4xl mt-8 md:mt-12 pb-12 mb-12 border-b border-gray-100 dark:border-gray-800">
      <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-white mb-10 md:mb-12">Frequently Asked Questions</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Q1 -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-6 rounded-2xl">
          <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Can I change my plan later?</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Yes, you can upgrade, downgrade, or cancel your subscription at any time directly from your organization settings page.
          </p>
        </div>
        <!-- Q2 -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-6 rounded-2xl">
          <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">What happens when my trial ends?</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            We will notify you before your 14-day trial finishes. You can subscribe to any of the paid tiers or your account will transition to read-only status until you choose a plan.
          </p>
        </div>
        <!-- Q3 -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-6 rounded-2xl">
          <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Do you charge transaction fees?</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Absolutely not. We only charge a flat subscription fee. You keep 100% of the revenue you make from sales channels.
          </p>
        </div>
        <!-- Q4 -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-6 rounded-2xl">
          <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Is my data secure?</h4>
          <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
            Data security is our top priority. We use industry-standard bank-level AES-256 encryption for database contents and SSL transmissions.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const isAnnual = ref(true);
</script>
