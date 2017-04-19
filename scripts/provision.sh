#!/usr/bin/env bash
apt-get -y update

# Install common tools
apt-get install -y make g++ git curl vim libcairo2-dev libav-tools nfs-common portmap

# PHP 7
add-apt-repository ppa:ondrej/php -y
apt-get -y update
apt-get -y install php7.1-fpm php7.1-cli php7.1-common
apt-get -y install php7.1-mysql php7.1-curl php-xdebug php7.1-mbstring php7.1-zip php7.1-json php7.1-dom
apt-get -y install phantomjs

# PHPBrew
# https://github.com/phpbrew/phpbrew/wiki/Requirement
#apt-get build-dep php5
#apt-get install -y php5 php5-dev php-pear autoconf automake curl libcurl3-openssl-dev build-essential libxslt1-dev re2c libxml2 libxml2-dev php5-cli bison libbz2-dev libreadline-dev
#apt-get install -y libfreetype6 libfreetype6-dev libpng12-0 libpng12-dev libjpeg-dev libjpeg8-dev libjpeg8  libgd-dev libgd3 libxpm4 libltdl7 libltdl-dev
#apt-get install -y libssl-dev openssl
#apt-get install -y gettext libgettextpo-dev libgettextpo0
#apt-get install -y libicu-dev
#apt-get install -y libmhash-dev libmhash2
#apt-get install -y libmcrypt-dev libmcrypt4

# DB
# MariaDB 10.1 Stable
apt-get -y install software-properties-common
apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xcbcb082a1bb943db
add-apt-repository 'deb [arch=amd64,i386,ppc64el] http://ftp.heanet.ie/mirrors/mariadb/repo/10.1/ubuntu trusty main'

apt-get -y update

DEBIAN_FRONTEND=noninteractive apt-get -y install mariadb-server libmariadbclient-dev libssl-dev
mysql -uroot -e 'SELECT CURDATE();' || /usr/bin/mysqladmin -u root password ''
mysql -u root -e "CREATE DATABASE IF NOT EXISTS wordpress CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci";
mysql -u root -e "CREATE USER IF NOT EXISTS 'wp'@'localhost' IDENTIFIED BY 'secret';"
mysql -u root -e "GRANT ALL PRIVILEGES ON wordpress.* to 'wp'@'localhost' IDENTIFIED BY 'secret';"

# Nginx
apt-get -y install nginx
sudo rm -f /etc/nginx/sites-enabled/default
#sudo ln -sfn /home/codeking/codeking/config/nginx.conf /etc/nginx/sites-enabled/codeking.conf

#sudo ln -sfn /home/codeking/codeking/config/php.ini /etc/php/7.0/cli/conf.d/codeking.ini
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#curl -L -O https://github.com/phpbrew/phpbrew/raw/master/phpbrew
#chmod +x phpbrew
#mv phpbrew /usr/local/bin/phpbrew
#
#mkdir -p /opt/phpbrew
#phpbrew init --root=/opt/phpbrew
#phpbrew update
#
#phpbrew -d install 7.1.2
#phpbrew fpm start
#
#echo "source ~/.phpbrew/bashrc" >> ~/.bashrc

# Autostart php-fpm
sudo update-rc.d php7.1-fpm defaults

# http://wp-cli.org/
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

# TODO: install tab completion http://wp-cli.org/#tab-completions
mkdir -p /opt/search-exclude/www
