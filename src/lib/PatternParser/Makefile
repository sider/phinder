.PHONY:	all
all: clean Parser.php

.PHONY:	clean
clean:
	rm -f Parser.php

Parser.php: Parser.phpy Parser.template
	kmyacc -l -m Parser.template Parser.phpy
