test-reset:
	docker exec todo_project-php-1 php bin/console doctrine:database:drop --env=test --force --if-exists
	docker exec todo_project-php-1 php bin/console doctrine:database:create --env=test --if-not-exists
	docker exec todo_project-php-1 php bin/console doctrine:migrations:migrate --env=test --no-interaction
	docker exec todo_project-php-1 php bin/console doctrine:fixtures:load --env=test --no-interaction
	docker exec todo_project-php-1 ./vendor/bin/phpunit

test-reset-coverage:
	docker exec todo_project-php-1 php bin/console doctrine:database:drop --env=test --force --if-exists
	docker exec todo_project-php-1 php bin/console doctrine:database:create --env=test --if-not-exists
	docker exec todo_project-php-1 php bin/console doctrine:migrations:migrate --env=test --no-interaction
	docker exec todo_project-php-1 php bin/console doctrine:fixtures:load --env=test --no-interaction
	docker exec todo_project-php-1 ./vendor/bin/phpunit --coverage-html var/coverage

migration:
	docker exec todo_project-php-1 php bin/console make:migration
	docker exec todo_project-php-1 php bin/console doctrine:database:drop --force --if-exists
	docker exec todo_project-php-1 php bin/console doctrine:database:create --if-not-exists
	docker exec todo_project-php-1 php bin/console doctrine:migrations:migrate --no-interaction
	docker exec todo_project-php-1 php bin/console doctrine:fixtures:load --no-interaction