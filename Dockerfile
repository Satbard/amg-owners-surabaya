FROM php:8.2-apache

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
RUN ln -s ../storage/app/public public/storage

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

# Copy and configure custom entrypoint to run migrations on startup
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
