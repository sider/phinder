# Test your patterns

Phinder provides features for testing your patterns against small code pieces. Suppose that you want to find `var_dump` uses, but you accidentally mistyped the pattern as `ver_dump`. In such cases, Phinder does not tell you any violations even if your code contains hundreds of `var_dump` calls. The testing features that we describe below is to avoid such accidents.

## Test your phinder.yml

The command `phinder test` checks each pattern in `phinder.yml` matches its fail/success tests. The tests can be written in `phinder.yml` as follows:

```yml
- id: sample.in_array_without_3rd_param
  pattern: in_array(_, _)
  message: ...
  test:
    fail:
      - in_array(1, $arr)
      - in_array(2, $arr) # PHP code piece that SHOULD be alerted by Phinder
    pass:
      - in_array(3, $arr, true)
      - in_array(4, $arr, false) # PHP code piece that SHOULD NOT be alerted by Phinder
```

## Test a pattern interactively

With the command `phinder console`, you can test your patterns interactively.
