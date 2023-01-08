<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Dotenv\Dotenv;

!passthru(sprintf('rm -rf "%s/../public/uploads"', __DIR__)) || exit(1);

if (!empty($_ENV['BOOTSTRAP_RESET_DATABASE'])) {
    echo 'Waiting for MySQL to be ready...';
    !passthru("php -r 'set_time_limit(30); for(;;) { if(@fsockopen(\"mysql:\".(3306))) { break; } }'") || exit(1);
    echo 'Resetting test database...';
    !passthru(
        sprintf(
            'php "%s/../bin/console" doctrine:database:drop --env=test --if-exists --force --no-interaction',
            __DIR__
        )
    ) || exit(1);
    !passthru(
        sprintf(
            'php "%s/../bin/console" doctrine:database:create --env=test --if-not-exists --no-interaction',
            __DIR__
        )
    ) || exit(1);
    !passthru(
        sprintf('php "%s/../bin/console" doctrine:schema:update --env=test --force --no-interaction', __DIR__)
    ) || exit(1);
    !passthru(
        sprintf('php "%s/../bin/console" doctrine:fixtures:load --env=test --no-interaction', __DIR__)
    ) || exit(1);
    !passthru(
        sprintf('php "%s/../bin/console" assets:install public --symlink --env=test --no-interaction', __DIR__)
    ) || exit(1);
    echo 'Done' . PHP_EOL . PHP_EOL;
}

require __DIR__ . '/../vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}
