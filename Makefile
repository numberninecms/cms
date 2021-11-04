.PHONY: dump-routes phpcsfixer-audit phpcsfixer-fix
dump-routes:
	@bin/console fos:js-routing:dump --format=json --target=assets/routing/routes.json

phpcsfixer-audit:
	@docker compose exec -e PHP_CS_FIXER_IGNORE_ENV=1 php ./vendor/bin/php-cs-fixer fix --diff --dry-run --no-interaction --ansi --verbose --allow-risky=yes

phpcsfixer-fix:
	@docker compose exec -e PHP_CS_FIXER_IGNORE_ENV=1 php ./vendor/bin/php-cs-fixer fix --verbose --allow-risky=yes

test:
	@docker compose exec php composer run-script test
	@docker compose exec php ./vendor/bin/phpunit
