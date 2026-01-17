<script setup lang="ts">
import { ref, onMounted, onUnmounted, type FunctionalComponent } from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import NavItem from './NavItem.vue';
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

const setSidebarCollapsed = (collapsed: boolean) => {
    isCollapsed.value = collapsed;
    emit('toggle', collapsed);
};

const toggleSidebar = () => {
    setSidebarCollapsed(!isCollapsed.value);
};

// Responsive check
const collapseIfScreenIsSmall = () => {
    if (window.innerWidth < 768 && !isCollapsed.value) {
        setSidebarCollapsed(true);
    }
};

onMounted(() => {
    collapseIfScreenIsSmall();
    window.addEventListener('resize', collapseIfScreenIsSmall);
});

onUnmounted(() => {
    window.removeEventListener('resize', collapseIfScreenIsSmall);
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

type NavItemTemplate = {
    label: string;
    to: string;
    icon: FunctionalComponent;
}

const navItems: NavItemTemplate[] = [
    { label: 'Dashboard', to: '/', icon: HomeIcon },
    { label: 'Plex Events', to: '/plex-event', icon: QueueListIcon },
];
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
            <NavItem
                v-for="item in navItems"
                :key="item.to"
                :to="item.to"
                :label="item.label"
                :is-collapsed="isCollapsed"
            >
                <template #icon>
                    <component :is="item.icon" class="h-6 w-6" />
                </template>
            </NavItem>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <NavItem
                label="Logout"
                :is-collapsed="isCollapsed"
                variant="danger"
                @click="handleLogout"
            >
                <template #icon>
                    <ArrowLeftOnRectangleIcon class="h-6 w-6" />
                </template>
            </NavItem>
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
