# QA Assessment – Laravel Prototype

A minimal Laravel web app for our QA interview. What the app does and what you need to do are explained on the **landing page** once you run it.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# Configure .env with your database (e.g. MySQL for XAMPP)
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

Then open the app in your browser, **log in** with:

- **Email:** `test@example.com`  
- **Password:** `password`

and find the bugs.
