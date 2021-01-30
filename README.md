# api.touslesverbes.com

Prérequis :
- PHP8
- Composer 2
- Un serveur MySQL

Installer :
```
git clone https://github.com/pierreminiggio/api.touslesverbes.com
cd api.touslesverbes.com
composer install
cp config_example.php config.php
```

Modifier le fichier config.php pour mettre les identifiants à la db & choisir une clé d'encryption (n'importe quelle string fonctionne)

Jouer les scripts de migrations (les up.sql) dans la base de données

Lancer un serveur web qui pointe vers public/index.php

Pour lancer les scripts d'import de données, les lancer via la commande php, exemple :
```
php scripts/cooljugator.php
```
