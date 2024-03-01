FROM php:7.2-apache
WORKDIR /var/www/html

ADD . /var/www/html
RUN chmod -R 777 /var/www/html/storage/logs

RUN apt update \
&& apt install -y libpng-dev zlib1g-dev libzip-dev zip \
&& curl -s https://deb.nodesource.com/setup_16.x | bash - \
&& apt install nodejs -y \
&& npm install yarn -g \
&& docker-php-ext-configure zip --with-libzip \
&& docker-php-ext-install mbstring zip gd mysqli pdo pdo_mysql opcache \
&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \ 
&& a2enmod rewrite 
# && composer install \

# RUN curl -s https://deb.nodesource.com/setup_16.x | bash -
# RUN apt install nodejs -y
# RUN npm install yarn -g

# RUN docker-php-ext-configure zip --with-libzip
# RUN docker-php-ext-install mbstring zip gd mysqli pdo pdo_mysql opcache

# Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer install

# RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN composer install
  
RUN yarn config set network-timeout 9000000
RUN yarn cache clean
RUN yarn
RUN yarn production