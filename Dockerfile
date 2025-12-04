FROM php:8.2-cli

RUN docker-php-ext-install simplexml

WORKDIR /app

COPY . .

CMD ["php", "broadcast.php"]
