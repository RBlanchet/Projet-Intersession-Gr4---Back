
echo "
 ____________________________________
|                                    |
|        UPDATE / UPGRADE            |
 ____________________________________
"
# UPDATE UPGRADE & INSTALL UNZIP

sudo apt-get update && sudo apt-get upgrade -y

echo "
 ____________________________________
|                                    |
|               PHP                  |
 ____________________________________
"
echo " *** ADD PHP7.2 *** "
sudo add-apt-repository ppa:ondrej/php

echo " *** UPDATE *** "
sudo apt-get update -y

echo " *** INSTALL PHP 7.2 *** "
sudo apt-get install php7.2 -y

echo " *** INSTALL PHP 7.2 PACKAGE  *** "
sudo apt-get install php7.2-zip -y

echo " *** INSTALL APACHE 2 / LIBAPACHE 2 *** "
sudo apt-get install -y apache2 libapache2-mod-php7.2

# INSTALL EXTENSIONS PHP

echo " *** PHP XML *** "
sudo apt-get install php7.2-xml -y

echo " *** INSTALL PHP 7.2 COMMON *** "
sudo apt-get install php7.2-common -y

echo " *** INSTALL PHP 7.2 MBSTRING *** "
sudo apt-get install php7.2-mbstring -y

echo " *** INSTALL PHP OPENSSL *** "
sudo apt-get install openssl -y

echo " *** INSTALL PHP MYSQL *** "
sudo apt-get install php7.2-mysql -y
sudo apt-get install debconf-utils -y > /dev/null
debconf-set-selections <<< "mysql-server mysql-server/root_password password 0000"
debconf-set-selections <<< "mysql-server mysql-server/root_password_again password 0000"
sudo apt-get install mysql-server mysql-client mysql-common -y > /dev/null
echo "UPDATE"
sudo apt-get update
# On active le mode Rewrite
sudo a2enmod rewrite
# Recharge la config d'apache
sudo /etc/init.d/apache2 reload
# Redemarre apache2
sudo service apache2 restart
echo "
 ____________________________________
|                                    |
|              COMPOSER              |
 ____________________________________
"

echo " *** INSTALL ADD COMPOSER  *** "
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

echo " *** COMPOSER SETUP *** "
php composer-setup.php
php -r "unlink('composer-setup.php');"

echo " *** DEPLACE COMPOSER *** "
sudo mv composer.phar /usr/local/bin/composer
echo "UPDATE"
sudo apt-get update
composer update
echo " *** DISPLAY VERSION COMPOSER  *** "
composer -V

sudo sed -i 's/DocumentRoot \/var\/www\/html/ DocumentRoot \/var\/www\/html\/intersession\/web\n<Directory \/var\/www\/html>\nAllowOverride All\nOrder Allow,Deny\nAllow from All\n<\/Directory>/g' 000-default.conf

cd /var/www/html/intersession

echo " *** INSTALL VENDOR *** "
composer install
mysql -uroot -p0000 -e "create database projet_intersession";
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load



