# site_e_commerce_Symfony

after cloning project and create  _.env_ files ,run : 

```composer install``` 

=> If you have an error with your php version, update it in _composer.json_ 

## Step 2

 ```php bin/console make/migration``` 
 
and after

```php bin/console doctrine:migrations:migrate```

if the last command not working, run 

```php bin/console doctrine:schema:update --force```

## Step 3

for fixtures  

```php bin/console doctrine:fixtures:load```
