<?php
declare(strict_types=1);

namespace SetBased\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Helper\Cast;
use SetBased\Helper\InvalidCastException;

/**
 * Test cases with booleans for Cast.
 */
class CastBoolTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional int test cases.
   *
   * @return array
   */
  public function invalidManBoolCases(): array
  {
    $cases   = $this->invalidOptBoolCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional int test cases.
   *
   * @return array
   */
  public function invalidOptBoolCases(): array
  {
    $cases = [[''],
              ['abc'],
              [123.456],
              [$this],
              [fopen('php://stdin', 'r')]];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testManBoolWithDefault()
  {
    // Default must not be used.
    $casted = Cast::toManBool(false, true);
    self::assertSame(false, $casted);

    // Default must be returned.
    $casted = Cast::toManBool(null, false);
    self::assertSame(false, $casted);

    $casted = Cast::toManBool(null, true);
    self::assertSame(true, $casted);

    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManBool(null, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory booleans.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidManBoolCases
   */
  public function testManBoolWithInvalidValues(mixed $value): void
  {
    $test = Cast::isManBool($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toManBool($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory booleans.
   *
   * @param mixed $value    The value.
   * @param bool  $expected The expected value.
   *
   * @dataProvider validManBoolCases
   */
  public function testManBoolWithValidValues(mixed $value, bool $expected): void
  {
    $test = Cast::isManBool($value);
    self::assertTrue($test);

    $casted = Cast::toManBool($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testOptBoolWithDefault()
  {
    // Default must not be used.
    $casted = Cast::toOptBool(false, true);
    self::assertSame(false, $casted);

    // Default must be returned.
    $casted = Cast::toOptBool(null, false);
    self::assertSame(false, $casted);

    $casted = Cast::toOptBool(null, true);
    self::assertSame(true, $casted);

    // When both the value and default are null, null must be returned.
    $casted = Cast::toOptBool(null, null);
    self::assertNull($casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional booleans.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidOptBoolCases
   */
  public function testOptBoolWithInvalidValues(mixed $value): void
  {
    $test = Cast::isOptBool($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toOptBool($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional booleans.
   *
   * @param mixed     $value    The value.
   * @param bool|null $expected The expected value.
   *
   * @dataProvider validOptBoolCases
   */
  public function testOptBoolWithValidValues(mixed $value, ?bool $expected): void
  {
    $test = Cast::isOptBool($value);
    self::assertTrue($test);

    $casted = Cast::toOptBool($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory boolean test cases.
   *
   * @return array
   */
  public function validManBoolCases(): array
  {
    return [[false, false],
            [0, false],
            ['0', false],
            [true, true],
            [1, true],
            ['1', true]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid optional boolean test cases.
   *
   * @return array
   */
  public function validOptBoolCases(): array
  {
    $cases   = $this->validManBoolCases();
    $cases[] = [null, null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
