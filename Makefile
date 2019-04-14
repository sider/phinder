.DELETE_ON_ERROR:

# PHONY targets
.PHONY:	all
all: vendor

.PHONY:	clean
clean:
	rm -rf php-yacc
	git clean -dfX .

.PHONY:	test
test: vendor src/lib/Pattern/Parser.php src/lib/Pattern/Lexer.php
	./vendor/bin/phpunit

# Composer-related targets
composer-setup.php:
	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"

composer.phar: composer-setup.php
	php composer-setup.php

vendor: composer.phar
	./composer.phar install

# Parser/Lexer-related targets
php-yacc:
	git clone -n https://github.com/ircmaxell/PHP-Yacc php-yacc
	cd php-yacc && git reset --hard 6e86fc490c2633c78650ad04c0de88a6044bef0b
	cd php-yacc && composer install

src/lib/Pattern/Parser.php: php-yacc grammar/Parser.template grammar/Parser.phpy
	./php-yacc/bin/phpyacc -m grammar/Parser.template grammar/Parser.phpy
	mv grammar/Parser.php src/lib/Pattern/Parser.php

src/lib/Pattern/Lexer.php: grammar/Parser.phpy
	./php-lex/bin/phplex grammar/Parser.phpy > src/lib/Pattern/Lexer.php
