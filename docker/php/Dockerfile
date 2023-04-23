FROM php:7.4-fpm

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

RUN apt-get update  \
    && apt-get install -y  libpq-dev libpng-dev libzip-dev zip nodejs \
    && docker-php-ext-install zip pgsql pdo_pgsql sysvsem gd bcmath \
    && apt-get clean \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN apt-get install -y nodejs

RUN npm install -g yarn

WORKDIR /var/www/kcalculator
