%token T_COMMA ','
%token T_ARROW '->'
%token T_ARRAY 'array'
%token T_DOUBLE_ARROW '=>'
%token T_ELLIPSIS '\.\.\.'
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
%token T_INTEGER_LITERAL '[1-9][0-9]*'
%token T_STRING_LITERAL '\'.*?\'|".*?"'
%token T_IDENTIFIER '[a-z_][a-z0-9_]*'

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
  | invocation { $$ = $1; }
  | method_invocation { $$ = $1; }
  | null_literal { $$ = $1; }
  | boolean_literal { $$ = $1; }
  | integer_literal { $$ = $1; }
  | float_literal { $$ = $1; }
  | string_literal { $$ = $1; }
  | array_literal { $$ = $1; }
;

identifier:
    T_IDENTIFIER { $$ = new Identifier($1); }
;

invocation:
    identifier T_LEFT_PAREN arguments T_RIGHT_PAREN { $$ = new Invocation($1, $3); }
;

method_invocation:
    expression T_ARROW invocation { $$ = new MethodInvocation($1, $3); }
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
    expression { $$ = $1; }
  | ellipsis { $$ = $1; }
;

ellipsis:
    T_ELLIPSIS { $$ = new Ellipsis(); }
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

array_literal:
    T_ARRAY T_LEFT_PAREN array_elements T_RIGHT_PAREN { $$ = new ArrayLiteral(false, $3); }
  | T_LEFT_BRACKET array_elements T_RIGHT_BRACKET { $$ = new ArrayLiteral(true, $2); }
;

array_elements:
    /* empty */ { $$ = []; }
  | non_empty_array_elements { $$ = $1; }
;

non_empty_array_elements:
    array_element { $$ = [$1]; }
  | array_element T_COMMA non_empty_array_elements { $$ = array_merge([$1], $3); }
;

array_element:
    expression { $$ = $1; }
  | ellipsis { $$ = $1; }
  | expression T_DOUBLE_ARROW expression { $$ = new KeyValuePair($1, $3); }
;

%%
