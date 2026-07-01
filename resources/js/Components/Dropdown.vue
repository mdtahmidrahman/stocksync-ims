<template>
  <div :class="['relative text-left', fullWidth ? 'block w-full' : 'inline-block']" ref="dropdownRef">
    <!-- Trigger -->
    <div @click="toggle" :class="['cursor-pointer', fullWidth ? 'block w-full' : 'inline-block']">
      <slot name="trigger" />
    </div>

    <!-- Dropdown Content -->
    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-if="isOpen"
        :class="[
          'absolute z-50 rounded-xl shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 dark:ring-gray-700 focus:outline-none',
          widthClass,
          alignClass
        ]"
      >
        <div class="py-1">
          <slot name="content" :close="close" />
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useClickOutside } from '../Composables/useClickOutside';

const props = defineProps({
  align: {
    type: String,
    default: 'right', // 'left', 'right', 'center', 'top-left', 'top-right', 'top-center'
  },
  width: {
    type: String,
    default: '48', 
  },
  fullWidth: {
    type: Boolean,
    default: false,
  },
});

const isOpen = ref(false);
const dropdownRef = ref(null);

const toggle = () => {
  isOpen.value = !isOpen.value;
};

const close = () => {
  isOpen.value = false;
};

useClickOutside(dropdownRef, close);

const alignClass = computed(() => {
  if (props.align === 'left') return 'origin-top-left left-0 mt-2';
  if (props.align === 'right') return 'origin-top-right right-0 mt-2';
  if (props.align === 'top-left') return 'origin-bottom-left left-0 bottom-full mb-2';
  if (props.align === 'top-right') return 'origin-bottom-right right-0 bottom-full mb-2';
  if (props.align === 'top-center') return 'origin-bottom left-1/2 -translate-x-1/2 bottom-full mb-2';
  return 'origin-top left-1/2 -translate-x-1/2 mt-2';
});

const widthClass = computed(() => {
  if (props.width.startsWith('w-')) return props.width;
  return `w-${props.width}`;
});
</script>
