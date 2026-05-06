<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

const props = defineProps({
    show: { type: Boolean, default: false },
    maxWidth: { type: String, default: '2xl' },
    closeable: { type: Boolean, default: true },
});

const emit = defineEmits(['close']);
const dialog = ref();
const showSlot = ref(props.show);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
            showSlot.value = true;
            dialog.value?.showModal();
        } else {
            document.body.style.overflow = '';
            setTimeout(() => {
                dialog.value?.close();
                showSlot.value = false;
            }, 200);
        }
    },
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape') {
        e.preventDefault();
        if (props.show) close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = '';
});

const maxWidthClass = computed(() => {
    const map = { sm: 'modal-panel--sm', md: 'modal-panel--md', lg: 'modal-panel--lg', xl: 'modal-panel--xl', '2xl': 'modal-panel--2xl' };
    return map[props.maxWidth] ?? 'modal-panel--2xl';
});
</script>

<template>
    <dialog class="modal-dialog" ref="dialog">
        <div class="modal-container" scroll-region>
            <Transition name="modal-fade">
                <div v-show="show" class="modal-backdrop" @click="close">
                    <div class="modal-backdrop__bg"></div>
                </div>
            </Transition>

            <Transition name="modal-slide">
                <div v-show="show" class="modal-panel" :class="maxWidthClass">
                    <slot v-if="showSlot" />
                </div>
            </Transition>
        </div>
    </dialog>
</template>
