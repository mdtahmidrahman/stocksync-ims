<template>
  <div class="min-h-screen flex bg-gray-50 dark:bg-black transition-colors">
    <!-- Form Side -->
    <div class="flex-1 flex flex-col justify-center py-10 px-4 sm:px-6 lg:flex-none lg:px-16 xl:px-20 overflow-y-auto">
      <div class="mx-auto w-full max-w-md lg:max-w-sm relative pb-10">
        <div class="absolute top-0 right-0 -mt-2">
            <ThemeToggle />
        </div>
        
        <div>
          <BrandLogo size="xl" theme="dark" class="hidden dark:flex" />
          <BrandLogo size="xl" class="dark:hidden" />
          <!-- Demo Role Buttons -->
          <div class="pt-6">
            <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-4">Demo: Quick Login as Role</p>
            <div class="grid grid-cols-3 gap-2">
              <button type="button" @click="demoLogin('admin')" class="px-3 py-2 text-xs font-semibold rounded-lg bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 border border-primary-200 dark:border-primary-800 hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors">Admin</button>
              <button type="button" @click="demoLogin('manager')" class="px-3 py-2 text-xs font-semibold rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">Manager</button>
              <button type="button" @click="demoLogin('staff')" class="px-3 py-2 text-xs font-semibold rounded-lg bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-800 hover:bg-green-100 dark:hover:bg-green-900/40 transition-colors">Staff</button>
            </div>
          </div>
          <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Create your account</h2>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Already have an account?
            <router-link to="/login" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
              Sign in
            </router-link>
          </p>
        </div>

        <div class="mt-6">
          <!-- Social Logins -->
          <div class="space-y-3">
            <button class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 dark:border-gray-800 rounded-xl shadow-sm bg-white dark:bg-gray-900 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
              <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                  <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                  <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                  <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
              </svg>
              Sign up with Google
            </button>
          </div>

          <div class="mt-6 relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-gray-50 dark:bg-black text-gray-500 dark:text-gray-400">Or register with email</span>
            </div>
          </div>

          <!-- Signup Form -->
          <div class="mt-6">
            <form @submit.prevent="$router.push('/dashboard')" class="space-y-4">
              <!-- Name & Phone -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="name" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"> Full Name </label>
                    <div class="relative rounded-xl shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                      </div>
                      <input id="name" name="name" type="text" required class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-all" placeholder="John Doe" />
                    </div>
                  </div>
                  <div>
                    <label for="phone" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"> Phone Number </label>
                    <div class="relative rounded-xl shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                      </div>
                      <input id="phone" name="phone" type="text" class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-all" placeholder="+880 1*********" />
                    </div>
                  </div>
              </div>

              <!-- Company & Industry -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label for="company" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"> Company Name </label>
                    <div class="relative rounded-xl shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                      </div>
                      <input id="company" name="company" type="text" required class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-all" placeholder="Demo Company" />
                    </div>
                  </div>
                  <div>
                    <label for="industry" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"> Industry </label>
                    <div class="relative rounded-xl shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                      </div>
                      <input type="hidden" name="industry" :value="selectedIndustry">
                      <Dropdown align="left" width="full" fullWidth>
                        <template #trigger>
                          <button type="button" class="flex justify-between items-center w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-all text-left min-h-[38px]">
                            <span :class="!selectedIndustry ? 'text-gray-500' : ''">{{ selectedIndustry || 'Select...' }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                          </button>
                        </template>
                        <template #content="{ close }">
                          <a href="#" v-for="ind in ['Retail & E-commerce', 'Manufacturing', 'Logistics & Supply', 'Other']" :key="ind" @click.prevent="selectedIndustry = ind; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors" :class="selectedIndustry === ind ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">
                            {{ ind }}
                          </a>
                        </template>
                      </Dropdown>
                    </div>
                  </div>
              </div>

              <!-- Email & Password -->
              <div>
                <label for="email" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"> Work Email </label>
                <div class="relative rounded-xl shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                  </div>
                  <input id="email" name="email" type="email" required class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-all" placeholder="you@company.com" />
                </div>
              </div>

              <div>
                <label for="password" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1"> Password </label>
                <div class="relative rounded-xl shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                  </div>
                  <input id="password" name="password" type="password" required class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-all" placeholder="••••••••" />
                </div>
              </div>

              <div class="pt-2">
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-xl shadow-md shadow-primary-500/20 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                  Create Account
                </button>
                <p class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400">
                  By registering, you agree to our <a href="#" class="font-medium text-primary-600 hover:text-primary-500">Terms of Service</a> and <a href="#" class="font-medium text-primary-600 hover:text-primary-500">Privacy Policy</a>.
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Right Panel: Abstract Gradient & Glassmorphism -->
    <div class="hidden lg:block relative w-0 flex-1 bg-gradient-to-tr from-black via-gray-900 to-primary-900 overflow-hidden">
      <!-- Decorative background blobs -->
      <div class="absolute top-0 left-0 w-full h-full opacity-40">
        <div class="absolute top-20 right-20 w-72 h-72 rounded-full bg-primary-500 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-96 h-96 rounded-full bg-blue-600 blur-3xl"></div>
      </div>

      <div class="absolute inset-0 flex items-center justify-center p-12 z-10">
         <!-- Glassmorphic Card -->
         <div class="backdrop-blur-xl bg-white/5 dark:bg-white/5 border border-white/10 p-10 rounded-3xl shadow-2xl max-w-md w-full text-white relative overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Join StockSync</h3>
                    <p class="text-primary-200 text-sm">Transform your inventory management.</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="mt-1 bg-white/10 rounded-full p-1 border border-white/20">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white">Infinite Scaling</h4>
                        <p class="text-sm text-gray-300 mt-1">Track up to 10,000 products effortlessly with our high-performance grid.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="mt-1 bg-white/10 rounded-full p-1 border border-white/20">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white">Team Collaboration</h4>
                        <p class="text-sm text-gray-300 mt-1">Invite unlimited team members with granular role-based access control.</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-4">
                    <div class="mt-1 bg-white/10 rounded-full p-1 border border-white/20">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white">Automated Alerts</h4>
                        <p class="text-sm text-gray-300 mt-1">Set par levels and receive real-time notifications before stock runs out.</p>
                    </div>
                </div>
            </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import BrandLogo from '../Components/BrandLogo.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';

const selectedIndustry = ref('');
</script>
