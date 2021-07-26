# site_e_commerce_Symfony

after cloning project 

##Step 1
###run : ```composer install``` 
=> If you have an error with your php version, update it in composer.json 

##Step 2
update .env for database connection with your username and password
run: `php bin/console make/migration` 
and after `php bin/console doctrine:migrations:migrate`
if the last command not working, run `php bin/console doctrine:schema:update --force`

3. 
for fixtures run: `php bin/console doctrine:fixtures:load`
