FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

# Copy composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# GI-USAB: Gi-copy ang tanang files gikan sa root sa GitHub repo
# Imbes nga 'COPY task-manager/ .', naggamit na ta og '.'
COPY . . 

# I-set ang Apache document root sa public folder sa Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# I-set ang permissions para sa storage ug cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Pag-run sa migrations ug pag-start sa Apache
CMD php artisan migrate --force && php artisan db:seed --force && apache2-foreground