#!/bin/bash


  echo "Waiting for MySQL to be ready..."
  sleep 10


# Install WordPress

echo "Installing WordPress..."
/usr/local/bin/wp core download --path="/var/www/html" --allow-root
/usr/local/bin/wp config create --path="/var/www/html" --dbname="$WORDPRESS_DB_NAME" --dbuser="$WORDPRESS_DB_USER" --dbpass="$WORDPRESS_DB_PASSWORD" --dbhost="$WORDPRESS_DB_HOST" --allow-root
/usr/local/bin/wp core install --path="/var/www/html" --url="$WORDPRESS_URL" --title="$WORDPRESS_TITLE" --admin_user="$WORDPRESS_ADMIN_USER" --admin_password="$WORDPRESS_ADMIN_PASSWORD" --admin_email="$WORDPRESS_ADMIN_EMAIL" --allow-root
echo "WordPress installed!"

# activate all plugins
echo "Activating plugins..."
/usr/local/bin/wp plugin activate --all --path="/var/www/html" --allow-root

# install wp-crontrol
echo "Installing WP Crontrol..."
/usr/local/bin/wp plugin install wp-crontrol --path="/var/www/html" --allow-root --activate

# start apache
echo "Starting Apache..."
exec apache2-foreground