<template>
  <div class="min-h-screen bg-gray-50 dark:bg-black transition-colors selection:bg-primary-500 selection:text-white flex flex-col justify-between">
    <!-- Topbar -->
    <header class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 shadow-sm sticky top-0 z-50 transition-colors">
      <div class="container mx-auto px-4 md:px-6 h-20 flex items-center justify-between">
        <div class="flex items-center">
          <BrandLogo size="lg" theme="dark" class="hidden dark:flex" />
          <BrandLogo size="lg" class="dark:hidden" />
        </div>
        
        <nav class="hidden md:flex items-center space-x-10">
          <router-link to="/" class="text-sm font-semibold transition-colors pb-1" :class="isRoute('/') ? 'text-gray-900 dark:text-white border-b-2 border-primary-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'">Home</router-link>
          <router-link to="/features" class="text-sm font-semibold transition-colors pb-1" :class="isRoute('/features') ? 'text-gray-900 dark:text-white border-b-2 border-primary-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'">Features</router-link>
          <router-link to="/pricing" class="text-sm font-semibold transition-colors pb-1" :class="isRoute('/pricing') ? 'text-gray-900 dark:text-white border-b-2 border-primary-600' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'">Pricing</router-link>
        </nav>
        
        <div class="flex items-center gap-2 md:gap-4">
          <ThemeToggle />
          <template v-if="isAuthenticated">
            <router-link :to="currentUserRole === 'super_admin' ? '/platform' : '/dashboard'" class="bg-primary-600 text-white px-4 md:px-5 py-2 md:py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
              Go to Dashboard
            </router-link>
          </template>
          <template v-else>
            <router-link to="/login" class="hidden md:flex text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 border border-transparent transition-colors">
              Login
            </router-link>
            <router-link to="/signup" class="bg-primary-600 text-white px-4 md:px-5 py-2 md:py-2.5 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
              Get Started
            </router-link>
          </template>
        </div>
      </div>
    </header>

    <!-- Main Page Content -->
    <div class="flex-grow">
      <router-view></router-view>
    </div>

    <!-- Footer Section -->
    <footer class="bg-white dark:bg-gray-950 border-t border-gray-100 dark:border-gray-800 transition-colors">
      <div class="container mx-auto px-4 md:px-6 py-12 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 md:gap-12 pb-12 border-b border-gray-100 dark:border-gray-900">
          <!-- Col 1: Brand Info -->
          <div class="lg:col-span-4 flex flex-col gap-4">
            <div class="flex items-center">
              <BrandLogo size="lg" theme="dark" class="hidden dark:flex" />
              <BrandLogo size="lg" class="dark:hidden" />
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-sm">
              StockSync is the ultimate inventory command center. Empowering modern businesses to manage stocks, coordinate suppliers, and streamline order fulfillments in real-time.
            </p>
            <!-- Social Media Icons -->
            <div class="flex items-center gap-4 mt-2">
              <a href="#" class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" aria-label="Twitter">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" aria-label="GitHub">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.579.688.481C19.137 20.162 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
              </a>
              <a href="#" class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" aria-label="LinkedIn">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
              </a>
            </div>
          </div>

          <!-- Col 2: Product Links -->
          <div class="lg:col-span-2 flex flex-col gap-3">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Product</h4>
            <ul class="flex flex-col gap-2">
              <li><router-link to="/features" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">Features</router-link></li>
              <li><router-link to="/pricing" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">Pricing</router-link></li>
              <li><a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">Integrations</a></li>
              <li><a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">Security</a></li>
            </ul>
          </div>

          <!-- Col 3: Resources Links -->
          <div class="lg:col-span-2 flex flex-col gap-3">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Resources</h4>
            <ul class="flex flex-col gap-2">
              <li><a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">Documentation</a></li>
              <li><a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">API Reference</a></li>
              <li><a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">Guides</a></li>
              <li><a href="#" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:translate-x-1 transition-all duration-200 inline-block">Help Center</a></li>
            </ul>
          </div>

          <!-- Col 4: Newsletter -->
          <div class="lg:col-span-4 flex flex-col gap-3">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider">Stay Updated</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Subscribe to our newsletter for the latest inventory management tips, product updates, and industry insights.
            </p>
            <form @submit.prevent class="flex flex-col sm:flex-row gap-2 mt-2">
              <input 
                type="email" 
                placeholder="Enter your email" 
                class="flex-1 px-4 py-2.5 rounded-lg text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 text-gray-900 dark:text-white transition-all"
                required
              />
              <button 
                type="submit" 
                class="bg-primary-600 hover:bg-primary-700 active:scale-95 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition-all flex items-center justify-center shrink-0"
              >
                Subscribe
              </button>
            </form>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-12 text-sm text-gray-500 dark:text-gray-400">
          <div>
            &copy; {{ new Date().getFullYear() }} StockSync. All rights reserved.
          </div>
          <div class="flex items-center gap-6">
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Cookies Settings</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router';
import BrandLogo from '../Components/BrandLogo.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';
import { useAppState } from '../Composables/useAppState';

const { isDark, isAuthenticated, currentUserRole } = useAppState();
const route = useRoute();
const isRoute = (path) => route.path === path;
</script>
