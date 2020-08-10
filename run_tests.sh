#!/bin/bash
php ./vendor/bin/phpunit --color --testdox tests
php ./vendor/bin/phpunit tests --coverage-html tests/coverage --coverage-filter src