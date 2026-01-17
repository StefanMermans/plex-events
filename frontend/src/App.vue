<script setup lang="ts">
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import AuthLayout from './layouts/AuthLayout.vue';
import GuestLayout from './layouts/GuestLayout.vue';

const route = useRoute();

const layout = computed(() => {
  const layoutMeta = route.meta.layout;
  
  if (layoutMeta === 'auth') {
    return AuthLayout;
  }
  
  // Default to GuestLayout for login/register or explicit guest pages
  // You might want to default to AuthLayout generally, but since we were explicit in router.ts, 
  // we can just handle the fallback.
  return GuestLayout;
});
</script>

<template>
  <component :is="layout">
    <RouterView />
  </component>
</template>
