# php terminal tool

[![Build Status](https://travis-ci.org/colin1124x/php-terminal.svg?branch=master)](https://travis-ci.org/colin1124x/php-terminal)

### Flag

cmd.php
```php
use Rde\Flag;

$flag = new Flag;

// 取參照
$str = &$flag->string('str-name', 'default', 'describes...');

// 傳參照
$flag->intVar($int, 'int-name', 0, 'describes...');

// 解析
$args = $flag->parse($argv);

var_dump($args, $str, $int);

```

cli
```sh
./cmd.php --str-name abc --int-name 123 x y z
```

output
```sh
array(3) {
  [0] =>
  string(1) "x"
  [1] =>
  string(1) "y"
  [2] =>
  string(1) "z"
}
string(3) "abc"
int(123)
```
