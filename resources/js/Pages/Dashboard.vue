<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="page-title">Dashboard</h2>
        </template>

        <div class="dashboard-page">
            <div class="dashboard-container">
                <div class="dashboard-welcome">
                    <div class="dashboard-welcome__banner"></div>
                    <div class="dashboard-welcome__body">
                        <h2 class="dashboard-welcome__title">Welcome back, {{ $page.props.auth.user.name }}!</h2>
                        <p class="dashboard-welcome__subtitle">You're logged in and ready to manage your inventory.</p>

                        <div class="dashboard-quick-links">
                            <Link :href="route('products.index')" class="dashboard-quick-link">
                                <svg class="dashboard-quick-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                </svg>
                                View Products
                            </Link>
                            <Link :href="route('products.create')" class="dashboard-quick-link">
                                <svg class="dashboard-quick-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Product
                            </Link>
                        </div>

                        <div class="dashboard-info-grid">
                            <div class="dashboard-info-card">
                                <div class="dashboard-info-card__icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="dashboard-info-card__label">Products</div>
                                    <div class="dashboard-info-card__value">Create, read, update &amp; delete products</div>
                                </div>
                            </div>
                            <div class="dashboard-info-card">
                                <div class="dashboard-info-card__icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="dashboard-info-card__label">Image Upload</div>
                                    <div class="dashboard-info-card__value">JPG, PNG, WebP up to 2 MB per product</div>
                                </div>
                            </div>
                            <div class="dashboard-info-card">
                                <div class="dashboard-info-card__icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="dashboard-info-card__label">Soft Deletes</div>
                                    <div class="dashboard-info-card__value">Trash, restore or permanently delete products</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
