<template>
  <router-link to="/" class="flex items-center gap-2.5 w-fit transition-transform duration-300 hover:scale-105 origin-left">
    <div :class="[bgClass, sizeClass]" class="rounded-xl overflow-hidden flex items-center justify-center shadow-sm shrink-0 p-1">
      <img src="/images/logo.png" alt="StockSync" class="w-full h-full object-contain" />
    </div>
    <span :class="textClass" style="font-family: 'Outfit', sans-serif; font-weight: 800; letter-spacing: -0.02em;">
      StockSync
    </span>
  </router-link>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  theme: {
    type: String,
    default: 'light' // 'light' means dark text, 'dark' means light text
  },
  size: {
    type: String,
    default: 'md' // 'md', 'lg', 'xl'
  }
});

const bgClass = computed(() => {
  // Always use white background for the image to blend cleanly
  return 'bg-white shadow-primary-500/20'; 
});

const sizeClass = computed(() => {
  if (props.size === 'xl') return 'w-12 h-12';
  if (props.size === 'lg') return 'w-9 h-9';
  return 'w-7 h-7';
});

const textClass = computed(() => {
  let color = props.theme === 'dark' ? 'text-white' : 'text-gray-900';
  let size = props.size === 'xl' ? 'text-4xl' : (props.size === 'lg' ? 'text-2xl' : 'text-xl');
  return `${color} ${size}`;
});
</script>
