<?php

namespace Phinder\Pattern;

class Lexer
{
    private static $_regex = "/^(\t+|\s+|(?<T_VARIABLE>\\\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)|(?<T_COMMA>,)|(?<T_ARROW>->)|(?<T_ARRAY>array(?![a-zA-Z0-9_\x80-\xff]))|(?<T_DOUBLE_ARROW>=>)|(?<T_ELLIPSIS>\.\.\.)|(?<T_DOT>\.)|(?<T_TRIPLE_VERTICAL_BAR>\|\|\|)|(?<T_TRIPLE_AMPERSAND>&&&)|(?<T_EXCLAMATION>!)|(?<T_LEFT_PAREN>\()|(?<T_RIGHT_PAREN>\))|(?<T_LEFT_BRACKET>\[)|(?<T_RIGHT_BRACKET>\])|(?<T_NULL>null)|(?<T_BOOLEAN>:bool:)|(?<T_INTEGER>:int:)|(?<T_FLOAT>:float:)|(?<T_STRING>:string:)|(?<T_BOOLEAN_LITERAL>true|false)|(?<T_FLOAT_LITERAL>[0-9]+\.[0-9]+)|(?<T_INTEGER_LITERAL>0|[1-9][0-9]*)|(?<T_STRING_LITERAL>'.*?'|\".*?\")|(?<T_IDENTIFIER>\?|[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)|(?<T_DOUBLE_COLON>::))/";

    private $_string;

    public function __construct($string)
    {
        $this->_string = $string;
    }

    public function getToken(&$val)
    {
        while ($this->_string != "") {
            $matches = [];
            if (preg_match(self::$_regex, $this->_string, $matches)) {
                if (strlen(trim($matches[0])) === 0) {
                    $this->_string = substr($this->_string, strlen($matches[0]));
                    continue;
                }
                if ($matches['T_VARIABLE'] !== '') {
                    $val = $matches['T_VARIABLE'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_VARIABLE;
                }
                if ($matches['T_COMMA'] !== '') {
                    $val = $matches['T_COMMA'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_COMMA;
                }
                if ($matches['T_ARROW'] !== '') {
                    $val = $matches['T_ARROW'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_ARROW;
                }
                if ($matches['T_ARRAY'] !== '') {
                    $val = $matches['T_ARRAY'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_ARRAY;
                }
                if ($matches['T_DOUBLE_ARROW'] !== '') {
                    $val = $matches['T_DOUBLE_ARROW'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_DOUBLE_ARROW;
                }
                if ($matches['T_ELLIPSIS'] !== '') {
                    $val = $matches['T_ELLIPSIS'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_ELLIPSIS;
                }
                if ($matches['T_DOT'] !== '') {
                    $val = $matches['T_DOT'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_DOT;
                }
                if ($matches['T_TRIPLE_VERTICAL_BAR'] !== '') {
                    $val = $matches['T_TRIPLE_VERTICAL_BAR'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_TRIPLE_VERTICAL_BAR;
                }
                if ($matches['T_TRIPLE_AMPERSAND'] !== '') {
                    $val = $matches['T_TRIPLE_AMPERSAND'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_TRIPLE_AMPERSAND;
                }
                if ($matches['T_EXCLAMATION'] !== '') {
                    $val = $matches['T_EXCLAMATION'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_EXCLAMATION;
                }
                if ($matches['T_LEFT_PAREN'] !== '') {
                    $val = $matches['T_LEFT_PAREN'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_LEFT_PAREN;
                }
                if ($matches['T_RIGHT_PAREN'] !== '') {
                    $val = $matches['T_RIGHT_PAREN'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_RIGHT_PAREN;
                }
                if ($matches['T_LEFT_BRACKET'] !== '') {
                    $val = $matches['T_LEFT_BRACKET'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_LEFT_BRACKET;
                }
                if ($matches['T_RIGHT_BRACKET'] !== '') {
                    $val = $matches['T_RIGHT_BRACKET'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_RIGHT_BRACKET;
                }
                if ($matches['T_NULL'] !== '') {
                    $val = $matches['T_NULL'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_NULL;
                }
                if ($matches['T_BOOLEAN'] !== '') {
                    $val = $matches['T_BOOLEAN'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_BOOLEAN;
                }
                if ($matches['T_INTEGER'] !== '') {
                    $val = $matches['T_INTEGER'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_INTEGER;
                }
                if ($matches['T_FLOAT'] !== '') {
                    $val = $matches['T_FLOAT'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_FLOAT;
                }
                if ($matches['T_STRING'] !== '') {
                    $val = $matches['T_STRING'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_STRING;
                }
                if ($matches['T_BOOLEAN_LITERAL'] !== '') {
                    $val = $matches['T_BOOLEAN_LITERAL'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_BOOLEAN_LITERAL;
                }
                if ($matches['T_FLOAT_LITERAL'] !== '') {
                    $val = $matches['T_FLOAT_LITERAL'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_FLOAT_LITERAL;
                }
                if ($matches['T_INTEGER_LITERAL'] !== '') {
                    $val = $matches['T_INTEGER_LITERAL'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_INTEGER_LITERAL;
                }
                if ($matches['T_STRING_LITERAL'] !== '') {
                    $val = $matches['T_STRING_LITERAL'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_STRING_LITERAL;
                }
                if ($matches['T_IDENTIFIER'] !== '') {
                    $val = $matches['T_IDENTIFIER'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_IDENTIFIER;
                }
                if ($matches['T_DOUBLE_COLON'] !== '') {
                    $val = $matches['T_DOUBLE_COLON'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_DOUBLE_COLON;
                }
            }

            return Parser::YYERRTOK;
        }
        return false;
    }
}
