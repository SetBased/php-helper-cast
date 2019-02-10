<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper;

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
   * Returns true if and only if a value is not null and can be casted to a finite float. Otherwise returns false.
   *
   * @param mixed $value The value.
   *
   * @return bool
   */
  public static function isManFiniteFloat($value): bool
  {
    return (static::isManFloat($value) && is_finite((float)$value));
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

        return ($filtered===$value);

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
   * Converts a value to a boolean. The the value can not be safely casted to a boolean throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return bool
   *
   * @throws InvalidCastException
   */
  public static function toManBool($value): bool
  {
    if ($value===true || $value===1 || $value==='1' || $value===1.0)
    {
      return true;
    }

    if ($value===false || $value===0 || $value==='0' || $value===0.0)
    {
      return false;
    }

    throw new InvalidCastException('Value can not be converted to a boolean');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a finite float. The the value can not be safely casted to a finite float throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return float
   *
   * @throws InvalidCastException
   */
  public static function toManFiniteFloat($value): float
  {
    if (static::isManFiniteFloat($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to finite float');
    }

    return (float)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a float. The the value can not be safely casted to a float throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return float
   *
   * @throws InvalidCastException
   */
  public static function toManFloat($value): float
  {
    if (static::isManFloat($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to float');
    }

    return (float)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to an int. The the value can not be safely casted to an int throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return int
   *
   * @throws InvalidCastException
   */
  public static function toManInt($value): int
  {
    if (static::isManInt($value)===false)
    {
      throw new InvalidCastException('Value can not be converted to an integer');
    }

    return (int)$value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a string. The the value can not be safely casted to a string throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return string
   *
   * @throws InvalidCastException
   */
  public static function toManString($value): string
  {
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
   * Converts a value to a boolean. The the value can not be safely casted to a boolean throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return bool|null
   */
  public static function toOptBool($value): ?bool
  {
    if ($value===null)
    {
      return null;
    }

    return static::toManBool($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a finite float. The the value can not be safely casted to a finite float throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return float|null
   */
  public static function toOptFiniteFloat($value): ?float
  {
    if ($value===null)
    {
      return null;
    }

    return static::toManFiniteFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a float. The the value can not be safely casted to a float throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return float|null
   */
  public static function toOptFloat($value): ?float
  {
    if ($value===null)
    {
      return null;
    }

    return static::toManFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to an int. The the value can not be safely casted to an int throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return int|null
   */
  public static function toOptInt($value): ?int
  {
    if ($value===null)
    {
      return null;
    }

    return static::toManInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Converts a value to a string. The the value can not be safely casted to a string throws an exception.
   *
   * @param mixed $value The value.
   *
   * @return string|null
   */
  public static function toOptString($value): ?string
  {
    if ($value===null)
    {
      return null;
    }

    return static::toManString($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
