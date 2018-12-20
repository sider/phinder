# Command line options

## Run phinder

```
Usage: phinder [options]

Options:
  -p, --php <file>|<dir>   Find pieces in <file> or in all PHP files in <dir>
  -r, --rule <file>|<dir>  Use <file> or all YAML files in <dir> instead of phinder.yml
  -j. --json               Output JSON format
```

## Test your patterns

Phinder provides features for testing your patterns against small code pieces. Suppose that you want to find `var_dump` uses, but you accidentally mistyped the pattern as `ver_dump`. In such cases, Phinder does not tell you any violations even if your code contains hundreds of `var_dump` calls. The testing features that we describe below is to avoid such accidents.

### Test your phinder.yml

```
Usage: phinder test [options]

Options:
  -r, --rule <file>|<dir>  Use <file> or all YAML files in <dir> instead of phinder.yml
  -j. --json               Output JSON format
```

This command checks each pattern in `phinder.yml` matches its fail/success tests. Tests can be written in `phinder.yml` as follows:

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

### Test a pattern quickly

Phinde provides a way to check your patterns without adding tests to `phinder.yml`:

```
Usage: phinder -q <pattern> [options]
   or: phinder --quicktest <pattern> [options]

Options:
  -p, --php <file>|<dir> Find pieces in <file> or in all PHP files in <dir>
  -j, --json             Output JSON format
```

For example, `phinder -q 'in_array(...)' -p sample.php` reports all the calls to `in_array` in `sample.php`.
