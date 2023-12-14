Тестовое задание для ПиццаСофт:                                                                                                                                                                                                               
                                                                                                                                                                                                                                              
Установка:                                                                                                                                                                                                                                    
1. Нужен докер, проект разрабатывался на "Docker version 24.0.5"

$cd ~/dir_of_docker_compose/ &&  docker-compose up                                                                                                                                                                                         

2. Заполнить схему базы данных.                                                                                                                                                                                                               
Нужно зайти по адрессу: http://127.0.0.1:8083                                                                                                                                                                                                       
ввести логин и пароль базы данных(по умалчиванию):                                                                                                                                                                                            
    MYSQL_ROOT_PASSWORD: pizzasoft                                                                                                                                                                                                            
    MYSQL_DATABASE: pizzasoft                                                                                                                                                                                                                 
    MYSQL_USER: pizzasoft                                                                                                                                                                                                                     
    MYSQL_PASSWORD: pizzasoft                                                                                                                                                                                                                 
импортировать схему данных:                                                                                                                                                                                                                   
pizzasoft.sql

3. Устновить компоненты Composer:                                                                                                                                                                                                             
$/docker exec --user root -it dir_of_docker_compose-php-1 /bin/bash                                                                                                                                                                           
root@f450f9a6f434:/var/www/html# curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer update                                                                                      

4. Нужно создать файл настроек:
$vim app/.env
с содержимым:
DB_HOST="db"
DB_NAME="pizzasoft"
DB_USER="pizzasoft"
DB_PASSWORD="pizzasoft"
XAuthKey="qwerty123"

5. Апи будет доступно по адресам начиная с http://127.0.0.1:8001/, например http://127.0.0.1:8001/orders/65792e3d9e3ed/done                                                                                                                   
для тестирования АПИ можно использовать "Postman" или аналоги.                                                                                                                                                                                

6. Документация по АПИ будет изложена позже в md файле и в openapi.yaml