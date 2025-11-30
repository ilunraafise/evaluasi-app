# Base image PHP FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install gd pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js & npm (untuk Vite / frontend)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Set working directory
WORKDIR /var/www/app

# Copy composer files dulu (optimasi cache Docker)
COPY composer.json composer.lock ./

# Install PHP dependencies (tanpa scripts dulu karena artisan belum ada)
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Copy seluruh project
COPY . .

# Jalankan artisan package discovery
RUN php artisan package:discover --ansi

# Build frontend jika ada package.json
RUN if [ -f package.json ]; then \
        npm install && npm run build; \
    fi

# Expose port Laravel
EXPOSE 8080

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8080
