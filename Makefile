.PHONY: dump-routes phpcsfixer-audit phpcsfixer-fix
dump-routes:
	@bin/console fos:js-routing:dump --format=json --target=assets/routing/routes.json

check:
	@docker compose exec php ./vendor/bin/ecs check

fix:
	@docker compose exec php ./vendor/bin/ecs check --fix

test:
	@docker compose exec php composer run-script test
	@docker compose exec php php -dxdebug.mode=off -dmemory_limit=1024M ./vendor/bin/phpunit
