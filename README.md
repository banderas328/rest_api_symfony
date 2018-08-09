rest_api
========
Symfony 3.4 rest api example
1) Install symfony by
composer create-project symfony/framework-standard-edition my_project_name
2) Create temp folder and clone this repository into this folder
git clone https://github.com/banderas328/rest_api_symfony.git 
3) Execute "composer update" in root folder of project
4) Setup database credentials in parametrs.yml
5) Execute "php bin/console doctrine:database:create"
6) Execute "php bin/console doctrine:schema:update --force"
7) Execute "php bin/console server:start" to start progress
