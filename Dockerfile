FROM php:7.3-apache
WORKDIR /var/www/html
COPY . /var/www/html
RUN apt update \
    && apt install -y libpng-dev zlib1g-dev wget \
    && apt-get install libzip-dev -y \
    && docker-php-ext-install mbstring zip gd mysqli opcache
RUN pecl install mailparse \
    && docker-php-ext-enable mailparse \
    && pecl install xdebug-3.1.5 \
    && docker-php-ext-enable xdebug
RUN apt-get install libssh2-1 libssh2-1-dev -y
RUN pecl install ssh2
RUN docker-php-ext-enable ssh2
ENV XDEBUG_MODE=coverage
# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer update
RUN composer install
RUN a2enmod rewrite
