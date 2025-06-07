<?php
declare(strict_types=1);

namespace SetBased\Helper\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SetBased\Helper\Cast;
use SetBased\Helper\InvalidCastException;
use stdClass;

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
  public static function invalidManBoolCases(): array
  {
    $cases   = CastBoolTest::invalidOptBoolCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional int test cases.
   *
   * @return array
   */
  public static function invalidOptBoolCases(): array
  {
    return [[''],
            ['abc'],
            [123.456],
            [new stdClass()],
            [fopen('php://stdin', 'r')]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory boolean test cases.
   *
   * @return array
   */
  public static function validManBoolCases(): array
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
  public static function validOptBoolCases(): array
  {
    $cases   = CastBoolTest::validManBoolCases();
    $cases[] = [null, null];

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
   */
  #[DataProvider('invalidManBoolCases')]
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
   */
  #[DataProvider('validManBoolCases')]
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
   */
  #[DataProvider('invalidOptBoolCases')]
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
   */
  #[DataProvider('validOptBoolCases')]
  public function testOptBoolWithValidValues(mixed $value, ?bool $expected): void
  {
    $test = Cast::isOptBool($value);
    self::assertTrue($test);

    $casted = Cast::toOptBool($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
