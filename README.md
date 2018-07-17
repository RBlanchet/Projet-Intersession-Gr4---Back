# Configuration du serveur

## Réécriture d'url
/etc/apache2/site-available/000-default.conf
rajouter «         
	DocumentRoot /var/www/html/intersession/web
        <Directory /var/www/html>
                AllowOverride All
                Order Allow,Deny
                Allow from All
        </Directory>»
        
        
## Vérification
Vérifier que le app_dev.php "/var/www/html/projet/web/app_dev.php"
Soit bien commenter 
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'], true) || PHP_SAPI === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

## Finalisation 

cd /var/www/html/intersession 

composer install

apt-get install mysql-server mysql-client mysql-common -y

Création d'une Bd projet_intersession

