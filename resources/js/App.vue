<template>
  <router-view></router-view>
</template>

<script setup>
import { useAppState } from './Composables/useAppState';

// Initialize dark mode and layout state globally
useAppState();
</script>
