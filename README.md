# Laravel Vue CRUD — Product Inventory

A full-stack web application built with **Laravel 12** (backend) and **Vue 3 + Inertia.js** (frontend).

> This project is being built incrementally. More features will be added with each commit.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Vue 3, Inertia.js |
| Build Tool | Vite |
| CSS | Tailwind CSS + custom plain CSS |

## Progress

- [x] Laravel 12 + Vue 3 + Inertia.js + Vite project initialized
- [x] Tailwind CSS and PostCSS configured
- [x] jsconfig paths, aliases, and editor settings configured
- [x] Users and sessions migration added
- [x] Cache and jobs migration added
- [x] Products migration with soft deletes added
- [x] OTPs migration added
- [x] User, Product, and Otp models added
- [x] Database factories and seeders added
- [x] Authentication — Register, Login, Logout, Password Reset, Email Verification, Confirm Password
- [x] OTP two-factor authentication — send/verify via email, rate limiting, session guard
- [x] Products CRUD — Create, Read, Update, Delete, Soft Delete, Restore, Force Delete, image upload

## Requirements

- PHP 8.2+
- Composer
- Node.js + npm

## Installation

```bash
git clone https://github.com/Fable07/laravel-vue-crud.git
cd laravel-vue-crud
composer install
npm install
cp .env.example .env
php artisan key:generate


npm run dev - for vue
php artisan serve - for laravel
```

