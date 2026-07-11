FROM php:8.2-apache

# Set timezone to Asia/Jakarta (WIB)
ENV TZ=Asia/Jakarta
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    tzdata \
    && ln -snf /usr/share/zoneinfo/Asia/Jakarta /etc/localtime \
    && echo "Asia/Jakarta" > /etc/timezone \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring exif pcntl

# Enable Apache mod_rewrite for Laravel URL routing
RUN a2enmod rewrite

# Copy custom Apache virtual host configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Create .env file and generate APP_KEY using PHP directly (avoids artisan bootstrap issues)
RUN cp .env.docker .env && php -r "file_put_contents('.env', str_replace('APP_KEY=', 'APP_KEY=base64:' . base64_encode(random_bytes(32)), file_get_contents('.env')));"

# Create storage symlink for public uploads
RUN rm -rf public/storage \
    && ln -s /var/www/storage/app/public /var/www/public/storage

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80
