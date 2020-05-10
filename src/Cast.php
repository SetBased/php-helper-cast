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
   * Return true if and only if a value is not null and can be casted to a boolean. Otherwise returns false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManBool($value): bool
  {
    if ($value===false ||
      $value===true ||
      $value===0 ||
      $value===1 ||
      $value==='0' ||
      $value==='1')
    {
      return true;
    }

    return false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if a value is not null and can be casted to a float. Otherwise returns false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManFiniteFloat($value): bool
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
        if ($value==='') return false;

        // Reject leading zeros unless they are followed by a decimal point
        if (strlen($value)>1 && $value[0]==='0' && $value[1]!=='.') return false;

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
   * Returns true if and only if a value is not null and can be casted to a float. Otherwise returns false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManFloat($value): bool
  {
    switch (gettype($value))
    {
      case 'boolean':
      case 'double':
      case 'integer':
        return true;

      case 'string':
        // Reject empty strings.
        if ($value==='') return false;

        // Reject leading zeros unless they are followed by a decimal point
        if (strlen($value)>1 && $value[0]==='0' && $value[1]!=='.') return false;

        $filtered = filter_var($value,
                               FILTER_SANITIZE_NUMBER_FLOAT,
                               FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_SCIENTIFIC);

        return ($filtered===$value || in_array($value, ['NAN', 'INF', '-INF'], true));

      default:
        return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return true if and only if a value is not null and can be casted to an int. Otherwise returns false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManInt($value): bool
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
   * Returns true if and only if a value is not null and can be casted to a string. Otherwise returns false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManString($value): bool
  {
    switch (gettype($value))
    {
      case 'boolean':
      case 'double':
      case 'integer':
      case 'string':
        return true;

      case 'object':
        return method_exists($value, '__toString');

      default:
        return false;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if a value is null or can be casted to a boolean, otherwise return false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptBool($value): bool
  {
    return ($value===null) ? true : static::isManBool($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if a value is null or can be casted to a finite float, otherwise return false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptFiniteFloat($value): bool
  {
    return ($value===null) ? true : static::isManFiniteFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if a value is null or can be casted to a float, otherwise return false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptFloat($value): bool
  {
    return ($value===null) ? true : static::isManFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if a value is null or can be casted to an int, otherwise return false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptInt($value): bool
  {
    return ($value===null) ? true : static::isManInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns true if and only if a value is null or can be casted to a string, otherwise return false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isOptString($value): bool
  {
    return ($value===null) ? true : static::isManString($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a boolean. If the value can not be safely casted to a boolean throws an exception.
   *
   * @param mixed     $value   The value.
   * @param bool|null $default The default value. If the value is null and the default is not null the default value
   *                           will be returned.
   *
   * @return bool
   *
   */
  public static function toManBool($value, ?bool $default = null): bool
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
   * Converts a value to a finite float. If the value can not be safely casted to a finite float throws an exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null and the default is not null the default value
   *                            will be returned.
   *
   * @return float
   *
   * @throws InvalidCastException
   */
  public static function toManFiniteFloat($value, ?float $default = null): float
  {
    if ($value===null && $default!==null)
    {
      if (!is_finite($default))
      {
        throw new InvalidCastException('Default is not a finite float');
      }

      return $default;
    }

    if (static::isManFiniteFloat($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to finite float');
    }

    return (float)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a float. If the value can not be safely casted to a float throws an exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null and the default is not null the default value
   *                            will be returned.
   *
   * @return float
   *
   * @throws InvalidCastException
   */
  public static function toManFloat($value, ?float $default = null): float
  {
    if ($value===null && $default!==null)
    {
      return $default;
    }

    if (static::isManFloat($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to float');
    }

    if ($value==='NAN') return NAN;
    if ($value==='INF') return INF;
    if ($value==='-INF') return -INF;

    return (float)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to an int. If the value can not be safely casted to an int throws an exception.
   *
   * @param mixed    $value   The value.
   * @param int|null $default The default value. If the value is null and the default is not null the default value
   *                          will be returned.
   *
   * @return int
   *
   * @throws InvalidCastException
   */
  public static function toManInt($value, ?int $default = null): int
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
   * Converts a value to a string. If the value can not be safely casted to a string throws an exception.
   *
   * @param mixed       $value   The value.
   * @param string|null $default The default value. If the value is null and the default is not null the default value
   *                             will be returned.
   *
   * @return string
   *
   * @throws InvalidCastException
   */
  public static function toManString($value, ?string $default = null): string
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

    return (string)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a boolean. If the value can not be safely casted to a boolean throws an exception.
   *
   * @param mixed     $value   The value.
   * @param bool|null $default The default value. If the value is null the default value will be returned.
   *
   * @return bool|null
   */
  public static function toOptBool($value, ?bool $default = null): ?bool
  {
    if ($value===null)
    {
      return $default;
    }

    return static::toManBool($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a finite float. If the value can not be safely casted to a finite float throws an exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null the default value will be returned.
   *
   * @return float|null
   */
  public static function toOptFiniteFloat($value, ?float $default = null): ?float
  {
    if ($value===null)
    {
      if ($default!==null && !is_finite($default))
      {
        throw new InvalidCastException('Default is not a finite float');
      }

      return $default;
    }

    return static::toManFiniteFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a float. If the value can not be safely casted to a float throws an exception.
   *
   * @param mixed      $value   The value.
   * @param float|null $default The default value. If the value is null the default value will be returned.
   *
   * @return float|null
   */
  public static function toOptFloat($value, ?float $default = null): ?float
  {
    if ($value===null)
    {
      return $default;
    }

    return static::toManFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to an int. If the value can not be safely casted to an int throws an exception.
   *
   * @param mixed    $value   The value.
   * @param int|null $default The default value. If the value is null the default value will be returned.
   *
   * @return int|null
   */
  public static function toOptInt($value, ?int $default = null): ?int
  {
    if ($value===null)
    {
      return $default;
    }

    return static::toManInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a string. If the value can not be safely casted to a string throws an exception.
   *
   * @param mixed       $value   The value.
   * @param string|null $default The default value. If the value is null the default value will be returned.
   *
   * @return string|null
   */
  public static function toOptString($value, ?string $default = null): ?string
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
