FROM php:7.4-cli
RUN apt-get update && apt-get install -y wget \
    && pecl install xdebug-2.9.6 \
    && docker-php-ext-enable xdebug \
    && wget -O phpunit https://phar.phpunit.de/phpunit-9.phar \
    && chmod +x phpunit \
    && mv phpunit /usr/local/bin/phpunit