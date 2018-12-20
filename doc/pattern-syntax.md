# Pattern syntax

Any PHP expression is a valid Phinder pattern. Phinder currently supports two kinds of wildcards:

- `_`: any single expression
- `...`: variable length arguments or array pairs

For example, `foo(_)` means an invocation of `foo` with one argument. `bar(_, _, ...)` means an invocation of `bar` with two or more arguments. More features will be added such as statement search.
