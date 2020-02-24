# SocialMedia-Management-Platform
Projet Annuel 2 ASI Dev 3ème année



- Create .env and copy .env.dist on .env
- docker-compose up --build -d
- docker-compose exec web composer install
- docker-compose exec web bin/console doctrine:schema:update --force
