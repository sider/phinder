# Phinder: PHP code piece finder

Phinder is a tool to find code pieces (technically PHP expressions).
This tool aims mainly at speeding up your code review process, not static bug detection.

## Installation

```bash
# Locally
composer require tomokinakamaru/phinder

# Globally
# (Don't forget to add `$HOME/.composer/vendor/bin` to your PATH!)
composer global require tomokinakamaru/phinder
```

## Use Case

## Pattern Syntax

Any PHP expression is a valid Phinder pattern.
Phinder currently supports two kinds of wildcards:

- `?`: any single expression
- `...`: variable length arguments or array pairs

For example, `foo(?)` means an invocation of `foo` with one argument.
`bar(?, ?, ...)` means an invocation of `bar` with two or more arguments.
More features will be added such as statement search.

## Acknowledgements

Phinder is inspired by [Querly](https://github.com/soutaro/querly/), [ast-grep](https://github.com/azz/ast-grep), and [ASTsearch](https://github.com/takluyver/astsearch).
The implementation is largely depends on [PHP-Parser](https://github.com/nikic/PHP-Parser) and [kmyacc-forked](https://github.com/moriyoshi/kmyacc-forked/).
