#!/bin/bash
set -e

mkdir -p /var/www/html/data
mkdir -p /var/www/html/uploads/cv
chown -R www-data:www-data /var/www/html/data /var/www/html/uploads

if [ ! -f /var/www/html/data/database.sqlite ]; then
    echo "Initialiseren van de database..."
    cd /var/www/html
    php setup_database.php
    
    chown www-data:www-data /var/www/html/data/database.sqlite
    echo "Database initialisatie voltooid."
else
    echo "Database bestaat al, overslaan van initialisatie."
fi

exec "$@" 