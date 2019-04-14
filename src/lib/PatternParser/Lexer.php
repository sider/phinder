<?php

namespace Phinder\PatternParser;

class Lexer
{
    private static $_regex = "^(\t+|\s+|(?<ARROW>->)|(?<DOUBLE_ARROW>=>)|(?<ELLIPSIS>\.\.\.)|(?<NULL>null)|(?<BOOLEAN>true|false)|(?<IDENTIFIER>[a-z_][a-z0-9_]*)|(?<FLOAT>[0-9]+\.[0-9]+)|(?<INTEGER>[1-9][0-9]*)|(?<STRING>'.*?'|\".*?\"))";

    private $_string;

    public function __construct($string)
    {
        $this->_string = $string;
    }

    public function getToken(&$val)
    {
        $matches = [];
        if (preg_match(self::$_regex, $string, $matches, PREG_UNMATCHED_AS_NULL)) {
            if ($matches['ARROW'] !== null) {
                $val = $matches['ARROW'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::ARROW;
            }
            if ($matches['DOUBLE_ARROW'] !== null) {
                $val = $matches['DOUBLE_ARROW'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::DOUBLE_ARROW;
            }
            if ($matches['ELLIPSIS'] !== null) {
                $val = $matches['ELLIPSIS'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::ELLIPSIS;
            }
            if ($matches['NULL'] !== null) {
                $val = $matches['NULL'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::NULL;
            }
            if ($matches['BOOLEAN'] !== null) {
                $val = $matches['BOOLEAN'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::BOOLEAN;
            }
            if ($matches['IDENTIFIER'] !== null) {
                $val = $matches['IDENTIFIER'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::IDENTIFIER;
            }
            if ($matches['FLOAT'] !== null) {
                $val = $matches['FLOAT'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::FLOAT;
            }
            if ($matches['INTEGER'] !== null) {
                $val = $matches['INTEGER'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::INTEGER;
            }
            if ($matches['STRING'] !== null) {
                $val = $matches['STRING'];
                $this->_string = substr($this-_string, strlen($val));
                return Parser::STRING;
            }
        }
        return Parser::YYERRTOK;
    }
}
