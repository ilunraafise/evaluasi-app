# Base PHP Image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpq-dev libonig-dev libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set Working Directory
WORKDIR /var/www/html

# Copy Project Files
COPY . .

# Install PHP Dependencies
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# Build frontend (jika pakai Vite)
RUN if [ -f package.json ]; then \
        npm install && npm run build; \
    fi

# Generate key
RUN php artisan key:generate

EXPOSE 8080

# Start server
CMD php artisan serve --host=0.0.0.0 --port=8080
