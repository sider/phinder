PHPY := grammar.phpy
TMPL := grammar.template
NAME := PatternParser

PatternParser.php: $(PHPY) $(TMPL)
	kmyacc -l -m $(TMPL) -p $(NAME) $(PHPY)
	mv $(PHPY:y=) $(NAME).php
