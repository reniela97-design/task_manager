FROM php:8.2-apache

# 1. Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

# 2. Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 3. Kopyahon ang tanang files (Apil ang ca.pem)
COPY . . 

# 4. KINI ANG IMPORTANTE NGA FIX:
# I-siguro nga ang ca.pem moadto sa saktong folder nga gipangita sa imong database.php
RUN mkdir -p /var/www/html/storage/certs && \
    cp ca.pem /var/www/html/storage/certs/ca.pem || true

# 5. I-set ang Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 6. Install dependencies
RUN composer install --no-dev --optimize-autoloader

# 7. I-set ang permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Run migrations and start
CMD php artisan migrate:fresh --seed --force && apache2-foreground