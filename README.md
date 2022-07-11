Développement stellaire avec Symfony 5


## Installer
Si vous venez de télécharger le code, félicitations !!

Pour le faire fonctionner, suivez ces étapes :

1) Créer votre base de données :

```
php bin/console doctrine:database:mffsd
```

2) Migration des entités sur la base de données :

```
 php bin/console make:migration
```
```
 php bin/console doctrine:migrations:migrate
```