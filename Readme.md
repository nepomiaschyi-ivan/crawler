1. Clone project
2. `docker-compose up -d --build`
3. `docker exec -it crawler-php bash`
4. `composer install`
5. add `crawler.web` to /etc/hosts
6. Parse page command: `bin/console app:parse {url}` `[-p]` - pages `[-d]` - depth
7. look parse results `http://crawler.web:8080`
