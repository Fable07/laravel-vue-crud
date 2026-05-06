<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const open = ref(false);
</script>

<template>
    <div class="dropdown-wrap">
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <!-- Full-screen overlay to close on outside click -->
        <div
            v-show="open"
            class="dropdown-overlay"
            @click="open = false"
        ></div>

        <Transition name="dropdown">
            <div
                v-show="open"
                class="dropdown-panel"
                @click="open = false"
            >
                <div class="dropdown-content">
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
