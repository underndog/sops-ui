FROM php:8.3.4-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache mod_rewrite
RUN apk add --no-cache apache2 apache2-proxy apache2-ssl apache2-proxy_http \
    && sed -i 's/^#\(Include .*mod_rewrite.so\)/\1/' /etc/apache2/httpd.conf

# Set document root and update Apache configuration
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/httpd.conf

# Copy application code
COPY . /var/www/html

# Install Composer and dependencies
RUN apk add --no-cache composer \
    && composer install --no-dev --optimize-autoloader

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy .env.example to .env and generate application key
RUN cp /var/www/html/.env.example /var/www/html/.env \
    && php artisan key:generate

EXPOSE 80
CMD ["php-fpm"]
