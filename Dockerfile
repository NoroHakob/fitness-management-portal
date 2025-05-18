# Base PHP image with Apache
FROM php:8.2-apache

# Enable PostgreSQL extensions
RUN docker-php-ext-install pgsql pdo pdo_pgsql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files into container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Set Apache's DocumentRoot to /var/www/html/public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Optional: fix <Directory> directive too
RUN sed -i 's|<Directory /var/www/html>|<Directory /var/www/html/public>|' /etc/apache2/apache2.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80