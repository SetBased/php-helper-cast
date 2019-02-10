<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\InvalidCastException;

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
   * Test cases with invalid mandatory booleans.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidManBoolCases
   */
  public function testManBoolWithInvalidValues($value): void
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
  public function testManBoolWithValidValues($value, bool $expected): void
  {
    $test = Cast::isManBool($value);
    self::assertTrue($test);

    $casted = Cast::toManBool($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional booleans.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidOptBoolCases
   */
  public function testOptBoolWithInvalidValues($value): void
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
  public function testOptBoolWithValidValues($value, ?bool $expected): void
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
