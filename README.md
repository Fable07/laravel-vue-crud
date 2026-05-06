# Laravel Vue CRUD — Product Inventory

A full-stack web application built with **Laravel 12** (backend) and **Vue 3 + Inertia.js** (frontend).

> Live demo: [laravel-vue-crud-production.up.railway.app](https://laravel-vue-crud-production.up.railway.app)

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Vue 3, Inertia.js |
| Build Tool | Vite |
| CSS | Tailwind CSS + custom plain CSS |
| Deployment | Railway (Docker) |
| Database | MySQL (Railway managed) |

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
- Node.js 20+ and npm
- MySQL (XAMPP, Laragon, or standalone)
- Gmail account with App Password (for OTP emails)

## Local Setup

### 1. Clone and install

```bash
git clone https://github.com/Fable07/laravel-vue-crud.git
cd laravel-vue-crud
composer install
npm install
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your database and mail settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_vue_crud
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_SCHEME=
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_gmail_app_password
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Laravel Vue CRUD"
```

> **Gmail App Password:** Go to Google Account → Security → 2-Step Verification → App Passwords → Generate one and paste it as `MAIL_PASSWORD`.

### 3. Create database

```sql
CREATE DATABASE laravel_vue_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations and link storage

```bash
php artisan migrate
php artisan storage:link
```

### 5. Start dev servers

Open **two terminals**:

```bash
# Terminal 1 — Frontend
npm run dev

# Terminal 2 — Backend
php artisan serve
```

Visit **http://localhost:8000**

## Railway Deployment

This project is configured for one-click deployment on [Railway](https://railway.app) using Docker.

### Steps

1. Fork or push this repo to GitHub
2. In Railway → **New Project** → **Deploy from GitHub repo**
3. Add a **MySQL** database service to the project
4. Set the following environment variables on the app service:

| Variable | Value |
|---|---|
| `APP_KEY` | Output of `php artisan key:generate --show` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | Your Railway public domain (with `https://`) |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQL_HOST}}` |
| `DB_PORT` | `${{MySQL.MYSQL_PORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQL_DATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQL_USER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQL_PASSWORD}}` |
| `MAIL_MAILER` | `smtp` |
| `MAIL_HOST` | `smtp.gmail.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | your Gmail address |
| `MAIL_PASSWORD` | your Gmail App Password |
| `MAIL_FROM_ADDRESS` | your Gmail address |
| `MAIL_FROM_NAME` | `Laravel Vue CRUD` |

5. Railway will build using the [Dockerfile](Dockerfile) and auto-deploy on every push to `main`
6. Migrations run automatically on container startup (`php artisan migrate --force`)

