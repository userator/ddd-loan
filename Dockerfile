FROM php:8.2-cli-alpine3.20
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
RUN apk update
RUN apk add bash
WORKDIR /app/
CMD ["php", "-S", "0.0.0.0:80"]
