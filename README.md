# site_e_commerce_Symfony

after cloning project and create  _.env_ files ,run : 

``` bash
composer install
``` 

=> If you have an error with your php version, update it in _composer.json_ 

## Step 2

 ```bash
 php bin/console make:migration
 ``` 
 
and after

```bash
php bin/console doctrine:migrations:migrate
```

if the last command not working, run 

```bash
php bin/console doctrine:schema:update --force
```

## Step 3

for fixtures  

```bash
php bin/console doctrine:fixtures:load
```
