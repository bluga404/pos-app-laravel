sudo docker pull mysql:latest
sudo docker run -d --name mysql-posApp -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=posApp_db -p 3306:3306 mysql
sudo docker ps 
sudo docker logs mysql-posApp (ready for connections)
sudo docker exec -it mysql-posApp mysql -u root -p

Commands:
php artisan test --filter={function_name}
php artisan make:test {TestName}
php artisan make:model {ModelName} -cms
php artisan make:component {ComponentName}
php artisan make:view {ViewName}