FROM php:7.3-apache

# MAINTAINER Jack

WORKDIR /var/www/html

COPY . /var/www/html

RUN apt-get update 
RUN apt-get install -y libpng-dev zlib1g-dev libzip-dev zip
RUN docker-php-ext-install mbstring gd mysqli opcache
RUN docker-php-ext-install zip

#RUN pecl install mailparse \
#    && docker-php-ext-enable mailparse \
#    && pecl install xdebug \
#    && docker-php-ext-enable xdebug

#RUN apt-get install libssh2-1 libssh2-1-dev -y
#RUN pecl install ssh2-1.0
#RUN docker-php-ext-enable ssh2

#ENV XDEBUG_MODE=coverage

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 
#&& composer install

RUN a2enmod rewrite
