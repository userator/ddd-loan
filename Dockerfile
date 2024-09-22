FROM php:8.2-cli-alpine3.20
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
RUN apk update
RUN apk add bash
#RUN apk add php82-sodium
#RUN apk add php82-zip
#ENV COMPOSER_ALLOW_SUPERUSER=1
#ENV COMPOSER_MEMORY_LIMIT=-1
WORKDIR /app/
CMD ["php", "-S", "0.0.0.0:80"]
