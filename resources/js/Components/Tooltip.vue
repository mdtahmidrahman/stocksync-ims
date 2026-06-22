<template>
  <div
    class="relative inline-flex items-center justify-center group"
    @mouseenter="show = true"
    @mouseleave="show = false"
    @focusin="show = true"
    @focusout="show = false"
  >
    <!-- Target Element -->
    <slot />

    <!-- Tooltip Popup -->
    <transition
      enter-active-class="transition ease-out duration-200"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div
        v-if="show"
        :class="[
          'absolute z-50 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700 whitespace-nowrap pointer-events-none',
          positionClasses
        ]"
      >
        {{ text }}
        <!-- Optional Arrow (can be customized) -->
        <div :class="['absolute w-2 h-2 bg-gray-900 dark:bg-gray-700 transform rotate-45', arrowClasses]"></div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  text: {
    type: String,
    required: true,
  },
  position: {
    type: String,
    default: 'top', // 'top', 'bottom', 'left', 'right'
  },
});

const show = ref(false);

const positionClasses = computed(() => {
  switch (props.position) {
    case 'top':
      return 'bottom-full mb-2 left-1/2 -translate-x-1/2 origin-bottom';
    case 'bottom':
      return 'top-full mt-2 left-1/2 -translate-x-1/2 origin-top';
    case 'left':
      return 'right-full mr-2 top-1/2 -translate-y-1/2 origin-right';
    case 'right':
      return 'left-full ml-2 top-1/2 -translate-y-1/2 origin-left';
    default:
      return 'bottom-full mb-2 left-1/2 -translate-x-1/2 origin-bottom';
  }
});

const arrowClasses = computed(() => {
  switch (props.position) {
    case 'top':
      return 'bottom-[-4px] left-1/2 -translate-x-1/2';
    case 'bottom':
      return 'top-[-4px] left-1/2 -translate-x-1/2';
    case 'left':
      return 'right-[-4px] top-1/2 -translate-y-1/2';
    case 'right':
      return 'left-[-4px] top-1/2 -translate-y-1/2';
    default:
      return 'bottom-[-4px] left-1/2 -translate-x-1/2';
  }
});
</script>
