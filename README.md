# Configuration du serveur

## Réécriture d'url
* /etc/apache2/site-available/000-default.conf
* rajouter
```
 DocumentRoot /var/www/html/intersession/web
 <Directory /var/www/html>
    AllowOverride All
    Order Allow,Deny
    Allow from All
 </Directory>
```             

## Finalisation 

* apt-get install mysql-server mysql-client mysql-common -y

* Création d'une Bd projet_intersession
