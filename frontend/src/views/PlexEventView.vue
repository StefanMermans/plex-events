<script setup lang="ts">
import { ref } from 'vue';
import InputField from '../components/InputField.vue';
import PrimaryButton from '../components/PrimaryButton.vue';

import api from '../api';

const plexEventUrl = api.getUri({ url: '/plex/event' });
const copied = ref(false);

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(plexEventUrl);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                    Plex Event URL
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    Use this URL to configure Plex webhooks.
                </p>
            </div>
            
            <div class="mt-8 space-y-6">
                <InputField
                    id="plex-url"
                    :model-value="plexEventUrl"
                    readonly
                >
                    <template #suffix>
                        <PrimaryButton
                            type="button"
                            @click="copyToClipboard"
                            class="!w-auto !py-1 !px-3 !text-xs"
                        >
                            {{ copied ? 'Copied!' : 'Copy' }}
                        </PrimaryButton>
                    </template>
                </InputField>
            </div>
        </div>
    </div>
</template>
