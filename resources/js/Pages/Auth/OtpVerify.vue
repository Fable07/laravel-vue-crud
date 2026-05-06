<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

const page = usePage();

const verifyForm = useForm({ code: '' });
const sendForm = useForm({});

function sendOtp() {
    sendForm.post(route('otp.send'));
}

function verify() {
    verifyForm.post(route('otp.verify'));
}
</script>

<template>
    <GuestLayout>
        <Head title="OTP Verification" />

        <div class="otp-header">
            <div class="otp-icon-wrap">
                <svg class="otp-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="otp-title">Two-Factor Verification</h2>
            <p class="otp-subtitle">Enter the 6-digit OTP sent to your email.</p>
        </div>

        <div v-if="page.props.flash?.status" class="otp-status">
            {{ page.props.flash.status }}
        </div>

        <form @submit.prevent="verify" class="auth-form">
            <div class="form-group">
                <InputLabel for="code" value="OTP Code" />
                <TextInput
                    id="code"
                    v-model="verifyForm.code"
                    type="text"
                    maxlength="6"
                    inputmode="numeric"
                    autocomplete="one-time-code"
                    class="otp-input"
                    placeholder="——————"
                    autofocus
                    required
                />
                <InputError :message="verifyForm.errors.code" />
            </div>

            <button type="submit" class="btn-primary btn-primary--full" :disabled="verifyForm.processing">
                {{ verifyForm.processing ? 'Verifying…' : 'Verify OTP' }}
            </button>
        </form>

        <div class="otp-resend">
            <p class="otp-resend__text">Didn't receive a code?</p>
            <button class="otp-resend__btn" @click="sendOtp" :disabled="sendForm.processing">
                {{ sendForm.processing ? 'Sending…' : 'Send OTP to my email' }}
            </button>
        </div>
    </GuestLayout>
</template>
