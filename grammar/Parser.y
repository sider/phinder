%token T_ARROW '->'
%token T_DOUBLE_ARROW '=>'
%token T_ELLIPSIS '\.\.\.'
%token T_VERTICAL_BAR '\|'
%token T_AMPERSAND '&'
%token T_EXCLAMATION '!'
%token T_LEFT_PAREN '\('
%token T_RIGHT_PAREN '\)'
%token T_IDENTIFIER '[a-z_][a-z0-9_]*'
%token T_UNSERSCORE '_'
%token T_COMMA ','
%token T_NULL 'null'
%token T_BOOLEAN 'true|false'
%token T_FLOAT '[0-9]+\.[0-9]+'
%token T_INTEGER '[1-9][0-9]*'
%token T_STRING '\'.*?\'|".*?"'

%%

start:
  expression { $$ = $1; }
;

expression:
    term { $$ = $1; }
  | term T_VERTICAL_BAR expression { $$ = new Disjunction($1, $3); }
;

term:
    factor { $$ = $1; }
  | factor T_AMPERSAND term { $$ = new Conjunction($1, $3); }
;

factor:
    element { $$ = $1; }
  | T_EXCLAMATION element { $$ = new Not($1); }
;

element:
    atom { $$ = $1; }
  | T_LEFT_PAREN expression T_RIGHT_PAREN { $$ = $2; }
;

atom:
    wildcard { $$ = $1; }
  | identifier { $$ = $1; }
  | invocation { $$ = $1; }
  | null_literal { $$ = $1; }
  | boolean_literal { $$ = $1; }
  | integer_literal { $$ = $1; }
  | float_literal { $$ = $1; }
  | string_literal { $$ = $1; }
;

wildcard:
    T_UNSERSCORE { $$ = new Wildcard(); }
;

identifier:
    T_IDENTIFIER { $$ = new Identifier($1); }
;

invocation:
    T_IDENTIFIER T_LEFT_PAREN arguments T_RIGHT_PAREN { $$ = new Invocation($1, $3); }
;

arguments:
    /* empty */ { $$ = new Arguments(); }
  | non_empty_arguments { $$ = $1; }
;

non_empty_arguments:
    argument { $$ = new Arguments($1); }
  | argument T_COMMA non_empty_arguments { $$ = new Arguments($1, $3); }
;

argument:
    expression { $$ = $1; }
  | varlen_wildcard { $$ = $1; }

varlen_wildcard:
    T_ELLIPSIS { $$ = new Wildcard(true); }
;

null_literal:
    T_NULL { $$ = new NullLiteral(); }
;

boolean_literal:
    T_BOOLEAN { $$ = new BooleanLiteral($1); }
;

integer_literal:
    T_INTEGER { $$ = new IntegerLiteral($1); }
;

float_literal:
    T_FLOAT { $$ = new FloatLiteral($1); }
;

string_literal:
    T_STRING { $$ = new StringLiteral($1); }
;

// TODO: array (old-style), array (new-style), method_invocation

%%
