<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="guest-layout">
        <div class="guest-layout__logo-wrap">
            <Link href="/" class="guest-layout__logo">
                <svg class="guest-layout__logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
            </Link>
        </div>

        <div class="guest-layout__card">
            <slot />
        </div>
    </div>
</template>
