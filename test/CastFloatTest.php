<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\InvalidCastException;

/**
 * Test cases with floats for Cast.
 */
class CastFloatTest extends TestCase
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
   * Test cases with invalid mandatory floats.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidManFloatCases
   */
  public function testManFloatWithInvalidValues($value): void
  {
    $test = Cast::isManFloat($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toManFloat($value);
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
  public function testManFloatWithValidValues($value, float $expected): void
  {
    $test = Cast::isManFloat($value);
    self::assertTrue($test);

    $casted = Cast::toManFloat($value);
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
   * Test cases with invalid optional floats.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidOptFloatCases
   */
  public function testOptFloatWithInvalidValues($value): void
  {
    $test = Cast::isOptFloat($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toOptFloat($value);
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
  public function testOptFloatWithValidValues($value, ?float $expected): void
  {
    $test = Cast::isOptFloat($value);
    self::assertTrue($test);

    $casted = Cast::toOptFloat($value);
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
