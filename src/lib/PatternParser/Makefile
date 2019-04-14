.PHONY:	all
all: clean Parser.php Lexer.php

.PHONY:	clean
clean:
	rm -f Parser.php Lexer.php

Parser.php: Parser.phpy Parser.template
	kmyacc -l -m Parser.template Parser.phpy

Lexer.php: Parser.phpy generateLexer
	./generateLexer > Lexer.php
