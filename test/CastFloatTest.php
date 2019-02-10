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
            [fopen('php://stdin', 'r')]];
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
    self::assertSame($expected, $casted);
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
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory float test cases.
   *
   * @return array
   */
  public function validManFloatCases(): array
  {
    return [[123.45, 123.45],
            [0, 0.0],
            [1, 1.0],
            ['0', 0.0],
            ['0.0', 0.0],
            ['0.1', 0.1],
            ['123.45', 123.45]];
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
    $cases[] = [null, null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
