#  e-commerce Website


## prerequisite: 

-> docker and docker-compose installed on your machine, if there is a conflict with your version, you can change it at the version = "" line in the docker-compose.yml file 

. Install docker -> https://docs.docker.com/get-docker/

. Install docker-compose -> https://docs.docker.com/compose/install/

If you want testing stripe payments, add on .env files your API keys access

 -> https://dashboard.stripe.com/test/apikeys

## Project start  

* after cloning , open the terminal at the root of the project (~/site_e_commerce_Symfony) : 

First :

``` 
make start
``` 

Second open command on www_symfony_docker container (tap "exit" if you want return on basic terminal): 

``` 
make sh
```

## Project access : 

-> https://localhost:444

If you need help, see "MAKEFILE" or type

``` 
make help
``` 
to see the list of commands in terminal 


## Administration panel access

* Login: admin@gmail.com 

* password: password

## Maildev access

-> http://localhost:8082
