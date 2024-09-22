FROM php:8.2-cli-alpine3.20
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
RUN apk update
RUN apk add --no-cache linux-headers bash autoconf dpkg-dev dpkg file g++ gcc libc-dev make pkgconf re2c
RUN pecl install xdebug-3.3.2
RUN docker-php-ext-enable xdebug
WORKDIR /app/
CMD ["php", "-S", "0.0.0.0:80"]
