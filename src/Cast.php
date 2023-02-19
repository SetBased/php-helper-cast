<?php
declare(strict_types=1);

namespace SetBased\Helper;

/**
 * Utility class for casting safely mixed values to bool, float, int, or string.
 */
class Cast
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is not null and can be cast to a boolean.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManBool(mixed $value): bool
  {
    return ($value===false || $value===true || $value===0 || $value===1 || $value==='0' || $value==='1');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is not null and can be cast to a finite float.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManFloat(mixed $value): bool
  {
    switch (gettype($value))
    {
      case 'boolean':
      case 'integer':
        return true;

      case 'double':
        return is_finite($value);

      case 'string':
        // Reject empty strings.
        if ($value==='')
        {
          return false;
        }

        // Reject leading zeros unless they are followed by a decimal point.
        if (strlen($value)>1 && $value[0]==='0' && $value[1]!=='.')
        {
          return false;
        }

        $filtered = filter_var($value,
                               FILTER_SANITIZE_NUMBER_FLOAT,
                               FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC);

        return ($filtered===$value);

      default:
        return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is not null and can be cast to a float including NaN, -INF, and INF.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManFloatInclusive(mixed $value): bool
  {
    switch (gettype($value))
    {
      case 'boolean':
      case 'double':
      case 'integer':
        return true;

      case 'string':
        // Reject empty strings.
        if ($value==='')
        {
          return false;
        }

        // Reject leading zeros unless they are followed by a decimal point
        if (strlen($value)>1 && $value[0]==='0' && $value[1]!=='.')
        {
          return false;
        }

        $filtered = filter_var($value,
                               FILTER_SANITIZE_NUMBER_FLOAT,
                               FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC);

        return ($filtered===$value || in_array(strtoupper($value), ['NAN', 'INF', '-INF'], true));

      default:
        return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is not null and can be cast to an int.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManInt(mixed $value): bool
  {
    switch (gettype($value))
    {
      case 'integer':
      case 'boolean':
        return true;

      case 'double':
        return $value===(float)(int)$value;

      case 'string':
        $casted = (string)(int)$value;

        if ($value!==$casted && $value!==('+'.$casted))
        {
          return false;
        }

        return $value<=PHP_INT_MAX && $value>=PHP_INT_MIN;

      default:
        return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is not null and can be cast to a string.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManString(mixed $value): bool
  {
    return match (gettype($value))
    {
      'boolean',
      'double',
      'integer',
      'string' => true,
      'object' => method_exists($value, '__toString'),
      default => false,
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is null or can be cast to a boolean.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptBool(mixed $value): bool
  {
    return $value===null || static::isManBool($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is null or can be cast to a finite float.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptFloat(mixed $value): bool
  {
    return $value===null || static::isManFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is null or can be cast to a float including NaN, -INF, and INF.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptFloatInclusive(mixed $value): bool
  {
    return $value===null || static::isManFloatInclusive($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is null or can be cast to an int.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptInt(mixed $value): bool
  {
    return $value===null || static::isManInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns whether a value is null or can be cast to a string.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptString(mixed $value): bool
  {
    return $value===null || static::isManString($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a boolean. If the value can not be safely cast to a boolean throws an exception.
   *
   * @param mixed     $value   The value.
   * @param bool|null $default The default value. If the value is null and the default is not null the default value
   *                           will be returned.
   *
   * @return bool
   */
  public static function toManBool(mixed $value, ?bool $default = null): bool
  {
    if ($value===null && $default!==null)
    {
      return $default;
    }

    if ($value===true || $value===1 || $value==='1')
    {
      return true;
    }

    if ($value===false || $value===0 || $value==='0')
    {
      return false;
    }

    throw new InvalidCastException('Value can not be converted to a boolean');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a finite float. If the value can not be safely cast to a finite float throws an exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null and the default is not null the default value
   *                            will be returned.
   *
   * @return float
   *
   * @throws InvalidCastException
   */
  public static function toManFloat(mixed $value, ?float $default = null): float
  {
    if ($value===null && $default!==null)
    {
      if (!is_finite($default))
      {
        throw new InvalidCastException('Default is not a finite float');
      }

      return $default;
    }

    if (static::isManFloat($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to finite float');
    }

    return (float)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a float including NaN, -INF, and INF. If the value can not be safely cast to a float throws an
   * exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null and the default is not null the default value
   *                            will be returned.
   *
   * @return float
   *
   * @throws InvalidCastException
   */
  public static function toManFloatInclusive(mixed $value, ?float $default = null): float
  {
    if ($value===null && $default!==null)
    {
      return $default;
    }

    if (static::isManFloatInclusive($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to float');
    }

    if (is_string($value))
    {
      $upper = strtoupper($value);

      if ($upper==='NAN')
      {
        return NAN;
      }

      if ($upper==='INF')
      {
        return INF;
      }

      if ($upper==='-INF')
      {
        return -INF;
      }
    }

    return (float)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to an int. If the value can not be safely cast to an int throws an exception.
   *
   * @param mixed    $value   The value.
   * @param int|null $default The default value. If the value is null and the default is not null the default value
   *                          will be returned.
   *
   * @return int
   *
   * @throws InvalidCastException
   */
  public static function toManInt(mixed $value, ?int $default = null): int
  {
    if ($value===null && $default!==null)
    {
      return $default;
    }

    if (static::isManInt($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to an integer');
    }

    return (int)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a string. If the value can not be safely cast to a string throws an exception.
   *
   * @param mixed       $value   The value.
   * @param string|null $default The default value. If the value is null and the default is not null the default value
   *                             will be returned.
   *
   * @return string
   *
   * @throws InvalidCastException
   */
  public static function toManString(mixed $value, ?string $default = null): string
  {
    if ($value===null && $default!==null)
    {
      return $default;
    }

    if (static::isManString($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to string');
    }

    if ($value===false)
    {
      return '0';
    }

    if (is_float($value))
    {
      if (is_nan($value))
      {
        return 'NaN';
      }

      if ($value===INF)
      {
        return 'INF';
      }

      if ($value===-INF)
      {
        return '-INF';
      }

      $string = sprintf('%.'.PHP_FLOAT_DIG.'E', $value);

      return preg_replace('/(\.0+E)|(0+E)/', 'E', $string);
    }

    return (string)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a boolean. If the value can not be safely cast to a boolean throws an exception.
   *
   * @param mixed     $value   The value.
   * @param bool|null $default The default value. If the value is null the default value will be returned.
   *
   * @return bool|null
   */
  public static function toOptBool(mixed $value, ?bool $default = null): ?bool
  {
    if ($value===null)
    {
      return $default;
    }

    return static::toManBool($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a finite float. If the value can not be safely cast to a finite float throws an exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null the default value will be returned.
   *
   * @return float|null
   */
  public static function toOptFloat(mixed $value, ?float $default = null): ?float
  {
    if ($value===null)
    {
      if ($default!==null && !is_finite($default))
      {
        throw new InvalidCastException('Default is not a finite float');
      }

      return $default;
    }

    return static::toManFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a float including NaN, -INF, and INF. If the value can not be safely cast to a float throws an
   * exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null the default value will be returned.
   *
   * @return float|null
   */
  public static function toOptFloatInclusive(mixed $value, ?float $default = null): ?float
  {
    if ($value===null)
    {
      return $default;
    }

    return static::toManFloatInclusive($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to an int. If the value can not be safely cast to an int throws an exception.
   *
   * @param mixed    $value   The value.
   * @param int|null $default The default value. If the value is null the default value will be returned.
   *
   * @return int|null
   */
  public static function toOptInt(mixed $value, ?int $default = null): ?int
  {
    if ($value===null)
    {
      return $default;
    }

    return static::toManInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a string. If the value can not be safely cast to a string throws an exception.
   *
   * @param mixed       $value   The value.
   * @param string|null $default The default value. If the value is null the default value will be returned.
   *
   * @return string|null
   */
  public static function toOptString(mixed $value, ?string $default = null): ?string
  {
    if ($value===null)
    {
      return $default;
    }

    return static::toManString($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
