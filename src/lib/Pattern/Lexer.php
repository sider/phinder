<?php

namespace Phinder\Pattern;

class Lexer
{
    private static $_regex = "/^(\t+|\s+|(?<T_COMMA>,)|(?<T_ARROW>->)|(?<T_DOUBLE_ARROW>=>)|(?<T_ELLIPSIS>\.\.\.)|(?<T_VERTICAL_BAR>\|)|(?<T_AMPERSAND>&)|(?<T_EXCLAMATION>!)|(?<T_LEFT_PAREN>\()|(?<T_RIGHT_PAREN>\))|(?<T_NULL>null)|(?<T_BOOLEAN>:bool:)|(?<T_INTEGER>:int:)|(?<T_FLOAT>:float:)|(?<T_STRING>:string:)|(?<T_BOOLEAN_LITERAL>true|false)|(?<T_FLOAT_LITERAL>[0-9]+\.[0-9]+)|(?<T_INTEGER_LITERAL>[1-9][0-9]*)|(?<T_STRING_LITERAL>'.*?'|\".*?\")|(?<T_IDENTIFIER>[a-z_][a-z0-9_]*)|(?<T_UNSERSCORE>_))/";

    private $_string;

    public function __construct($string)
    {
        $this->_string = $string;
    }

    public function getToken(&$val)
    {
        while (true) {
            if ($this->_string == "") {
                return false;
            }

            $matches = [];
            if (preg_match(self::$_regex, $this->_string, $matches)) {
                if (strlen(trim($matches[0])) === 0) {
                    $this->_string = substr($this->_string, strlen($matches[0]));
                    continue;
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
                if ($matches['T_VERTICAL_BAR'] !== '') {
                    $val = $matches['T_VERTICAL_BAR'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_VERTICAL_BAR;
                }
                if ($matches['T_AMPERSAND'] !== '') {
                    $val = $matches['T_AMPERSAND'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_AMPERSAND;
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
                if ($matches['T_UNSERSCORE'] !== '') {
                    $val = $matches['T_UNSERSCORE'];
                    $this->_string = substr($this->_string, strlen($val));

                    return Parser::T_UNSERSCORE;
                }
            }

            return Parser::YYERRTOK;
        }
    }
}
