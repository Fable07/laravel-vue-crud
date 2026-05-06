<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <p class="auth-info">
            Thanks for signing up! Before getting started, could you verify your
            email address by clicking on the link we just emailed to you? If you
            didn't receive the email, we will gladly send you another.
        </p>

        <p v-if="verificationLinkSent" class="auth-success">
            A new verification link has been sent to the email address you
            provided during registration.
        </p>

        <form @submit.prevent="submit">
            <div class="auth-footer">
                <button type="submit" class="btn-primary" :disabled="form.processing">
                    Resend Verification Email
                </button>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="auth-logout-link"
                >Log Out</Link>
            </div>
        </form>
    </GuestLayout>
</template>
