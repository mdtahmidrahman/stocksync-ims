<template>
  <Modal :show="show" :scrollable="false" @close="$emit('close')" maxWidth="sm">
    <template #title>
      <div class="flex items-center gap-3 text-red-600 dark:text-red-500">
        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <span>Confirm Deletion</span>
      </div>
    </template>
    <template #body>
      <div class="text-gray-600 dark:text-gray-400 font-medium">
        {{ message || 'Are you sure you want to perform this action? This cannot be undone.' }}
      </div>
    </template>
    <template #footer>
      <button @click="$emit('close')" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-colors border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800">
        Cancel
      </button>
      <button @click="$emit('confirm')" class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        {{ confirmText || 'Delete' }}
      </button>
    </template>
  </Modal>
</template>

<script setup>
import Modal from './Modal.vue';

defineProps({
    show: Boolean,
    message: String,
    confirmText: {
        type: String,
        default: 'Delete'
    }
});

defineEmits(['close', 'confirm']);
</script>
