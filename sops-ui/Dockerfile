FROM alpine:3.11

LABEL Maintainer="Jabar Digital Service <digital.service@jabarprov.go.id>" \
      Description="Lightweight container with Nginx 1.16 & PHP-FPM 8.0 based on Alpine Linux (forked from trafex/alpine-nginx-php7)."

ADD https://dl-cdn.alpinelinux.org/alpine/v3.11/main/x86_64/APKINDEX.tar.gz /etc/apk/keys/

# make sure you can use HTTPS
RUN apk --update add ca-certificates

# Update repository URLs for PHP 8
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v3.11/community" >> /etc/apk/repositories && \
    echo "https://dl-cdn.alpinelinux.org/alpine/v3.11/main" >> /etc/apk/repositories

# Install packages
RUN apk --no-cache add php8 php8-fpm php8-opcache php8-openssl php8-curl \
    nginx supervisor curl

# Create a symbolic link for PHP 8
RUN ln -s /usr/bin/php8 /usr/bin/php

# Configure nginx
COPY config/nginx.conf /etc/nginx/nginx.conf

# Remove default server definition
RUN rm /etc/nginx/conf.d/default.conf

# Configure PHP-FPM
COPY config/fpm-pool.conf /etc/php8/php-fpm.d/www.conf
COPY config/php.ini /etc/php8/conf.d/custom.ini

# Configure supervisord
COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup document root
RUN mkdir -p /var/www/html

# Make sure files/folders needed by the processes are accessible when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html
COPY --chown=nobody src/ /var/www/html/

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up & running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping
