# Phinder: PHP Code Piece Finder
[![CircleCI](https://circleci.com/gh/sider/phinder/tree/master.svg?style=svg)](https://circleci.com/gh/sider/phinder/tree/master)
[![Latest Stable Version](https://poser.pugx.org/sider/phinder/v/stable)](https://packagist.org/packages/sider/phinder)
[![Docker Hub](https://img.shields.io/badge/docker-ready-blue.svg)](https://hub.docker.com/r/sider/phinder/)

Phinder is a tool to find code pieces (technically PHP expressions).
This tool aims mainly at speeding up your code review process, not static bug detection.

---

Suppose that your project has the following local rule:

- Specify the 3rd parameter explicitly when calling `in_array` to avoid unexpected comparison results.

Your project code follows this rule if you check it in code review. But what if you forget to check it? What if your project has tens of rules? You probably want machines to do such low-level checking.

Phinder is a command line tool for checking such low-level things automatically. By saving the following yml as `phinder.yml` and running `phinder` from your terminal, Phinder finds the violations for you:

```yml
- id: in_array_without_3rd_param
  pattern: in_array(_, _)
  message: Specify the 3rd parameter explicitly when calling `in_array` to avoid unexpected comparison results.
```

## Installation

Phinder requires PHP >= 7.0. You can install with [Composer](https://getcomposer.org/):

```bash
composer require --dev sider/phinder
vendor/bin/phinder -v
```

You can also use [Docker](https://hub.docker.com/r/sider/phinder/):

```bash
docker run --rm -t -v $(pwd):/workdir sider/phinder
```

## Documentation

- [Sample rule and its description](./doc/rule.md)

- [Pattern syntax](./doc/pattern-syntax.md)

- [Command line options](./doc/command-line-options.md)

## Contributing

Bug reports, feature request, and pull requests are welcome on GitHub at [https://github.com/sider/phinder](https://github.com/sider/phinder).

---

**Acknowledgements**

Phinder is inspired by [Querly](https://github.com/soutaro/querly/), [ast-grep](https://github.com/azz/ast-grep), and [ASTsearch](https://github.com/takluyver/astsearch).
The implementation depends largely on [PHP-Parser](https://github.com/nikic/PHP-Parser) and [kmyacc-forked](https://github.com/moriyoshi/kmyacc-forked/).
