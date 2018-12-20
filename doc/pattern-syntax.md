# Pattern syntax

Any PHP expression is a valid Phinder pattern. Phinder provides two kinds of wildcards:

- `_`: any single expression

- `...`: variable length arguments or array pairs

For example, `foo(_)` means an invocation of `foo` with one argument. `bar(_, _, ...)` means an invocation of `bar` with two or more arguments.

Although Phinder currently provides only the two wildcards `_` and `...`, [your suggestion](https://github.com/sider/phinder/issues/new) for better *phinding* is more than welcome!
