### Pour installer ce code...

```
cd /wamp64/www
git clone https://github.com/gsylvestre/eni-bucket-list.git 
cd eni-bucket-list
composer install
```

Pour créer la base de donnée, vous devez d'abord configurer correcter le fichier .env ou .env.local, puis exécuter :
```
php bin/console doctrine:database:create 
php bin/console doctrine:schema:update --force
```

Puis naviguer vers : http://localhost/eni-bucket-list/public/"# eni-bucket-list" 
