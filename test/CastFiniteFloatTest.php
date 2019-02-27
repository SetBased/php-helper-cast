<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\InvalidCastException;

/**
 * Test cases with finite floats for Cast.
 */
class CastFiniteFloatTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid mandatory finite float test cases.
   *
   * @return array
   */
  public function invalidManFiniteFloatCases(): array
  {
    $cases   = $this->invalidOptFiniteFloatCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional finite float test cases.
   *
   * @return array
   */
  public function invalidOptFiniteFloatCases(): array
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
            [new NoFloat()],
            [INF],
            [-INF],
            [NAN],
            ['INF'],
            ['-INF'],
            ['NAN']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testManFiniteFloatWithDefault1()
  {
    // Default must not be used.
    $casted = Cast::toManFiniteFloat(1.1, pi());
    self::assertSame(1.1, $casted);

    // Default must be returned.
    $casted = Cast::toManFiniteFloat(null, pi());
    self::assertSame(pi(), $casted);

    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFiniteFloat(null, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testManFiniteFloatWithDefault2()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFiniteFloat(null, INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testManFiniteFloatWithDefault3()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFiniteFloat(null, -1.0 * INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testManFiniteFloatWithDefault4()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManFiniteFloat(null, NAN);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory finite floats.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidManFiniteFloatCases
   */
  public function testManFiniteFloatWithInvalidValues($value): void
  {
    $test = Cast::isManFiniteFloat($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toManFiniteFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory finite floats.
   *
   * @param mixed $value    The value.
   * @param float $expected The expected value.
   *
   * @dataProvider validManFiniteFloatCases
   */
  public function testManFiniteFloatWithValidValues($value, float $expected): void
  {
    $test = Cast::isManFiniteFloat($value);
    self::assertTrue($test);

    $casted = Cast::toManFiniteFloat($value);
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
    $casted = Cast::toOptFiniteFloat(1.1, pi());
    self::assertSame(1.1, $casted);

    // Default must be returned.
    $casted = Cast::toOptFiniteFloat(null, pi());
    self::assertSame(pi(), $casted);

    // When both the value and default are null, null must be returned.
    $casted = Cast::toOptFiniteFloat(null, null);
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
    Cast::toOptFiniteFloat(null, INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testOptFiniteFloatWithDefault3()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toOptFiniteFloat(null, -1.0 * INF);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with not a finite default value.
   */
  public function testOptFiniteFloatWithDefault4()
  {
    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toOptFiniteFloat(null, NAN);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional finite floats.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidOptFiniteFloatCases
   */
  public function testOptFiniteFloatWithInvalidValues($value): void
  {
    $test = Cast::isOptFiniteFloat($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toOptFiniteFloat($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional finite floats.
   *
   * @param mixed      $value    The value.
   * @param float|null $expected The expected value.
   *
   * @dataProvider validOptFiniteFloatCases
   */
  public function testOptFiniteFloatWithValidValues($value, ?float $expected): void
  {
    $test = Cast::isOptFiniteFloat($value);
    self::assertTrue($test);

    $casted = Cast::toOptFiniteFloat($value);
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
   * Returns valid mandatory finite float test cases.
   *
   * @return array
   */
  public function validManFiniteFloatCases(): array
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
  public function validOptFiniteFloatCases(): array
  {
    $cases   = $this->validManFiniteFloatCases();
    $cases[] = ['value' => null, 'expected' => null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
