FROM richarvey/nginx-php-fpm:3.1.6

# Copy all files
COPY . .

# Image config
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV APP_ENV production
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr

# ALLOW COMPOSER TO INSTALL DEPENDENCIES
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD php artisan migrate --force && /start.sh
