ARG WORDPRESS_VERSION

FROM wordpress:${WORDPRESS_VERSION:-php7.4}

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
