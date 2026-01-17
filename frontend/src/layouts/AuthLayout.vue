<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <LeftNav @toggle="handleSidebarToggle" />
        
        <main 
            class="transition-all duration-300 ease-in-out min-h-screen"
            :class="[isSidebarCollapsed ? 'pl-20' : 'pl-64']"
        >
            <div class="p-8">
                <!-- Top bar or other common auth elements could go here -->
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import LeftNav from '../components/LeftNav.vue';

const isSidebarCollapsed = ref(false);

const handleSidebarToggle = (collapsed: boolean) => {
    isSidebarCollapsed.value = collapsed;
};
</script>
