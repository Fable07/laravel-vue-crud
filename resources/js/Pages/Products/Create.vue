<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Toast from '@/Components/Toast.vue';

const form = useForm({
    name: '',
    description: '',
    price: '',
    quantity: '',
    category: '',
    image: null,
});

const categories = ['Electronics', 'Clothing', 'Food & Beverage', 'Health & Beauty', 'Home & Garden', 'Sports', 'Other'];

function handleImage(e) {
    form.image = e.target.files[0] ?? null;
}

function submit() {
    form.post(route('products.store'), {
        forceFormData: true,
    });
}
</script>

<template>
    <Head title="Add Product" />
    <AuthenticatedLayout>
        <Toast />
        <template #header>
            <div class="page-header-row">
                <Link :href="route('products.index')" class="page-back-btn">
                    <svg class="page-back-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </Link>
                <h2 class="page-title">Add Product</h2>
            </div>
        </template>

        <div class="page-content">
            <div class="product-form-container">
                <form @submit.prevent="submit" enctype="multipart/form-data" class="product-form">

                    <div class="form-group">
                        <InputLabel for="name" value="Product Name *" />
                        <TextInput id="name" v-model="form.name" required autofocus />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="form-group">
                        <InputLabel for="description" value="Description" />
                        <textarea id="description" v-model="form.description" rows="3" class="form-textarea"></textarea>
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <InputLabel for="price" value="Price (PHP) *" />
                            <TextInput id="price" v-model="form.price" type="number" step="0.01" min="0" required />
                            <InputError :message="form.errors.price" />
                        </div>
                        <div class="form-group">
                            <InputLabel for="quantity" value="Quantity *" />
                            <TextInput id="quantity" v-model="form.quantity" type="number" min="0" required />
                            <InputError :message="form.errors.quantity" />
                        </div>
                    </div>

                    <div class="form-group">
                        <InputLabel for="category" value="Category" />
                        <select id="category" v-model="form.category" class="form-select">
                            <option value="">— Select category —</option>
                            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                        </select>
                        <InputError :message="form.errors.category" />
                    </div>

                    <div class="form-group">
                        <InputLabel for="image" value="Product Image" />
                        <input id="image" type="file" accept="image/*" @change="handleImage" class="form-file-input" />
                        <InputError :message="form.errors.image" />
                    </div>

                    <div class="form-actions">
                        <Link :href="route('products.index')" class="btn-cancel">Cancel</Link>
                        <button type="submit" class="btn-primary" :disabled="form.processing">
                            {{ form.processing ? 'Saving…' : 'Save Product' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
