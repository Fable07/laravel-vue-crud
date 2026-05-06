<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="app-layout">
        <nav class="app-nav">
            <div class="app-nav__inner">
                <div class="app-nav__bar">
                    <!-- Left: Logo + Nav Links -->
                    <div class="app-nav__left">
                        <Link :href="route('dashboard')" class="app-nav__logo">
                            <svg class="app-nav__logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                            </svg>
                            Inventory
                        </Link>

                        <div class="app-nav__links">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </NavLink>
                            <NavLink :href="route('products.index')" :active="route().current('products.*')">
                                Products
                            </NavLink>
                        </div>
                    </div>

                    <!-- Right: User Dropdown -->
                    <div class="app-nav__right">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button type="button" class="app-nav__user-btn">
                                    {{ $page.props.auth.user.name }}
                                    <svg class="app-nav__user-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Hamburger -->
                    <button class="app-nav__hamburger" @click="showingNavigationDropdown = !showingNavigationDropdown">
                        <svg class="app-nav__hamburger-icon" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path v-if="!showingNavigationDropdown" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-show="showingNavigationDropdown" class="app-nav__mobile-menu">
                <div class="app-nav__mobile-links">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('products.index')" :active="route().current('products.*')">
                        Products
                    </ResponsiveNavLink>
                </div>
                <div class="app-nav__mobile-user">
                    <div class="app-nav__mobile-user-info">
                        <div class="app-nav__mobile-user-name">{{ $page.props.auth.user.name }}</div>
                        <div class="app-nav__mobile-user-email">{{ $page.props.auth.user.email }}</div>
                    </div>
                    <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header v-if="$slots.header" class="page-header">
            <div class="page-header__inner">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>
