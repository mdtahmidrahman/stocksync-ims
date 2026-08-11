<template>
  <div 
    class="w-full min-w-0 overflow-hidden text-ellipsis whitespace-nowrap tracking-tight transition-all duration-200"
    :class="[fontSizeClass, customClass]"
    :title="displayValue"
  >
    <slot>{{ displayValue }}</slot>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  value: {
    type: [String, Number],
    default: ''
  },
  defaultClass: {
    type: String,
    default: 'text-2xl sm:text-3xl font-extrabold'
  },
  customClass: {
    type: String,
    default: ''
  }
});

const displayValue = computed(() => String(props.value ?? ''));

const fontSizeClass = computed(() => {
  const len = displayValue.value.trim().length;

  if (len > 22) return 'text-xs sm:text-sm font-bold';
  if (len > 18) return 'text-sm sm:text-base font-bold';
  if (len > 14) return 'text-base sm:text-lg font-extrabold';
  if (len > 10) return 'text-lg sm:text-xl font-extrabold';
  if (len > 8)  return 'text-xl sm:text-2xl font-extrabold';
  return props.defaultClass;
});
</script>
