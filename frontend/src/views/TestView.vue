<script setup lang="ts">
import {onMounted, ref} from "vue";

const show = ref();

onMounted(async () => {
  const api_url = import.meta.env.VITE_API_URL;
  const response = await fetch(`${api_url}/show/437266`);
  show.value = await response.json();
})

</script>

<template>
  <div v-if="show">
    <div class="text-2xl">Loaded show {{ show?.title }}</div>
  </div>
  <div v-else>Loading...</div>
</template>
