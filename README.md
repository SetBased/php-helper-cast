# Cast

<table>
<thead>
<tr>
<th>Social</th>
<th>Legal</th>
<th>Release</th>
<th>Tests</th>
<th>Code</th>
</tr>
</thead>
<tbody>
<tr>
<td>
<a href="https://gitter.im/SetBased/php-abc?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge"><img src="https://badges.gitter.im/SetBased/php-abc.svg" alt="Gitter"/></a>
</td>
<td>
<a href="https://packagist.org/packages/setbased/helper-cast"><img src="https://poser.pugx.org/setbased/helper-cast/license" alt="License"/></a>
</td>
<td>
<a href="https://packagist.org/packages/setbased/helper-cast"><img src="https://poser.pugx.org/setbased/helper-cast/v/stable" alt="Latest Stable Version"/></a><br/>
</td>
<td>
<a href="https://travis-ci.org/SetBased/php-helper-cast"><img src="https://travis-ci.org/SetBased/php-helper-cast.svg?branch=master" alt="Build Status"/></a><br/>
<a href="https://scrutinizer-ci.com/g/SetBased/php-helper-cast/?branch=master"><img src="https://scrutinizer-ci.com/g/SetBased/php-helper-cast/badges/coverage.png?b=master" alt="Code Coverage"/></a>
</td>
<td>
<a href="https://scrutinizer-ci.com/g/SetBased/php-helper-cast/?branch=master"><img src="https://scrutinizer-ci.com/g/SetBased/php-helper-cast/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"/></a>
</td>
</tr>
</tbody>
</table>

This package contains a strong typed utility class safely testing and casting mixed values to bool, float, int, or
string values.

# Rationale

What is the purpose of this package and why is it necessary?
PHP is a great language, however is has some quirks. One of them is that PHP will convert any string to an integer even
when strict type is set.

The following code will not cause any error or warning (tested with PHP 7.2.12):
```php
<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

echo '1: ', (int)'3.14', PHP_EOL;
echo '2: ', (int)'123abc', PHP_EOL;
echo '3: ', (int)"Ceci n'est pas une pipe.", PHP_EOL;
echo '4: ', (int)'', PHP_EOL;
echo '5: ', (int)null, PHP_EOL;
```
but gives the following output:
```text
1: 3
2: 123
3: 0
4: 0
5: 0
```

This package provides a strong typed utility class for safely testing and casting mixed typed values to Boolean,
float, integer, or string values.

# Manual

The class `SetBased\Abc\Helper\Cast` has the following methods for testing mixed values against primitive data types:

| Method      | Null Value    | Return Type |
| ----------- | ------------- | ----------- |
| isManBool   | returns false | bool        |
| isManFloat  | returns false | bool        |
| isManInt    | returns false | bool        |
| isManString | returns false | bool        |
| isOptBool   | returns true  | bool        |
| isOptFloat  | returns true  | bool        |
| isOptInt    | returns true  | bool        |
| isOptString | returns true  | bool        |

The class `SetBased\Abc\Helper\Cast` has the methods shown int he table below for casting mixed values to a primitive
data type. When a value can not casted safely to a type n exception will be thrown.

| Method      | Null Value          | Return Type  |
| ----------- | ------------------- | ------------ |
| toManBool   | throws an exception | bool         |
| toManFloat  | throws an exception | float        |
| toManInt    | throws an exception | int          |
| toManString | throws an exception | string       |
| toOptBool   | returns null        | bool\|null   |
| toOptFloat  | returns null        | float\|null  |
| toOptInt    | returns null        | int\|null    |
| toOptString | returns null        | string\|null |

Remarks:
 * 'opt' is short for optional:  `null` values are valid. Testing and casting against `null` yields `true` and `null`, respectively.
 * 'man' is short for mandatory: `null` values are not allowed. Testing against `null` yields `false` and casting `null` will throw an exception.

## Sample

Code:
```php
<?php
declare(strict_types=1);

use SetBased\Abc\Helper\Cast;

$value = "Ceci n'est pas une pipe.";
if (Cast::isManInt($value))
{
  echo 'This is not an integer', PHP_EOL;
}
else
{
  echo Cast::toManInt($value), PHP_EOL;
}

echo Cast::toManFloat($value), PHP_EOL;
```

Output:
```
This is not an integer

Exception
```

## Booleans:

Only and only the following values are valid representations of boolean values:
* `false`
 * int(0)
 * string(1) "0"
 * bool(false)
* `true`
 * int(1)
 * string(1) "1"
 * bool(true)

Hence, only these values can be casted safely to booleans and vice versa.


# Installing

```
composer require setbased/helper-cast
```


#  License

This project is licensed under the MIT license.
