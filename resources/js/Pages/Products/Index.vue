<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
    products: Object,
    filters: Object,
});

const search = ref(props.filters?.search ?? '');
const trashed = ref(props.filters?.trashed ?? '');

let searchTimeout = null;
watch(search, (val) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => applyFilters(), 400);
});

watch(trashed, () => applyFilters());

function applyFilters() {
    router.get(
        route('products.index'),
        { search: search.value, trashed: trashed.value },
        { preserveState: true, replace: true }
    );
}

function confirmDelete(id, permanent = false) {
    const msg = permanent
        ? 'Permanently delete this product? This cannot be undone.'
        : 'Move this product to trash?';

    if (!confirm(msg)) return;

    if (permanent) {
        router.delete(route('products.force-delete', id));
    } else {
        router.delete(route('products.destroy', id));
    }
}

function restoreProduct(id) {
    router.post(route('products.restore', id));
}
</script>

<template>
    <Head title="Products" />
    <AuthenticatedLayout>
        <Toast />
        <template #header>
            <h2 class="page-title">Products</h2>
        </template>

        <div class="page-content">
            <div class="page-container">

                <!-- Toolbar -->
                <div class="products-toolbar">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Search products…"
                        class="products-search"
                    />

                    <div class="products-toolbar__right">
                        <select v-model="trashed" class="products-filter">
                            <option value="">Active Products</option>
                            <option value="only">Trash</option>
                            <option value="with">All (incl. Trash)</option>
                        </select>

                        <Link :href="route('products.create')" class="products-add-btn">
                            <svg class="products-add-btn__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Product
                        </Link>
                    </div>
                </div>

                <!-- Table -->
                <div class="products-table-wrap">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th class="th-right">Price</th>
                                <th class="th-right">Qty</th>
                                <th class="th-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="products.data.length === 0">
                                <td colspan="5" class="products-empty">No products found.</td>
                            </tr>
                            <tr
                                v-for="product in products.data"
                                :key="product.id"
                                :class="['products-row', product.deleted_at && 'products-row--trashed']"
                            >
                                <!-- Product -->
                                <td>
                                    <div class="product-info">
                                        <img
                                            v-if="product.image_path"
                                            :src="`/storage/${product.image_path}`"
                                            :alt="product.name"
                                            class="product-thumb"
                                        />
                                        <div v-else class="product-thumb--placeholder">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="product-name">{{ product.name }}</p>
                                            <p class="product-desc">{{ product.description }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span v-if="product.category" class="category-badge">{{ product.category }}</span>
                                    <span v-else class="qty-text">—</span>
                                </td>

                                <td class="td-right">
                                    <span class="price-text">${{ Number(product.price).toFixed(2) }}</span>
                                </td>

                                <td class="td-right">
                                    <span class="qty-text">{{ product.quantity }}</span>
                                </td>

                                <!-- Actions -->
                                <td class="td-center">
                                    <div class="actions-group">
                                        <template v-if="!product.deleted_at">
                                            <Link :href="route('products.show', product.id)" class="action-btn action-btn--view" title="View">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </Link>
                                            <Link :href="route('products.edit', product.id)" class="action-btn action-btn--edit" title="Edit">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </Link>
                                            <button @click="confirmDelete(product.id)" class="action-btn action-btn--delete" title="Move to Trash">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button @click="restoreProduct(product.id)" class="action-btn action-btn--restore" title="Restore">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            </button>
                                            <button @click="confirmDelete(product.id, true)" class="action-btn action-btn--destroy" title="Delete Permanently">
                                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <Pagination :links="products.links" />

                <!-- Count -->
                <p class="products-count">
                    Showing {{ products.from ?? 0 }}–{{ products.to ?? 0 }} of {{ products.total }} products
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
