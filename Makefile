composer-require:
	docker exec service-marketplace-php /usr/local/bin/composer require -d /var/www/app $(PKG)

composer-require-dev:
	docker exec service-marketplace-php /usr/local/bin/composer require --dev -d /var/www/app $(PKG)

composer-update:
	docker exec service-marketplace-php /usr/local/bin/composer update -d /var/www/app --prefer-dist
	docker exec service-marketplace-php /usr/local/bin/composer dump-autoload -d /var/www/app -o

docker-rebuild:
	docker compose stop
	docker compose build
	docker compose up -d --remove-orphans

phpcs:
	docker exec -w /var/www/app service-marketplace-php /usr/local/bin/php vendor/bin/php-cs-fixer fix --dry-run --diff

phpcs-fix:
	docker exec -w /var/www/app service-marketplace-php /usr/local/bin/php vendor/bin/php-cs-fixer fix

phpstan:
	docker exec -w /var/www/app service-marketplace-php /usr/local/bin/php vendor/bin/phpstan analyse --memory-limit=1G

phpunit:
	docker exec -w /var/www/app service-marketplace-php /usr/local/bin/php vendor/bin/phpunit

phpunit-coverage:
	docker exec -w /var/www/app -e XDEBUG_MODE=coverage service-marketplace-php /usr/local/bin/php vendor/bin/phpunit --coverage-text --coverage-html var/log/coverage

phpstan-baseline:
	docker exec -w /var/www/app service-marketplace-php /usr/local/bin/php vendor/bin/phpstan analyse --memory-limit=1G --generate-baseline

reinstall-demo-data:
	docker exec service-marketplace-php /usr/local/bin/php /var/www/app/src/yii.php migrate/down all --interactive=0
	docker exec service-marketplace-php /usr/local/bin/php /var/www/app/src/yii.php migrate/up --interactive=0
	docker exec service-marketplace-php /usr/local/bin/php /var/www/app/src/yii.php install-demo-data

yii-migrate-down:
	docker exec service-marketplace-php /usr/local/bin/php /var/www/app/src/yii.php migrate/down --interactive=0

yii-migrate-down-all:
	docker exec service-marketplace-php /usr/local/bin/php /var/www/app/src/yii.php migrate/down all --interactive=0

yii-migrate-up:
	docker exec service-marketplace-php /usr/local/bin/php /var/www/app/src/yii.php migrate/up --interactive=0