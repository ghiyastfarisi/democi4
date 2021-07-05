FROM node:12-alpine AS nodebuild

WORKDIR /opt/app

COPY src ./src
COPY package.json ./
COPY webpack.config.js ./

RUN yarn install
RUN yarn run build

FROM php:7.4-apache

WORKDIR /var/www/html

# ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
# RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN apt-get update -y

# ci4 required module
RUN apt-get install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++ \
    sendmail

RUN docker-php-ext-install \
    bz2 \
    intl \
    bcmath \
    opcache \
    calendar \
    pdo_mysql \
    mysqli

# RUN apt-get update && apt-get install -y libicu-dev zip unzip zlib1g-dev libzip-dev
# install php extension
# RUN docker-php-ext-install -j$(nproc) mysqli intl zip

COPY ./infra/conf/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite headers

COPY --from=nodebuild /opt/app ./
COPY . ./

# user mod permission
RUN usermod -u 1000 www-data \
	&& chown -R www-data /var/www/html \
	&& chgrp -R www-data /var/www/html \
    && chmod -R 777 /var/www/html/*

EXPOSE 80