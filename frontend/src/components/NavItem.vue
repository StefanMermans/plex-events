<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink, useRoute } from 'vue-router';

const props = withDefaults(defineProps<{
    label: string;
    isCollapsed: boolean;
    to?: string;
    variant?: 'default' | 'danger';
}>(), {
    variant: 'default'
});

const route = useRoute();

const isRouterLink = computed(() => !!props.to);
const componentType = computed(() => isRouterLink.value ? RouterLink : 'button');

const isActive = computed(() => {
    if (!props.to) return false;
    // Strict match to mimic original behavior
    return route.path === props.to;
});

const baseClasses = "flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 group relative w-full";
const activeClasses = "bg-primary-600 text-white shadow-lg shadow-primary-900/50";
const inactiveClasses = "text-gray-300 hover:bg-gray-800 hover:text-white";
const dangerClasses = "text-gray-300 hover:bg-red-900/20 hover:text-red-400";

const computedClasses = computed(() => {
    const classes = [baseClasses];
    
    if (props.variant === 'danger') {
        classes.push(dangerClasses);
    } else {
        if (isActive.value) {
            classes.push(activeClasses);
        } else {
            classes.push(inactiveClasses);
        }
    }
    
    return classes.join(' ');
});
</script>

<template>
    <component 
        :is="componentType"
        :to="to"
        :class="computedClasses"
    >
        <div class="h-6 w-6 flex-shrink-0">
            <slot name="icon"></slot>
        </div>

        <span 
            v-if="!isCollapsed" 
            class="font-medium whitespace-nowrap transition-opacity duration-200"
        >
            {{ label }}
        </span>
        
        <!-- Tooltip -->
        <div 
            v-if="isCollapsed" 
            class="absolute left-16 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 border border-gray-700 pointer-events-none shadow-xl"
        >
            {{ label }}
        </div>
    </component>
</template>

<style scoped>
/* Copied / Adapted from LeftNav.vue to ensure consistent styling */
.text-primary-400 {
    color: var(--color-primary-400, #818cf8); 
}
.bg-primary-600 {
    background-color: var(--color-primary-600, #4f46e5);
}
.shadow-primary-900\/50 {
    --tw-shadow-color: rgb(49 46 129 / 0.5);
    --tw-shadow: 0 10px 15px -3px var(--tw-shadow-color), 0 4px 6px -4px var(--tw-shadow-color);
    box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
}
</style>
