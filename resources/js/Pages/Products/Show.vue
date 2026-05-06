<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({ product: Object });

function softDelete() {
    if (confirm('Move this product to trash?')) {
        router.delete(route('products.destroy', props.product.id));
    }
}
</script>

<template>
    <Head :title="product.name" />
    <AuthenticatedLayout>
        <Toast />
        <template #header>
            <div class="page-header-row">
                <Link :href="route('products.index')" class="page-back-btn">
                    <svg class="page-back-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="page-title">Product Details</h2>
            </div>
        </template>

        <div class="page-content">
            <div class="product-detail-container">
                <div class="product-detail-card">

                    <!-- Image -->
                    <div v-if="product.image_path" class="product-image-hero">
                        <img :src="`/storage/${product.image_path}`" :alt="product.name" />
                    </div>
                    <div v-else class="product-image-hero--placeholder">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                        </svg>
                    </div>

                    <!-- Details -->
                    <div class="product-detail-body">
                        <div class="product-detail-header">
                            <h1 class="product-detail-title">{{ product.name }}</h1>
                            <span v-if="product.category" class="category-badge">{{ product.category }}</span>
                        </div>

                        <p class="product-detail-desc">{{ product.description ?? 'No description provided.' }}</p>

                        <div class="product-stats-grid">
                            <div class="stat-block">
                                <dt class="stat-label">Price</dt>
                                <dd class="stat-value">${{ Number(product.price).toFixed(2) }}</dd>
                            </div>
                            <div class="stat-block">
                                <dt class="stat-label">In Stock</dt>
                                <dd :class="product.quantity > 0 ? 'stat-value--success' : 'stat-value--danger'">
                                    {{ product.quantity }}
                                </dd>
                            </div>
                            <div class="stat-block">
                                <dt class="stat-label">Status</dt>
                                <dd :class="['stat-status', product.deleted_at ? 'stat-status--trashed' : 'stat-status--active']">
                                    {{ product.deleted_at ? 'Trashed' : 'Active' }}
                                </dd>
                            </div>
                        </div>

                        <div v-if="!product.deleted_at" class="product-detail-actions">
                            <Link :href="route('products.edit', product.id)" class="btn-edit">Edit Product</Link>
                            <button @click="softDelete" class="btn-trash">Move to Trash</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
