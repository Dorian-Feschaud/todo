test-reset:
	docker exec todo_project-php-1 php bin/console doctrine:database:drop --env=test --force --if-exists
	docker exec todo_project-php-1 php bin/console doctrine:database:create --env=test --if-not-exists
	docker exec todo_project-php-1 php bin/console doctrine:migrations:migrate --env=test --no-interaction
	docker exec todo_project-php-1 php bin/console doctrine:fixtures:load --env=test --no-interaction
	docker exec todo_project-php-1 ./vendor/bin/phpunit