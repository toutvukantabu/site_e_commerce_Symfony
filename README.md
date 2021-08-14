#  e-commerce Website

after cloning project ,run : 

``` docker-compose build
``` 
and next 

``` docker-compose up
``` 
to start local environnement

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
