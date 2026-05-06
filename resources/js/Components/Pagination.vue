<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <div v-if="links && links.length > 3" class="pagination">
        <template v-for="link in links" :key="link.label">
            <Link
                v-if="link.url"
                :href="link.url"
                preserve-scroll
                :class="['pagination__link', link.active && 'pagination__link--active']"
                v-html="link.label"
            />
            <span
                v-else
                class="pagination__ellipsis"
                v-html="link.label"
            />
        </template>
    </div>
</template>
