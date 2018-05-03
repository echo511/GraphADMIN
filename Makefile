cs:
	- vendor/bin/phpcs --standard=phpcs.source.xml src/

cs-fix:
	- vendor/bin/phpcbf --standard=phpcs.source.xml src/

phpstan:
	- vendor/bin/phpstan analyse -l 5 -c phpstan.neon src/

clean:
	- rm -r temp/cache
