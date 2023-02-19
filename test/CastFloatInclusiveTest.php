<?php
declare(strict_types=1);

namespace SetBased\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Helper\Cast;
use SetBased\Helper\InvalidCastException;

/**
 * Test cases with floats for Cast.
 */
class CastFloatInclusiveTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid mandatory float test cases.
   *
   * @return array
   */
  public function invalidManFloatCases(): array
  {
    $cases   = $this->invalidOptFloatCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional float test cases.
   *
   * @return array
   */
  public function invalidOptFloatCases(): array
  {
    return [[''],
            ['abc'],
            ['123  '],
            ['123.456  '],
            ['0.0  '],
            ['00.0'],
            [$this],
            [fopen('php://stdin', 'r')],
            [[]],
            [new NoFloat()]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testManFloatWithDefault()
  {
    // Default must not be used.
    $casted = Cast::toManFloatInclusive(1.1, pi());
    self::assertSame(1.1, $casted);

    // Default must be returned.
    $casted = Cast::toManFloatInclusive(null, pi());
    self::assertSame(pi(), $casted);

    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFloatInclusive(null, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory floats.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidManFloatCases
   */
  public function testManFloatWithInvalidValues(mixed $value): void
  {
    $test = Cast::isManFloatInclusive($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toManFloatInclusive($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory floats.
   *
   * @param mixed $value    The value.
   * @param float $expected The expected value.
   *
   * @dataProvider validManFloatCases
   */
  public function testManFloatWithValidValues(mixed $value, float $expected): void
  {
    $test = Cast::isManFloatInclusive($value);
    self::assertTrue($test);

    $casted = Cast::toManFloatInclusive($value);
    if (is_nan($expected))
    {
      self::assertNan($casted);
    }
    else
    {
      self::assertSame($expected, $casted);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testOptFloatWithDefault()
  {
    // Default must not be used.
    $casted = Cast::toOptFloatInclusive(1.1, pi());
    self::assertSame(1.1, $casted);

    // Default must be returned.
    $casted = Cast::toOptFloatInclusive(null, pi());
    self::assertSame(pi(), $casted);

    // When both the value and default are null, null must be returned.
    $casted = Cast::toOptFloatInclusive(null, null);
    self::assertNull($casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional floats.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidOptFloatCases
   */
  public function testOptFloatWithInvalidValues(mixed $value): void
  {
    $test = Cast::isOptFloatInclusive($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toOptFloatInclusive($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional floats.
   *
   * @param mixed      $value    The value.
   * @param float|null $expected The expected value.
   *
   * @dataProvider validOptFloatCases
   */
  public function testOptFloatWithValidValues(mixed $value, ?float $expected): void
  {
    $test = Cast::isOptFloatInclusive($value);
    self::assertTrue($test);

    $casted = Cast::toOptFloatInclusive($value);
    if ($expected!==null && is_nan($expected))
    {
      self::assertNan($casted);
    }
    else
    {
      self::assertSame($expected, $casted);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory float test cases.
   *
   * @return array
   */
  public function validManFloatCases(): array
  {
    return [['value'    => 123.45,
             'expected' => 123.45],
            ['value'    => 0,
             'expected' => 0.0],
            ['value'    => 1,
             'expected' => 1.0],
            ['value'    => '0',
             'expected' => 0.0],
            ['value'    => '0.0',
             'expected' => 0.0],
            ['value'    => '0.1',
             'expected' => 0.1],
            ['value'    => '123.45',
             'expected' => 123.45],
            ['value'    => '75e-5',
             'expected' => 0.00075],
            ['value'    => "75E-5",
             'expected' => 0.00075],
            ['value'    => '31e+7',
             'expected' => 310000000.0],
            ['value'    => '31E+7',
             'expected' => 310000000.0],
            ['value'    => (string)PHP_INT_MAX,
             'expected' => (float)PHP_INT_MAX],
            ['value'    => PHP_INT_MAX,
             'expected' => (float)PHP_INT_MAX],
            ['value'    => (float)PHP_INT_MAX,
             'expected' => (float)PHP_INT_MAX],
            ['value'    => (string)PHP_INT_MIN,
             'expected' => (float)PHP_INT_MIN],
            ['value'    => PHP_INT_MIN,
             'expected' => (float)PHP_INT_MIN],
            ['value'    => (float)PHP_INT_MIN,
             'expected' => (float)PHP_INT_MIN],
            ['value'    => PHP_INT_MAX * 2.0,
             'expected' => PHP_INT_MAX * 2.0],
            ['value'    => PHP_INT_MIN * 2.0,
             'expected' => PHP_INT_MIN * 2.0],
            ['value'    => INF,
             'expected' => INF],
            ['value'    => -INF,
             'expected' => -INF],
            ['value'    => NAN,
             'expected' => NAN],
            ['value'    => 'INF',
             'expected' => INF],
            ['value'    => '-INF',
             'expected' => -INF],
            ['value'    => 'NaN',
             'expected' => NAN],
            ['value'    => 'inf',
             'expected' => INF],
            ['value'    => '-inf',
             'expected' => -INF],
            ['value'    => 'nan',
             'expected' => NAN]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory float test cases.
   *
   * @return array
   */
  public function validOptFloatCases(): array
  {
    $cases   = $this->validManFloatCases();
    $cases[] = ['value' => null, 'expected' => null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
