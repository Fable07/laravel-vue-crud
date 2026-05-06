<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <div class="profile-section-header">
            <h2 class="profile-section-title">Profile Information</h2>
            <p class="profile-section-desc">
                Update your account's profile information and email address.
            </p>
        </div>

        <form @submit.prevent="form.patch(route('profile.update'))" class="profile-form">
            <div class="form-group">
                <InputLabel for="name" value="Name" />
                <TextInput id="name" type="text" v-model="form.name" required autofocus autocomplete="name" />
                <InputError :message="form.errors.name" />
            </div>

            <div class="form-group">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" v-model="form.email" required autocomplete="username" />
                <InputError :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="form-group">
                <p class="profile-verify-notice">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="profile-verify-link"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <p v-show="status === 'verification-link-sent'" class="profile-verify-sent">
                    A new verification link has been sent to your email address.
                </p>
            </div>

            <div class="profile-form-actions">
                <button type="submit" class="btn-primary" :disabled="form.processing">Save</button>

                <Transition name="profile-fade">
                    <p v-if="form.recentlySuccessful" class="profile-form-saved">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
