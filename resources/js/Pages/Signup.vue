<template>
  <div class="min-h-screen flex bg-gray-50 dark:bg-black transition-colors">
    <!-- Form Side -->
    <div class="flex-grow flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
      <div class="mx-auto w-full max-w-sm lg:w-96 relative">
         <div class="absolute top-0 right-0 -mt-8">
             <ThemeToggle />
         </div>
         
         <div>
          <BrandLogo size="xl" theme="dark" class="hidden dark:flex" />
          <BrandLogo size="xl" class="dark:hidden" />
          <h2 class="mt-8 text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Create your account</h2>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            Already have an account?
            <Link href="/login" class="font-semibold text-primary-600 hover:text-primary-500 transition-colors">
              Sign in
            </Link>
          </p>
        </div>

        <div class="mt-8">
          <form @submit.prevent="handleSignup" class="space-y-4">
            <div v-if="error" class="p-3 py-2.5 text-xs font-medium rounded-xl bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-900/30">
              {{ error }}
            </div>

            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Full Name </label>
              <input id="name" v-model="name" type="text" required autocomplete="name" class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all" placeholder="John Doe" />
            </div>

            <div>
              <label for="company" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Company Name </label>
              <input id="company" v-model="company" type="text" required autocomplete="organization" class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all" placeholder="Acme Corp" />
            </div>

            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Email address </label>
              <input id="email" v-model="email" type="email" required autocomplete="email" class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all" placeholder="you@company.com" />
            </div>

            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Password </label>
              <input id="password" v-model="password" type="password" required autocomplete="new-password" class="block w-full px-3 py-2.5 border border-gray-300 dark:border-gray-800 rounded-xl bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all" placeholder="••••••••" />
            </div>

            <div>
              <button type="submit" :disabled="isSubmitting" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md shadow-primary-500/20 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                {{ isSubmitting ? 'Creating account...' : 'Create Account' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Right Panel: Abstract Gradient & Glassmorphism -->
    <div class="hidden lg:block relative w-0 flex-1 bg-gradient-to-br from-primary-900 via-primary-800 to-black overflow-hidden">
      <div class="absolute top-0 left-0 w-full h-full opacity-30">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-primary-500 blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-blue-500 blur-3xl"></div>
      </div>

      <div class="absolute inset-0 flex items-center justify-center p-12 z-10">
         <div class="backdrop-blur-xl bg-white/10 dark:bg-black/20 border border-white/20 dark:border-white/10 p-10 rounded-3xl shadow-2xl max-w-lg w-full text-white relative overflow-hidden">
             <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary-400 to-blue-400"></div>
             
             <h2 class="text-3xl font-bold mb-4 leading-tight">Start managing smarter today.</h2>
             <p class="text-primary-100 text-lg mb-8 leading-relaxed">
               Join thousands of businesses streamlining operations and scaling with StockSync.
             </p>
             
             <div class="flex items-center gap-6 p-4 rounded-2xl bg-white/5 border border-white/10">
                 <div>
                     <div class="text-sm text-primary-200 mb-1">Alert Trigger Time</div>
                     <div class="text-2xl font-bold text-white">&lt; 10s</div>
                 </div>
                 <div class="w-px h-10 bg-white/20"></div>
                 <div>
                     <div class="text-sm text-primary-200 mb-1">Automated PO Generation</div>
                     <div class="text-2xl font-bold text-white">Enabled</div>
                 </div>
             </div>
         </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import BrandLogo from '../Components/BrandLogo.vue';
import ThemeToggle from '../Components/ThemeToggle.vue';

const page = usePage();
const name = ref('');
const company = ref('');
const email = ref('');
const password = ref('');
const isSubmitting = ref(false);

const error = computed({
    get: () => {
        const errors = page.props.errors || {};
        return Object.values(errors)[0] || '';
    },
    set: (val) => {
        if (!val) page.props.errors = {};
    }
});

const handleSignup = () => {
    error.value = '';
    isSubmitting.value = true;
    router.post('/register', {
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password.value,
        company_name: company.value
    }, {
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>
