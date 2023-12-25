FROM php:8.3-fpm

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

RUN apt-get update  \
    && apt-get install -y libpq-dev libpng-dev libzip-dev libxml2-dev libicu-dev zip nodejs mariadb-client \
    && docker-php-ext-install zip pdo_mysql sysvsem gd bcmath soap intl \
    && apt-get clean \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN apt-get install -y nodejs

RUN npm install -g yarn

WORKDIR /var/www/kcalculator
