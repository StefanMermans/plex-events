<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import { 
    HomeIcon, 
    QueueListIcon, 
    ChevronDoubleLeftIcon, 
    ChevronDoubleRightIcon,
    ArrowLeftOnRectangleIcon 
} from '@heroicons/vue/24/outline';

const isCollapsed = ref(false);

const emit = defineEmits<{
    (e: 'toggle', collapsed: boolean): void
}>();

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
    // Emit event for parent layout to adjust margins
    emit('toggle', isCollapsed.value);
};

// Responsive check
const checkScreenSize = () => {
    if (window.innerWidth < 768) {
        if (!isCollapsed.value) {
            isCollapsed.value = true;
            emit('toggle', true);
        }
    } else {
         // Optional: auto-expand on large screens? 
         // For now, let's respect user choice or default to expanded only on initial load if > 768
    }
};

onMounted(() => {
    if (window.innerWidth < 768) {
        isCollapsed.value = true;
    }
    emit('toggle', isCollapsed.value); // Sync initial state
    
    window.addEventListener('resize', checkScreenSize);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkScreenSize);
});

const router = useRouter();

const handleLogout = async () => {
    try {
        await api.get('/logout');
    } catch (e) {
        console.error('Logout failed', e);
    } finally {
        await router.push('/login');
    }
};
</script>

<template>
    <div 
        class="bg-gray-900 text-white h-screen flex flex-col transition-all duration-300 ease-in-out shadow-xl z-50 fixed left-0 top-0 border-r border-gray-800"
        :class="[isCollapsed ? 'w-20' : 'w-64']"
    >
        <div class="p-6 flex items-center justify-between border-b border-gray-800 h-20">
            <h1 
                v-if="!isCollapsed" 
                class="text-xl font-bold tracking-wider text-primary-400 truncate animate-fade-in"
            >
                PLEX EVENTS
            </h1>
            <button 
                @click="toggleSidebar" 
                class="text-gray-400 hover:text-white transition-colors p-1 rounded-md hover:bg-gray-800 focus:outline-none"
            >
                <component 
                    :is="isCollapsed ? ChevronDoubleRightIcon : ChevronDoubleLeftIcon" 
                    class="h-6 w-6" 
                />
            </button>
        </div>

        <nav class="flex-1 py-6 px-3 space-y-2 overflow-y-auto">
            <RouterLink 
                to="/" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 group relative"
                active-class="bg-primary-600 text-white shadow-lg shadow-primary-900/50"
                :class="[$route.path !== '/' ? 'text-gray-300 hover:bg-gray-800 hover:text-white' : '']"
            >
                <HomeIcon class="h-6 w-6 flex-shrink-0" />
                <span 
                    v-if="!isCollapsed" 
                    class="font-medium whitespace-nowrap transition-opacity duration-200"
                >
                    Dashboard
                </span>
                
                <!-- Tooltip for collapsed state -->
                <div 
                    v-if="isCollapsed" 
                    class="absolute left-16 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 border border-gray-700 pointer-events-none shadow-xl"
                >
                    Dashboard
                </div>
            </RouterLink>

            <RouterLink 
                to="/plex-event" 
                class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 group relative"
                active-class="bg-primary-600 text-white shadow-lg shadow-primary-900/50"
                :class="[$route.path !== '/plex-event' ? 'text-gray-300 hover:bg-gray-800 hover:text-white' : '']"
            >
                <QueueListIcon class="h-6 w-6 flex-shrink-0" />
                <span 
                    v-if="!isCollapsed" 
                    class="font-medium whitespace-nowrap transition-opacity duration-200"
                >
                    Plex Events
                </span>
                
                <!-- Tooltip for collapsed state -->
                <div 
                    v-if="isCollapsed" 
                    class="absolute left-16 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 border border-gray-700 pointer-events-none shadow-xl"
                >
                    Plex Events
                </div>
            </RouterLink>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <button 
                @click="handleLogout"
                class="flex items-center w-full space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-red-900/20 hover:text-red-400 transition-all duration-200 group relative"
            >
                <ArrowLeftOnRectangleIcon class="h-6 w-6 flex-shrink-0" />
                <span 
                    v-if="!isCollapsed" 
                    class="font-medium whitespace-nowrap"
                >
                    Logout
                </span>
                 <!-- Tooltip for collapsed state -->
                <div 
                    v-if="isCollapsed" 
                    class="absolute left-16 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50 border border-gray-700 pointer-events-none shadow-xl"
                >
                    Logout
                </div>
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Ensure custom primary color exists or use indigo as fallback if not defined in Tailwind config */
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

.animate-fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
