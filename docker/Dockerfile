ARG WORDPRESS_VERSION

FROM wordpress:${WORDPRESS_VERSION:-php7.4}

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    vim msmtp

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
