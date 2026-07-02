# RSVP Wedding Website

A Laravel 12 application for managing wedding invitations, RSVPs, and guest lists.

## Features
- Guest and guest‑group management
- Invitation generation with QR codes
- Attendance tracking and check‑in scanner
- Admin dashboard for event settings and wish messages
- Responsive UI built with Blade, Tailwind CSS, and Alpine.js

## Tech Stack
- **PHP 8.2** & Laravel 12
- **MySQL** (or compatible) database
- **Tailwind CSS** for styling
- **Alpine.js** for lightweight interactivity
- **Spatie Laravel‑Permission** for role management
- **Vite** for asset bundling
- **PHPUnit** for testing

## Prerequisites
- PHP 8.2+ with required extensions
- Composer
- Node.js (v18+) & npm
- MySQL database
- XAMPP or another local web server

## Installation
```bash
# Clone the repository
git clone https://github.com/mywardaddy/rsvp-wedding.git
cd rsvp-wedding

# Install PHP dependencies
composer install

# Copy environment file and generate app key
cp .env.example .env
php artisan key:generate

# Install front‑end dependencies
npm install

# Run database migrations and seeders
php artisan migrate --seed
```

## Development
```bash
# Start the Vite dev server
npm run dev

# Serve the Laravel app
php artisan serve
```
Visit `http://127.0.0.1:8000` in your browser.

## Testing
```bash
php artisan test
```

## Deployment
- Ensure the `APP_ENV` is set to `production`.
- Run `npm run build` to compile assets.
- Configure a web server (Apache/Nginx) to point to the `public` directory.
- Set up a cron job for the Laravel scheduler if needed: `* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1`

## Contributing
Feel free to open issues or submit pull requests. Follow the Laravel coding standards and run tests before submitting changes.

---
*This project was bootstrapped with Laravel Breeze and customized for wedding RSVP management.*
