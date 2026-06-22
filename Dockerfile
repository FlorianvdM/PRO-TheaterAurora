FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/assets

# docker commands:

#       docker compose up -d (start de container om de website te openen op poort http//:localhost:8080)

#       docker compose down (stopt de container en de website)

#       docker compose stop db (stopt de database om unhappy scenario te simuleren)

#       docker compose start db (start de database)