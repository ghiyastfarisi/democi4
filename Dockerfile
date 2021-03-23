FROM php:7.4-apache-buster

WORKDIR /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY . ./

# ci4 required module
RUN apt-get update && apt-get install -y libicu-dev

# composer required module
RUN apt-get update && apt-get install -y git zip unzip zlib1g-dev libzip-dev

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# install php extension
RUN docker-php-ext-install -j$(nproc) mysqli intl zip

# user mod permission
RUN usermod -u 1000 www-data \
	&& chown -R www-data /var/www/html \
	&& chgrp -R www-data /var/www/html \
    && chmod -R 777 /var/www/html/*

# restart apache
RUN a2enmod rewrite

EXPOSE 80