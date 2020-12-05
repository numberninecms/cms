<?php

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (isset($_ENV['BOOTSTRAP_RESET_DATABASE']) && $_ENV['BOOTSTRAP_RESET_DATABASE'] == true) {
    echo "Resetting test database...";
    passthru(
        sprintf(
            'php "%s/../bin/console" doctrine:database:drop --env=test --if-exists --force --no-interaction',
            __DIR__
        )
    );
    passthru(
        sprintf(
            'php "%s/../bin/console" doctrine:database:create --env=test --if-not-exists --no-interaction',
            __DIR__
        )
    );
    passthru(
        sprintf(
            'php "%s/../bin/console" doctrine:schema:update --env=test --force --no-interaction',
            __DIR__
        )
    );
    passthru(
        sprintf(
            'rm -rf "%s/../public"',
            __DIR__
        )
    );
    passthru(
        sprintf(
            'php "%s/../bin/console" doctrine:fixtures:load --env=test --no-interaction',
            __DIR__
        )
    );
    passthru(
        sprintf(
            'php "%s/../bin/console" assets:install public --symlink --env=test --no-interaction',
            __DIR__
        )
    );
    passthru('ln -s bundles/numbernine/admin public/admin');
    echo " Done" . PHP_EOL . PHP_EOL;
}

require __DIR__ . '/../vendor/autoload.php';
