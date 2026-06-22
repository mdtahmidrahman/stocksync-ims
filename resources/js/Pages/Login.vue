<template>
  <div class="min-h-screen flex bg-gray-50 dark:bg-black transition-colors">
    <!-- Form Side -->
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
      <div class="mx-auto w-full max-w-sm lg:w-96 relative">
        <div class="absolute top-0 right-0 -mt-8">
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
          <h2 class="mt-8 text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Welcome back</h2>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Don't have an account?
            <router-link to="/signup" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
              Create an account
            </router-link>
          </p>
        </div>
              
        <div class="mt-8">
          <!-- Social Logins -->
          <div class="space-y-3">
            <button class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 dark:border-gray-800 rounded-xl shadow-sm bg-white dark:bg-gray-900 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
              <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                  <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                  <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                  <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
              </svg>
              Sign in with Google
            </button>
          </div>

          <div class="mt-6 relative">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-gray-200 dark:border-gray-800"></div>
            </div>
            <div class="relative flex justify-center text-sm">
              <span class="px-2 bg-gray-50 dark:bg-black text-gray-500 dark:text-gray-400">Or continue with email</span>
            </div>
          </div>

          <!-- Login Form -->
          <div class="mt-6">
            <form @submit.prevent="$router.push('/dashboard')" class="space-y-5">
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Email address </label>
                <div class="relative rounded-xl shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                  </div>
                  <input id="email" name="email" type="email" required class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all" placeholder="you@company.com" />
                </div>
              </div>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Password </label>
                <div class="relative rounded-xl shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                  </div>
                  <input id="password" name="password" type="password" required class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all" placeholder="••••••••" />
                </div>
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-800 rounded bg-white dark:bg-gray-900" />
                  <label for="remember-me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300"> Remember me </label>
                </div>

                <div class="text-sm">
                  <a href="#" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors"> Forgot password? </a>
                </div>
              </div>

              <div>
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md shadow-primary-500/20 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                  Sign in
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
    
    <!-- Right Panel: Abstract Gradient & Glassmorphism -->
    <div class="hidden lg:block relative w-0 flex-1 bg-gradient-to-br from-primary-900 via-primary-800 to-black overflow-hidden">
      <!-- Decorative background blobs -->
      <div class="absolute top-0 left-0 w-full h-full opacity-30">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary-500 blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-blue-500 blur-3xl"></div>
      </div>

      <div class="absolute inset-0 flex items-center justify-center p-12 z-10">
         <!-- Glassmorphic Card -->
         <div class="backdrop-blur-xl bg-white/10 dark:bg-black/20 border border-white/20 dark:border-white/10 p-10 rounded-3xl shadow-2xl max-w-lg w-full text-white relative overflow-hidden">
             <!-- Top decorative accent -->
             <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-400 to-blue-400"></div>
             
             <h2 class="text-3xl font-bold mb-4 leading-tight">Sync across all your warehouses.</h2>
             <p class="text-primary-100 text-lg mb-8 leading-relaxed">
               Get a unified view of your inventory in real-time, no matter how many locations you manage.
             </p>
             
             <div class="flex items-center gap-6 p-4 rounded-2xl bg-white/5 border border-white/10">
                 <div>
                     <div class="text-sm text-primary-200 mb-1">System Uptime</div>
                     <div class="text-2xl font-bold text-white">99.99%</div>
                 </div>
                 <div class="w-px h-10 bg-white/20"></div>
                 <div>
                     <div class="text-sm text-primary-200 mb-1">Global Sync</div>
                     <div class="text-2xl font-bold text-white">&lt; 50ms</div>
                 </div>
             </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import BrandLogo from '../Components/BrandLogo.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';
import { useAppState } from '../Composables/useAppState';

const router = useRouter();
const { loginAs } = useAppState();

const demoLogin = (role) => {
    loginAs(role);
    router.push('/dashboard');
};
</script>
