<?php
declare(strict_types=1);

namespace SetBased\Helper\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SetBased\Helper\Cast;
use SetBased\Helper\InvalidCastException;
use stdClass;

/**
 * Test cases with finite floats for Cast.
 */
class CastFloatTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid mandatory finite float test cases.
   *
   * @return array
   */
  public static function invalidManFiniteFloatCases(): array
  {
    $cases   = CastFloatTest::invalidOptFiniteFloatCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional finite float test cases.
   *
   * @return array
   */
  public static function invalidOptFiniteFloatCases(): array
  {
    return [[''],
            ['abc'],
            ['123  '],
            ['123.456  '],
            ['0.0  '],
            ['00.0'],
            [new stdClass()],
            [fopen('php://stdin', 'r')],
            [[]],
            [new NoFloat()],
            [INF],
            [-INF],
            [NAN],
            ['INF'],
            ['-INF'],
            ['NaN'],
            ['inf'],
            ['-inf'],
            ['nan']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory finite float test cases.
   *
   * @return array
   */
  public static function validManFiniteFloatCases(): array
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
             'expected' => PHP_INT_MIN * 2.0],];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory finite float test cases.
   *
   * @return array
   */
  public static function validOptFiniteFloatCases(): array
  {
    $cases   = CastFloatTest::validManFiniteFloatCases();
    $cases[] = ['value' => null, 'expected' => null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testManFiniteFloatWithDefault1()
  {
    // Default must not be used.
    $casted = Cast::toManFloat(1.1, pi());
    self::assertSame(1.1, $casted);

    // Default must be returned.
    $casted = Cast::toManFloat(null, pi());
    self::assertSame(pi(), $casted);

    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFloat(null, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testManFiniteFloatWithDefault2()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFloat(null, INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testManFiniteFloatWithDefault3()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFloat(null, -1.0 * INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testManFiniteFloatWithDefault4()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFloat(null, NAN);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory finite floats.
   *
   * @param mixed $value The invalid value.
   */
  #[DataProvider('invalidManFiniteFloatCases')]
  public function testManFiniteFloatWithInvalidValues(mixed $value): void
  {
    $test = Cast::isManFloat($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toManFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory finite floats.
   *
   * @param mixed $value    The value.
   * @param float $expected The expected value.
   */
  #[DataProvider('validManFiniteFloatCases')]
  public function testManFiniteFloatWithValidValues(mixed $value, float $expected): void
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
   * Test with default value.
   */
  public function testOptFiniteFloatWithDefault1()
  {
    // Default must not be used.
    $casted = Cast::toOptFloat(1.1, pi());
    self::assertSame(1.1, $casted);

    // Default must be returned.
    $casted = Cast::toOptFloat(null, pi());
    self::assertSame(pi(), $casted);

    // When both the value and default are null, null must be returned.
    $casted = Cast::toOptFloat(null, null);
    self::assertNull($casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testOptFiniteFloatWithDefault2()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toOptFloat(null, INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testOptFiniteFloatWithDefault3()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toOptFloat(null, -1.0 * INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testOptFiniteFloatWithDefault4()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toOptFloat(null, NAN);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional finite floats.
   *
   * @param mixed $value The invalid value.
   */
  #[DataProvider('invalidOptFiniteFloatCases')]
  public function testOptFiniteFloatWithInvalidValues(mixed $value): void
  {
    $test = Cast::isOptFloat($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toOptFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional finite floats.
   *
   * @param mixed      $value    The value.
   * @param float|null $expected The expected value.
   */
  #[DataProvider('validOptFiniteFloatCases')]
  public function testOptFiniteFloatWithValidValues(mixed $value, ?float $expected): void
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
}

//----------------------------------------------------------------------------------------------------------------------
