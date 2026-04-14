FROM php:8.3-fpm

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash -

RUN apt-get update \
    && apt-get install -y libpq-dev libpng-dev libzip-dev libxml2-dev libicu-dev zip nodejs mariadb-client \
    && docker-php-ext-install zip pdo_mysql sysvsem gd bcmath soap intl \
    && npm install -g yarn \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/kcalculator
