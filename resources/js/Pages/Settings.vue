<template>
  <AppLayout>
  <div>
    <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white mb-6">System Settings</h1>

    <div class="flex flex-col md:flex-row gap-6">
      <!-- Sidebar Tabs -->
      <div class="w-full md:w-64 shrink-0">
        <nav class="space-y-1">
          <button @click="activeTab = 'general'" :class="[activeTab === 'general' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white', 'w-full text-left group rounded-xl px-4 py-3 flex items-center text-sm font-medium transition-colors']">
            <svg :class="[activeTab === 'general' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            System Settings
          </button>
          <button @click="activeTab = 'pos'" :class="[activeTab === 'pos' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white', 'w-full text-left group rounded-xl px-4 py-3 flex items-center text-sm font-medium transition-colors']">
            <svg :class="[activeTab === 'pos' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            POS Settings
          </button>
          <button @click="activeTab = 'printers'" :class="[activeTab === 'printers' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white', 'w-full text-left group rounded-xl px-4 py-3 flex items-center text-sm font-medium transition-colors']">
            <svg :class="[activeTab === 'printers' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Printers
          </button>
          <button @click="activeTab = 'backup'" :class="[activeTab === 'backup' ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 font-semibold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white', 'w-full text-left group rounded-xl px-4 py-3 flex items-center text-sm font-medium transition-colors']">
            <svg :class="[activeTab === 'backup' ? 'text-primary-500' : 'text-gray-400 group-hover:text-gray-500 dark:group-hover:text-gray-300', 'mr-3 flex-shrink-0 h-5 w-5']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Backup & Restore
          </button>
        </nav>
      </div>

      <!-- Settings Content -->
      <div class="flex-1">
        
        <!-- General Settings Tab -->
        <div v-if="activeTab === 'general'" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
          <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">General Settings</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your basic company information and preferences.</p>
          </div>
          
          <div class="p-6 space-y-6">
            <!-- Logo Upload -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Company Logo</label>
              <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-xl border border-gray-200 dark:border-gray-700 flex items-center justify-center bg-gray-50 dark:bg-black overflow-hidden relative">
                  <img v-if="logoPreview || company?.logo_path" :src="logoPreview || (company?.logo_path?.startsWith('http') ? company?.logo_path : '/storage/' + company?.logo_path)" class="w-full h-full object-cover" />
                  <svg v-else class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <input type="file" ref="logoInput" @change="handleLogoChange" accept="image/*" class="hidden" />
                <button type="button" @click="$refs.logoInput.click()" class="bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                  Change Logo
                </button>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
                <input type="text" v-model="form.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Phone</label>
                <input type="text" v-model="form.phone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Email</label>
                <input type="email" v-model="form.email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Currency</label>
                <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      {{ form.currency }}
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" v-for="currency in availableCurrencies" :key="currency" @click.prevent="form.currency = currency; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="form.currency === currency ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ currency }}</a>
                  </template>
                </Dropdown>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Timezone</label>
                <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      {{ form.timezone }}
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" v-for="tz in availableTimezones" :key="tz" @click.prevent="form.timezone = tz; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="form.timezone === tz ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ tz }}</a>
                  </template>
                </Dropdown>
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Address</label>
                <textarea v-model="form.address" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" rows="3"></textarea>
              </div>
            </div>
          </div>
          <div class="px-6 py-4 bg-gray-50 dark:bg-black/50 border-t border-gray-100 dark:border-gray-800 flex justify-end">
            <button @click="saveSettings" :disabled="form.processing" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50 flex items-center gap-2">
              Save Changes
            </button>
          </div>
        </div>

        <!-- POS Settings Tab -->
        <div v-if="activeTab === 'pos'" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
          <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">POS Checkout Settings</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Configure default checkout behavior and printed receipt footer.</p>
          </div>
          
          <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Receipt Tax Rate (%)</label>
                <input type="number" step="0.01" min="0" max="100" v-model="form.pos_tax_rate" placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quick Payment Preset Buttons</label>
                <input type="text" value="৳100, ৳500, ৳1000, ৳5000" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500 sm:text-sm cursor-not-allowed" disabled />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Receipt Footer Note</label>
                <textarea v-model="form.pos_receipt_footer" rows="3" placeholder="Thank you for shopping at ABC HQ!" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm"></textarea>
              </div>
            </div>
          </div>
          <div class="px-6 py-4 bg-gray-50 dark:bg-black/50 border-t border-gray-100 dark:border-gray-800 flex justify-end">
            <button @click="saveSettings" :disabled="form.processing" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50">
              Save POS Settings
            </button>
          </div>
        </div>

        <!-- Printers Tab -->
        <div v-if="activeTab === 'printers'" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
          <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Printer Preferences</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage receipt printing formats and automatic print triggers.</p>
          </div>
          
          <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Default Receipt Format</label>
                <Dropdown align="left" width="full" fullWidth>
                  <template #trigger>
                    <button type="button" class="flex justify-between items-center w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 sm:text-sm transition-colors text-left min-h-[38px]">
                      {{ printerLabels[form.printer_type] || '80mm Thermal Printer' }}
                      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                  </template>
                  <template #content="{ close }">
                    <a href="#" v-for="(label, key) in printerLabels" :key="key" @click.prevent="form.printer_type = key; close()" class="block px-4 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" :class="form.printer_type === key ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-300'">{{ label }}</a>
                  </template>
                </Dropdown>
              </div>

              <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800/50 md:col-span-2">
                <div>
                  <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Auto-Print Receipt on Checkout</h3>
                  <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Automatically trigger native browser print dialog when completing a POS sale.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" v-model="form.auto_print_receipt" class="sr-only peer">
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-600 peer-checked:bg-primary-600"></div>
                </label>
              </div>
            </div>
          </div>
          <div class="px-6 py-4 bg-gray-50 dark:bg-black/50 border-t border-gray-100 dark:border-gray-800 flex justify-end">
            <button @click="saveSettings" :disabled="form.processing" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50">
              Save Printer Preferences
            </button>
          </div>
        </div>

        <!-- Backup & Restore Tab -->
        <div v-if="activeTab === 'backup'" class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
          <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Backup & Restore</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage database backups and system recovery.</p>
          </div>
          
          <div class="p-6 space-y-6">
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
              <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Create New Backup</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Download a full SQL dump of your current database state.</p>
              </div>
              <a href="/settings/backup" target="_blank" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Generate & Download Backup
              </a>
            </div>
            
            <div class="flex items-center justify-between p-4 border border-red-200 dark:border-red-900/50 rounded-lg bg-red-50 dark:bg-red-900/10">
              <div>
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-400">Restore from SQL Backup File</h3>
                <p class="text-xs text-red-600 dark:text-red-500 mt-1">Warning: This will execute the uploaded SQL database file.</p>
              </div>
              <input type="file" ref="restoreFileInput" @change="handleRestoreUpload" accept=".sql,.txt" class="hidden" />
              <button type="button" @click="$refs.restoreFileInput.click()" :disabled="isRestoring" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors shadow-sm flex items-center gap-2 disabled:opacity-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                {{ isRestoring ? 'Restoring...' : 'Upload SQL File' }}
              </button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import Dropdown from '../Components/Dropdown.vue';
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    company: Object
});

const activeTab = ref('general');
const logoPreview = ref(null);
const isRestoring = ref(false);

const form = useForm({
    name: props.company?.name || '',
    phone: props.company?.phone || '',
    email: props.company?.email || '',
    address: props.company?.address || '',
    currency: props.company?.currency || 'BDT (৳)',
    timezone: props.company?.timezone || 'Asia/Dhaka',
    pos_tax_rate: props.company?.pos_tax_rate ?? 0,
    pos_receipt_footer: props.company?.pos_receipt_footer || 'Thank you for shopping at ABC HQ!',
    printer_type: props.company?.printer_type || 'thermal_80mm',
    auto_print_receipt: props.company?.auto_print_receipt ?? true,
    logo: null,
});

const availableCurrencies = [
    'BDT (৳)',
    'USD ($)', 
    'EUR (€)', 
    'GBP (£)', 
    'INR (₹)',
    'JPY (¥)',
    'CAD ($)',
    'AUD ($)'
];

const availableTimezones = [
    'Asia/Dhaka',
    'Asia/Kolkata',
    'America/New_York',
    'Europe/London',
    'Asia/Tokyo',
    'UTC'
];

const printerLabels = {
    'thermal_80mm': '80mm Thermal Receipt Printer',
    'thermal_58mm': '58mm Mini Thermal Receipt Printer',
    'standard_a4': 'Standard A4 Document PDF'
};

const handleLogoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const saveSettings = () => {
    form.post('/settings', {
        preserveScroll: true,
    });
};

const handleRestoreUpload = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    if (!confirm('Are you sure you want to restore the database from this SQL file? Existing records may be overwritten.')) {
        return;
    }

    isRestoring.value = true;
    const restoreForm = useForm({
        sql_file: file
    });

    restoreForm.post('/settings/restore', {
        onFinish: () => {
            isRestoring.value = false;
        }
    });
};
</script>
