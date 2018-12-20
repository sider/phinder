# Sample rule and its description

The following is an example of `phinder.yml` that prints `Do not use var_dump.` when code contains a `var_dump` call.

```yml
- id: sample                    # required, string

  pattern: var_dump(...)        # required, string or list of string

  message: Do not use var_dump. # required, string

  test:                         # optional

    fail:                       # optional, string or list of string
        - var_dump($var);

    pass:                       # optional, string or list of string
        - echo $var;
```

- `id`: The id of the rule

- `pattern`: See [Pattern syntax](./pattern-syntax.md)

- `message`: The message to display when a violation is found

- `test`: See [Test your patterns](./command-line-options.md#test-your-patterns)
