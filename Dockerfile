FROM php:8.2-apache

# Extensiones necesarias para este proyecto (PDO + MySQLi)
RUN docker-php-ext-install pdo pdo_mysql mysqli

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
