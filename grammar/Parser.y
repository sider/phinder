%token T_THIS '\\\$this'
%token T_COMMA ','
%token T_ARROW '->'
%token T_ARRAY 'array(?![a-zA-Z0-9_\x80-\xff])'
%token T_DOUBLE_ARROW '=>'
%token T_ELLIPSIS '\.\.\.'
%token T_DOT '\.'
%token T_TRIPLE_VERTICAL_BAR '\|\|\|'
%token T_TRIPLE_AMPERSAND '&&&'
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
%token T_IDENTIFIER '\?|[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*'

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
  | property_access { $$ = $1; }
  | array_call { $$ = $1; }
  | null_literal { $$ = $1; }
  | boolean_literal { $$ = $1; }
  | integer_literal { $$ = $1; }
  | float_literal { $$ = $1; }
  | string_literal { $$ = $1; }
  | string_concatenation { $$ = $1; }
;

identifier:
    T_IDENTIFIER { $$ = new Identifier($1); }
;

this:
    T_THIS { $$ = new This(); }
;

function_call:
    identifier T_LEFT_PAREN arguments T_RIGHT_PAREN { $$ = new FunctionCall($1, $3); }
;

method_call:
    expression T_ARROW identifier T_LEFT_PAREN arguments T_RIGHT_PAREN { $$ = new MethodCall($1, $3, $5); }
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
    T_BOOLEAN_LITERAL { $$ = new BooleanLiteral($1); }
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

string_concatenation:
  expression T_DOT expression { $$ = new StringConcatenation($1, $3); }
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
  | expression T_DOUBLE_ARROW expression { $$ = new ArrayArgument($3, $1); }
  | T_EXCLAMATION T_LEFT_PAREN expression T_DOUBLE_ARROW expression T_RIGHT_PAREN { $$ = new ArrayArgument($5, $3, true); }
;

%%
