FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libzip-dev zip && \
    docker-php-ext-install gd zip pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 🟢 Install Node.js & NPM (ini yg bikin npm jadi available)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set working dir
WORKDIR /var/www/app

# Copy composer files & install dependencies
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-interaction

# Copy app
COPY . .

# 🟢 Build front-end (Vite)
RUN if [ -f package.json ]; then \
        npm install && npm run build; \
    fi

# Expose port
EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080
