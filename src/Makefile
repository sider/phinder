.PHONY: init update

all: init

init:
	rm -rf grammar
	rm -rf lib/Parser/PatternParser/ParserFactory.php
	cp -R ../vendor/nikic/php-parser/grammar .
	cp ../vendor/nikic/php-parser/lib/PhpParser/ParserFactory.php lib/Parser/PatternParser/
	patch grammar/parser.template < patch/parser.template.patch
	patch grammar/php5.y < patch/php5.y.patch
	patch grammar/php7.y < patch/php7.y.patch
	patch grammar/rebuildParsers.php < patch/rebuildParsers.php.patch
	patch grammar/tokens.template < patch/tokens.template.patch
	patch grammar/tokens.y < patch/tokens.y.patch
	patch lib/Parser/PatternParser/ParserFactory.php < patch/ParserFactory.php.patch
	php grammar/rebuildParsers.php

update:
	diff -u ../vendor/nikic/php-parser/grammar/parser.template grammar/parser.template > patch/parser.template.patch; exit 0
	diff -u ../vendor/nikic/php-parser/grammar/php5.y grammar/php5.y > patch/php5.y.patch; exit 0
	diff -u ../vendor/nikic/php-parser/grammar/php7.y grammar/php7.y > patch/php7.y.patch; exit 0
	diff -u ../vendor/nikic/php-parser/grammar/rebuildParsers.php grammar/rebuildParsers.php > patch/rebuildParsers.php.patch; exit 0
	diff -u ../vendor/nikic/php-parser/grammar/tokens.template grammar/tokens.template > patch/tokens.template.patch; exit 0
	diff -u ../vendor/nikic/php-parser/grammar/tokens.y grammar/tokens.y > patch/tokens.y.patch; exit 0
	diff -u ../vendor/nikic/php-parser/lib/PhpParser/ParserFactory.php lib/Parser/PatternParser/ParserFactory.php > patch/ParserFactory.php.patch; exit 0
