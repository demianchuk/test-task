## Installation guide

### Via docker
```
docker-compose up -d
```

1.## Composer
```
docker exec -it php composer install
```
DB param's for db container
```
   database_host: mysqldb
   database_port: null
   database_name: sf
   database_user: root
   database_password: root
```

2.## NPM packages
```
#npm should be installed globally (apt install npm)
npm install --ignore-scripts
```

3.## Clearing cache
```
docker exec -it php bin/console redis:flushall
docker exec -it php bin/console cache:clear -e dev --no-debug
```

4.## Assets
```
docker exec -it php bin/console clicktrans:build:vue
docker exec -it php bin/console fos:js-routing:dump --no-debug
docker exec -it php bin/console assets:install --no-debug
docker exec -it php bin/console assetic:dump --no-debug
```

5.## DB schema
```
$ docker exec -it php bin/console doctrine:schema:update --force
if you are running app first time maybe you should restart docker containers
```

6.## VIRTUAL HOST
```
$ Change virtual host parameter in nginx section, if you need  
currently it is set to 127.0.0.1. It can be domain name or IP address of your
server. Nginx proxy uses 81 port, but you can change it on 80 or any another.
By default settings start web page is available on 127.0.0.1:80
Remeber about symfony permissions: https://symfony.com/doc/3.2/setup/file_permissions.html
```

7.## Running tests
```
$ docker exec -it php vendor/bin/phpunit
```

####### TESTING OAUTH
```
###Uncomment is_Granted part in GitController::getAllStatisticsAction($userName, $repoName)

###To create oauth client (you may use any other grand types according fos auth bundle)
$ docker exec -it php bin/console oauth-server:client:create --redirect-uri="http://127.0.0.1:8000/" --grant-type="password"

###Create user:
curl -X POST -H "Content-Type: application/json" -H "Accept: application/json" -d '{"user":{"username": "zac", "password": "1qaz", "email": "test@test.com"}}' http://127.0.0.1:8000/app_dev.php/users

###Get User Access & Refresh Tokens
Browse to the following URL while Replacing CLIENTID, CLIENTSECRET, USERNAME, & PASSWORD with your values:
http://127.0.0.1:8000/app_dev.php/oauth/v2/token?client_id=__CLIENTID__&client_secret=__CLIENTSECRET__&grant_type=password&username=USERNAME&password=PASSWORD

###See Authorization Failure:
    The following will return a access_denied error:
    curl http://127.0.0.1:8000/app_dev.php/users

See Authorization Success:
    The following will return successful set of users (json).
    curl -H "Authorization: Bearer ACCESS_TOKEN" -H "Accept: application/json" http://127.0.0.1:8000/app_dev.php/users
```

####### Additional useful info

To check code standards
docker exec -it php ./vendor/bin/phpcs --standard=PSR1,PSR2,./ct3-ruleset.xml --extensions=php src/ --colors
```
