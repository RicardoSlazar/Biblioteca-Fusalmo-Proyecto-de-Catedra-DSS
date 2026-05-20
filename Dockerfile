FROM php:8.2-apache

# Extensiones necesarias para este proyecto (PDO + MySQLi)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar módulos de Apache requeridos por el .htaccess
RUN a2enmod rewrite headers deflate expires

# Permitir que el .htaccess sobreescriba la configuración de Apache
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80