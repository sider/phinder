DST := ../src/lib/PatternParser

.PHONY:	all
all: tokens parser

.PHONY:	tokens
tokens:
	kmyacc -l -m tokens.template grammar.phpy
	mv grammar.php $(DST)/Tokens.php

.PHONY:	parser
parser:
	kmyacc -l -m parser.template -p PatternParser grammar.phpy
	mv grammar.php $(DST)/PatternParser.php
