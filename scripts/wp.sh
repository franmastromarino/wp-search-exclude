#!/usr/bin/env bash

cd /opt/search-exclude/www/
wp core download
wp core config --dbname=wordpress --dbuser=wp --dbpass=secret --skip-check
wp core install --url=search-exclude.local --title=search-exclude --admin_user=search-exclude --admin_password=secret --admin_email=email@search-exclude.local

wp plugin activate search-exclude
