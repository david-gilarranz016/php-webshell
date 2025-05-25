autoload:
	composer dump-autoload

test: autoload
	./vendor/bin/phpunit --bootstrap vendor/autoload.php tests/*

