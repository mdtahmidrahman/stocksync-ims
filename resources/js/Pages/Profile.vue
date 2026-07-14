<template>
  <AppLayout>
  <div>
    <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 dark:text-white mb-6">Profile Settings</h1>

    <div class="space-y-6">
      
      <!-- Profile Information -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white">Profile Information</h2>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your account's profile information and email address.</p>
        </div>
        
        <div class="p-6 space-y-6">
          <div v-if="profileForm.wasSuccessful" class="p-3 text-sm font-medium rounded-xl bg-green-50 text-green-600 border border-green-100">
            Profile updated successfully.
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
              <input type="text" v-model="profileForm.name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="profileForm.errors.name" class="text-red-500 text-xs mt-1">{{ profileForm.errors.name }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
              <input type="email" v-model="profileForm.email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="profileForm.errors.email" class="text-red-500 text-xs mt-1">{{ profileForm.errors.email }}</div>
            </div>
          </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-black/50 border-t border-gray-100 dark:border-gray-800 flex justify-end">
          <button @click="profileForm.patch('/profile')" :disabled="profileForm.processing" class="bg-primary-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-primary-700 transition-colors shadow-sm disabled:opacity-50">
            Save Changes
          </button>
        </div>
      </div>

      <!-- Update Password -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
          <h2 class="text-lg font-bold text-gray-900 dark:text-white">Update Password</h2>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        
        <div class="p-6 space-y-6">
          <div v-if="passwordForm.wasSuccessful" class="p-3 text-sm font-medium rounded-xl bg-green-50 text-green-600 border border-green-100">
            Password updated successfully.
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
              <input type="password" v-model="passwordForm.current_password" class="w-full md:w-1/2 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="passwordForm.errors.current_password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.current_password }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
              <input type="password" v-model="passwordForm.password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="passwordForm.errors.password" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.password }}</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
              <input type="password" v-model="passwordForm.password_confirmation" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" />
              <div v-if="passwordForm.errors.password_confirmation" class="text-red-500 text-xs mt-1">{{ passwordForm.errors.password_confirmation }}</div>
            </div>
          </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 dark:bg-black/50 border-t border-gray-100 dark:border-gray-800 flex justify-end">
          <button @click="updatePassword" :disabled="passwordForm.processing" class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-gray-700 transition-colors shadow-sm disabled:opacity-50">
            Update Password
          </button>
        </div>
      </div>

      <!-- Delete Account -->
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-red-200 dark:border-red-900/50 overflow-hidden transition-colors">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
          <h2 class="text-lg font-bold text-red-600 dark:text-red-500">Delete Account</h2>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        </div>
        <div class="p-6">
          <button @click="showDeleteModal = true" class="bg-red-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors shadow-sm">
            Delete Account
          </button>
        </div>
      </div>
      
    </div>
  </div>

  <!-- Delete Modal -->
  <Modal :show="showDeleteModal" @close="showDeleteModal = false" @save="deleteAccount" saveText="Delete Account" saveClass="bg-red-600 hover:bg-red-700">
    <template #title>Are you sure you want to delete your account?</template>
    <template #body>
      <div class="space-y-4">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
        </p>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
            <input type="password" v-model="deleteForm.password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-black text-gray-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Password" />
            <div v-if="deleteForm.errors.password" class="text-red-500 text-xs mt-1">{{ deleteForm.errors.password }}</div>
        </div>
      </div>
    </template>
  </Modal>

  </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';
import Modal from '../Components/Modal.vue';

const page = usePage();
const user = page.props.auth.user;

const profileForm = useForm({
    name: user.name,
    email: user.email,
});

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const deleteForm = useForm({
    password: '',
});

const showDeleteModal = ref(false);

const updatePassword = () => {
    passwordForm.put('/password', {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
        onError: () => {
            if (passwordForm.errors.password) {
                passwordForm.reset('password', 'password_confirmation');
            }
            if (passwordForm.errors.current_password) {
                passwordForm.reset('current_password');
            }
        },
    });
};

const deleteAccount = () => {
    deleteForm.delete('/profile', {
        preserveScroll: true,
        onSuccess: () => (showDeleteModal.value = false),
        onFinish: () => deleteForm.reset(),
    });
};
</script>
