FROM php:8.0-apache

# Installeer benodigde PHP extensies
RUN docker-php-ext-install pdo

# Werk de package lijst bij en installeer benodigdheden
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Configureer Apache voor de public directory
RUN a2enmod rewrite
COPY ./docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Maak werkdirectories aan
RUN mkdir -p /var/www/html/data /var/www/html/uploads/cv

# Zet de applicatie over naar de container
COPY . /var/www/html/

# Stel de juiste permissies in
RUN chown -R www-data:www-data /var/www/html/data /var/www/html/uploads
RUN chmod -R 755 /var/www/html/uploads

# Genereer testdata bij opstarten
RUN chmod +x /var/www/html/docker/entrypoint.sh
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]

# Stel de werkdirectory in
WORKDIR /var/www/html

# Poort beschikbaar maken voor Apache
EXPOSE 80

# Start Apache Webserver
CMD ["apache2-foreground"] 