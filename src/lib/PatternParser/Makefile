.PHONY:	all
all: clean PatternParser.php

.PHONY:	clean
clean:
	rm -f PatternParser.php

PatternParser.php: PatternParser.phpy PatternParser.template
	kmyacc -l -m PatternParser.template PatternParser.phpy
