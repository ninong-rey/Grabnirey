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

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

# Set storage permissions
RUN chmod -R 777 /var/www/html/storage
RUN chmod -R 777 /var/www/html/bootstrap/cache

# Configure PHP-FPM to listen on port 9000
RUN echo "listen = 127.0.0.1:9000" > /usr/local/etc/php-fpm.d/zz-docker.conf
RUN echo "listen.allowed_clients = 127.0.0.1" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Create custom startup script with delay
RUN echo '#!/bin/sh\n\
echo "Starting PHP-FPM..."\n\
php-fpm -D\n\
echo "Waiting for PHP-FPM to be ready..."\n\
sleep 5\n\
echo "Testing PHP-FPM connection..."\n\
nc -z 127.0.0.1 9000 || echo "PHP-FPM not ready yet"\n\
echo "Starting Nginx..."\n\
nginx -g "daemon off;"' > /custom-start.sh

RUN chmod +x /custom-start.sh

EXPOSE 80

CMD ["/custom-start.sh"]
