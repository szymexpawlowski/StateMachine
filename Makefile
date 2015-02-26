test:
	vendor/bin/phpunit -c phpunit.xml tests

test-with-coverage:
	vendor/bin/phpunit -c phpunit.xml --coverage-html coverage tests

cs:
	vendor/bin/phpcs src