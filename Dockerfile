FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Install Node.js and npm
RUN apk add --no-cache nodejs npm

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# Set permissions
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache

# Configure PHP-FPM to listen on port 9000
RUN echo "listen = 127.0.0.1:9000" > /usr/local/etc/php-fpm.d/zz-docker.conf
RUN echo "listen.allowed_clients = 127.0.0.1" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Override the start script
RUN echo '#!/bin/sh\n\
php-fpm -D\n\
sleep 3\n\
nginx -g "daemon off;"' > /start-custom.sh && chmod +x /start-custom.sh

EXPOSE 80

CMD ["/start-custom.sh"]
