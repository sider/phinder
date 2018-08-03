# Phinder: PHP Code Piece Finder

Phinder is a tool to find code pieces (technically PHP expressions).
This tool aims mainly at speeding up your code review process, not static bug detection.

## Installation

```bash
composer global require tomokinakamaru/phinder
```

You can check your installation by the following command:

```bash
~/.composer/vendor/bin/phinder --help
```

If you have `$HOME/.composer/vendor/bin` in your PATH, you can also check it by the following:

```bash
phinder --help
```

## Command Line Options

### Quick Test

```bash
phinder --quicktest <pattern>
```

**Sample Usage:**

```bash
phinder --quicktest 'in_array(?, ?)'
phinder --quicktest 'var_dump(...)'
```

### JSON Output

```bash
phinder --json
```

**Sample Output:**

```json
{
  "result":[
    {
      "path":"./sample.php",
      "rule":{
        "id":"sample.var_dump",
        "message":"Do not use var_dump!"
      },
      "location":{
        "start":[3, 2],
        "end":[3, 5]
      }
    }
  ],
  "errors":[]
}
```

### Rule Path

```bash
phinder --rule <file>  # Use <file> instead of phinder.yml
phinder --rule <dir>   # Use all yml files in <dir>
```

### PHP Path

```bash
phinder --php <file>  # Find pieces in <file>
phinder --php <dir>   # Find pieces in all php files in <dir>
```

### Help

```bash
phinder --help
```

## Pattern Syntax

Any PHP expression is a valid Phinder pattern.
Phinder currently supports two kinds of wildcards:

- `?`: any single expression
- `...`: variable length arguments or array pairs

For example, `foo(?)` means an invocation of `foo` with one argument.
`bar(?, ?, ...)` means an invocation of `bar` with two or more arguments.
More features will be added such as statement search.

## Contributing

Bug reports and pull requests are welcome on GitHub at [https://github.com/tomokinakamaru/phinder](https://github.com/tomokinakamaru/phinder).

## Acknowledgements

Phinder is inspired by [Querly](https://github.com/soutaro/querly/), [ast-grep](https://github.com/azz/ast-grep), and [ASTsearch](https://github.com/takluyver/astsearch).
The implementation depends largely on [PHP-Parser](https://github.com/nikic/PHP-Parser) and [kmyacc-forked](https://github.com/moriyoshi/kmyacc-forked/).
