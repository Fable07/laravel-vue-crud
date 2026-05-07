<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: { type: Boolean },
    canRegister: { type: Boolean },
    laravelVersion: { type: String, required: true },
    phpVersion: { type: String, required: true },
});
</script>

<template>
    <Head title="Welcome" />
    <div class="welcome-page">

        <!-- Navbar -->
        <nav class="welcome-nav">
            <div class="welcome-brand">
                <svg class="welcome-brand-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
                Inventory
            </div>

            <div v-if="canLogin" class="welcome-nav-links">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="welcome-nav-link--primary">
                    Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')" class="welcome-nav-link">Log in</Link>
                    <Link v-if="canRegister" :href="route('register')" class="welcome-nav-link--primary">Get started</Link>
                </template>
            </div>
        </nav>

        <!-- Hero -->
        <section class="welcome-hero">
            <div class="welcome-badge">
                <span class="welcome-badge-dot"></span>
                Product Inventory System
            </div>

            <h1 class="welcome-title">
                Manage your<br>
                <span class="welcome-title-accent">inventory</span> with ease
            </h1>

            <p class="welcome-subtitle">
                A full-featured CRUD application to track, manage and organise your product inventory — with authentication and image uploads.
            </p>

            <div class="welcome-cta">
                <Link v-if="canRegister" :href="route('register')" class="welcome-btn-primary">
                    <svg class="welcome-btn-primary-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                    Get started free
                </Link>
                <Link :href="route('login')" class="welcome-btn-secondary">Sign in to your account</Link>
            </div>

            <!-- Feature highlights -->
            <div class="welcome-features">
                <div class="welcome-feature-card">
                    <div class="welcome-feature-icon">
                        <svg class="welcome-feature-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="welcome-feature-title">Full CRUD</h3>
                    <p class="welcome-feature-desc">Create, view, edit, and delete products with image upload and category tagging.</p>
                </div>
                <div class="welcome-feature-card">
                    <div class="welcome-feature-icon">
                        <svg class="welcome-feature-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="welcome-feature-title">Secure Auth</h3>
                    <p class="welcome-feature-desc">Register and log in with Laravel's built-in authentication.</p>
                </div>
                <div class="welcome-feature-card">
                    <div class="welcome-feature-icon">
                        <svg class="welcome-feature-icon-svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="welcome-feature-title">Soft Deletes</h3>
                    <p class="welcome-feature-desc">Move products to trash and restore them later, or permanently delete when ready.</p>
                </div>
            </div>
        </section>

        <footer class="welcome-footer">
            Laravel {{ laravelVersion }} &middot; PHP {{ phpVersion }}
        </footer>
    </div>
</template>

