Développement stellaire avec Symfony 5

Créer votre base de données :

php bin/console doctrine:database:mffsd


Migration des entités sur la base de données :

 php bin/console make:migration

 php bin/console doctrine:migrations:migrate