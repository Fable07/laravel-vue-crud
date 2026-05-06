<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const visible = ref(false);
const message = ref('');
const type = ref('success');

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            message.value = flash.success;
            type.value = 'success';
            visible.value = true;
            setTimeout(() => (visible.value = false), 3500);
        } else if (flash?.error) {
            message.value = flash.error;
            type.value = 'error';
            visible.value = true;
            setTimeout(() => (visible.value = false), 4000);
        }
    },
    { immediate: true, deep: true }
);
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div
            v-if="visible"
            :class="['toast', type === 'success' ? 'toast--success' : 'toast--error']"
        >
            <svg v-if="type === 'success'" class="toast__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <svg v-else class="toast__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="toast__message">{{ message }}</span>
            <button class="toast__close" @click="visible = false">&times;</button>
        </div>
    </Transition>
</template>
