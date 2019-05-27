%token T_VARIABLE '\\$[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*'
%token T_COMMA ','
%token T_ARROW '->'
%token T_ARRAY 'array(?![a-zA-Z0-9_\x80-\xff])'
%token T_SPACESHIP '<=>'
%token T_DOUBLE_ARROW_RIGHT '=>'
%token T_DOUBLE_ARROW_LEFT '<='
%token T_ELLIPSIS '\.\.\.'
%token T_DOT '\.'
%token T_TRIPLE_VERTICAL_BAR '\|\|\|'
%token T_DOUBLE_VERTICAL_BAR '\|\|'
%token T_VERTICAL_BAR '\|'
%token T_TRIPLE_AMPERSAND '&&&'
%token T_DOUBLE_AMPERSAND '&&'
%token T_AMPERSAND '&'
%token T_EXCLAMATION_DOUBLE_EQUAL '!=='
%token T_EXCLAMATION_EQUAL '!='
%token T_EXCLAMATION '!'
%token T_LEFT_PAREN '\('
%token T_RIGHT_PAREN '\)'
%token T_LEFT_BRACKET '\['
%token T_RIGHT_BRACKET '\]'
%token T_NULL 'null'
%token T_BOOLEAN ':bool:'
%token T_INTEGER ':int:'
%token T_FLOAT ':float:'
%token T_STRING ':string:'
%token T_BOOLEAN_LITERAL 'true|false'
%token T_FLOAT_LITERAL '[0-9]+\.[0-9]+'
%token T_INTEGER_LITERAL '0|[1-9][0-9]*'
%token T_STRING_LITERAL '\'.*?\'|".*?"'
%token T_AND 'and'
%token T_OR 'or'
%token T_XOR 'xor'
%token T_DOUBLE_QUESTION '\?\?'
%token T_IDENTIFIER '\?|[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*'
%token T_DOUBLE_COLON '::'
%token T_BACKSLASH '\\\\\\'
%token T_CARET '\^'
%token T_SLASH '/'
%token T_MINUS '\-'
%token T_PLUS '\+'
%token T_PERCENT '%'
%token T_DOUBLE_ASTERISK '\*\*'
%token T_ASTERISK '\*'
%token T_TRIPLE_EQUAL '==='
%token T_DOUBLE_EQUAL '=='
%token T_DOUBLE_RIGHT_TBRACKET '>>'
%token T_RIGHT_TBRACKET_EQUAL '>='
%token T_RIGHT_TBRACKET '>'
%token T_DOUBLE_LEFT_TBRACKET '<<'
%token T_LEFT_TBRACKET '<'

%%

start:
  expression { $$ = $1; }
;

expression:
    term { $$ = $1; }
  | term T_TRIPLE_VERTICAL_BAR expression { $$ = new Disjunction($1, $3); }
;

term:
    factor { $$ = $1; }
  | factor T_TRIPLE_AMPERSAND term { $$ = new Conjunction($1, $3); }
;

factor:
    element { $$ = $1; }
  | T_EXCLAMATION element { $$ = new Negation($2); }
;

element:
    atom { $$ = $1; }
  | T_LEFT_PAREN expression T_RIGHT_PAREN { $$ = $2; }
;

atom:
    identifier { $$ = $1; }
  | this { $$ = $1; }
  | function_call { $$ = $1; }
  | method_call { $$ = $1; }
  | static_method_call { $$ = $1; }
  | property_access { $$ = $1; }
  | array_call { $$ = $1; }
  | null_literal { $$ = $1; }
  | boolean_literal { $$ = $1; }
  | integer_literal { $$ = $1; }
  | float_literal { $$ = $1; }
  | string_literal { $$ = $1; }
  | binary_operation { $$ = $1; }
;

identifier:
    qualified_identifier { $$ = new Identifier(false, $1); }
  | fully_qualified_identifier { $$ = new Identifier(true, $1); }
;

qualified_identifier:
    T_IDENTIFIER { $$ = [$1]; }
  | T_IDENTIFIER T_BACKSLASH qualified_identifier { $$ = array_merge([$1], $3); }
;

fully_qualified_identifier:
    T_BACKSLASH qualified_identifier { $$ = $2; }
;

this:
    T_VARIABLE { $$ = new Variable($1); }
;

function_call:
    identifier T_LEFT_PAREN arguments T_RIGHT_PAREN { $$ = new FunctionCall($1, $3); }
;

method_call:
    expression T_ARROW identifier T_LEFT_PAREN arguments T_RIGHT_PAREN { $$ = new MethodCall($1, $3, $5); }
;

static_method_call:
    expression T_DOUBLE_COLON identifier T_LEFT_PAREN arguments T_RIGHT_PAREN { $$ = new StaticMethodCall($1, $3, $5); }
;

property_access:
    expression T_ARROW identifier { $$ = new PropertyAccess($1, $3); }
;

arguments:
    /* empty */ { $$ = []; }
  | non_empty_arguments { $$ = $1; }
;

non_empty_arguments:
    argument { $$ = [$1]; }
  | argument T_COMMA non_empty_arguments { $$ = array_merge([$1], $3); }
;

argument:
    expression { $$ = new Argument($1); }
  | ellipsis { $$ = $1; }
;

ellipsis:
    T_ELLIPSIS { $$ = Node::ELLIPSIS; }
;

null_literal:
    T_NULL { $$ = new NullLiteral(); }
;

boolean_literal:
    T_BOOLEAN_LITERAL { $$ = new BooleanLiteral($1 === 'true'); }
  | T_BOOLEAN { $$ = new BooleanLiteral(); }
;

integer_literal:
    T_INTEGER_LITERAL { $$ = new IntegerLiteral($1); }
  | T_INTEGER { $$ = new IntegerLiteral(); }
;

float_literal:
    T_FLOAT_LITERAL { $$ = new FloatLiteral($1); }
  | T_FLOAT { $$ = new FloatLiteral(); }
;

string_literal:
    T_STRING_LITERAL { $$ = new StringLiteral($1); }
  | T_STRING { $$ = new StringLiteral(); }
;

array_call:
    T_ARRAY T_LEFT_PAREN array_arguments T_RIGHT_PAREN { $$ = new ArrayCall($3); }
  | T_LEFT_BRACKET array_arguments T_RIGHT_BRACKET { $$ = new ArrayCall($2); }
;

array_arguments:
    /* empty */ { $$ = []; }
  | non_empty_array_arguments { $$ = $1; }
;

non_empty_array_arguments:
    array_argument { $$ = [$1]; }
  | array_argument T_COMMA non_empty_array_arguments { $$ = array_merge([$1], $3); }
;

array_argument:
    expression { $$ = new ArrayArgument($1); }
  | ellipsis { $$ = $1; }
  | expression T_DOUBLE_ARROW_RIGHT expression { $$ = new ArrayArgument($3, $1); }
  | T_EXCLAMATION T_LEFT_PAREN expression T_DOUBLE_ARROW_RIGHT expression T_RIGHT_PAREN { $$ = new ArrayArgument($5, $3, true); }
;

binary_operation:
    expression T_AMPERSAND expression { $$ = new BitwiseAnd($1, $3); }
  | expression T_VERTICAL_BAR expression { $$ = new BitwiseOr($1, $3); }
  | expression T_CARET expression { $$ = new BitwiseXor($1, $3); }
  | expression T_DOUBLE_AMPERSAND expression { $$ = new BooleanAnd($1, $3); }
  | expression T_DOUBLE_VERTICAL_BAR expression { $$ = new BooleanOr($1, $3); }
  | expression T_DOUBLE_QUESTION expression { $$ = new Coalesce($1, $3); }
  | expression T_DOT expression { $$ = new Concat($1, $3); }
  | expression T_SLASH expression { $$ = new Div($1, $3); }
  | expression T_DOUBLE_EQUAL expression { $$ = new Equal($1, $3); }
  | expression T_RIGHT_TBRACKET expression { $$ = new Greater($1, $3); }
  | expression T_RIGHT_TBRACKET_EQUAL expression { $$ = new GreaterOrEqual($1, $3); }
  | expression T_TRIPLE_EQUAL expression { $$ = new Identical($1, $3); }
  | expression T_AND expression { $$ = new LogicalAnd($1, $3); }
  | expression T_OR expression { $$ = new LogicalOr($1, $3); }
  | expression T_XOR expression { $$ = new LogicalXor($1, $3); }
  | expression T_MINUS expression { $$ = new Minus($1, $3); }
  | expression T_PERCENT expression { $$ = new Mod($1, $3); }
  | expression T_ASTERISK expression { $$ = new Mul($1, $3); }
  | expression T_EXCLAMATION_EQUAL expression { $$ = new NotEqual($1, $3); }
  | expression T_EXCLAMATION_DOUBLE_EQUAL expression { $$ = new NotIdentical($1, $3); }
  | expression T_PLUS expression { $$ = new Plus($1, $3); }
  | expression T_DOUBLE_ASTERISK expression { $$ = new Pow($1, $3); }
  | expression T_DOUBLE_LEFT_TBRACKET expression { $$ = new ShiftLeft($1, $3); }
  | expression T_DOUBLE_RIGHT_TBRACKET expression { $$ = new ShiftRight($1, $3); }
  | expression T_LEFT_TBRACKET expression { $$ = new Smaller($1, $3); }
  | expression T_DOUBLE_ARROW_LEFT expression { $$ = new SmallerOrEqual($1, $3); }
  | expression T_SPACESHIP expression { $$ = new Spaceship($1, $3); }
;

%%
