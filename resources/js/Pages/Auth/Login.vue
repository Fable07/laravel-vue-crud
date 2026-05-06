<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="auth-heading">
            <h1 class="auth-heading__title">Welcome back</h1>
            <p class="auth-heading__subtitle">Sign in to your account</p>
        </div>

        <div v-if="status" class="auth-status">{{ status }}</div>

        <form @submit.prevent="submit" class="auth-form">
            <div class="form-group">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" v-model="form.email" required autofocus autocomplete="username" />
                <InputError :message="form.errors.email" />
            </div>

            <div class="form-group">
                <InputLabel for="password" value="Password" />
                <TextInput id="password" type="password" v-model="form.password" required autocomplete="current-password" />
                <InputError :message="form.errors.password" />
            </div>

            <label class="auth-remember">
                <Checkbox name="remember" v-model:checked="form.remember" class="form-checkbox" />
                <span class="auth-remember__text">Remember me</span>
            </label>

            <div class="auth-footer">
                <Link v-if="canResetPassword" :href="route('password.request')" class="auth-link">
                    Forgot your password?
                </Link>
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    {{ form.processing ? 'Signing in…' : 'Sign in' }}
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
