FROM php:8.2-apache

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

# 2. Copy composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Kopyahon ang tanang files (apil ang imong .pem file kung naa na sa root)
COPY . . 

# 4. Siguruha nga naay 'certs' folder ug sakto ang permissions para sa .pem file
# Usba ang 'ca.pem' sa tinuod nga file name sa imong certificate kung lahi
RUN mkdir -p /var/www/html/storage/certs && \
    cp *.pem /var/www/html/storage/certs/ca.pem || true

# 5. I-set ang Apache document root sa public folder sa Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# 7. I-set ang permissions para sa storage, cache, ug certs
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Nuclear Option: migrate:fresh para ma-overwrite ang existing 'roles' table
CMD php artisan migrate:fresh --force && php artisan db:seed --force && apache2-foreground