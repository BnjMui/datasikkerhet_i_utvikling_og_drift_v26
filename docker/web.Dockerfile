FROM php:apache
RUN docker-php-ext-install pdo_mysql
ARG PHP_INI_DIR=/usr/local/etc/php
RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY ./server_config/php_config/no-error-display.ini $PHP_INI_DIR/conf.d/
