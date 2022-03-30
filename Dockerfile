FROM php:7.2-apache

MAINTAINER Jack

WORKDIR /var/www/html

ADD . /var/www/html

RUN apt update && apt install -y libpng-dev zlib1g-dev nodejs npm libzip-dev zip

RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install mbstring zip gd mysqli pdo pdo_mysql opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer install

RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN composer install

RUN npm install