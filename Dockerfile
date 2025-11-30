FROM php:8.2-fpm

# Install extensions yang diperlukan Laravel + Excel
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project ke dalam container
WORKDIR /var/www/html
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-interaction --no-scripts

CMD php-fpm
