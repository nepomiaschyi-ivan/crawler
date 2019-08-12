1. Clone project
2. `docker-compose up -d --build`
3. `docker exec -it crawler-php bash`
4. `composer install`
5. `bin/console dic:mig:mig`
6. add `crawler.web` to /etc/hosts
7. Parse page command: `bin/console app:parse {url}` `[-p]` - pages `[-d]` - depth
8. look parse results `http://crawler.web:8080`
