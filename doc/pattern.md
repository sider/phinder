# Pattern syntax

Any PHP expression is a valid Phinder pattern. Phinder provides two kinds of wildcards:

- `_`: any single expression

- `...`: variable length arguments or array pairs

For example, `foo(_)` means an invocation of `foo` with one argument. `bar(_, _, ...)` means an invocation of `bar` with two or more arguments.

## Usage by example

- Find `foo` calls:

    ```
    foo(...)
    ```

- Find `foo` calls with two arguments:

    ```
    foo(_, _)
    ```

- Find `foo` calls with one or more arguments:

    ```
    foo(_, ...)
    ```

- Find `foo` calls whose argument is an array that has a value for `key`:

    ```
    foo(['key' => _, ...])
    ```

- Find `foo` calls whose argument is an array that does not have a value for `key`:

    ```
    foo([!('key' => _), ...])
    ```
