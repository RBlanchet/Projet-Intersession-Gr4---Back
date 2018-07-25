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
 </Directory>»
```             

## Finalisation 

* apt-get install mysql-server mysql-client mysql-common -y

* Création d'une Bd projet_intersession


# Importer les fixtures

* S'assurer que la base est bien crée ET VIDE, si ce n'est pas le cas supprimer la base et lancer les commandes suivantes:

```
 cd /var/www/html/intersession
 php bin/console doctrine:database:create
```     

* Bien avoir la dernière mise à jour de la base

```
 php bin/console doctrine:schema:update --force
```   

* Lancer l'importation des fixtures

```
 php bin/console doctrine:fixtures:load
```