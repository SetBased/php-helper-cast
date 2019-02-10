<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\InvalidCastException;

/**
 * Test cases with strings for Case.
 */
class CastStringTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid mandatory string test cases.
   *
   * @return array
   */
  public function invalidManStringCases(): array
  {
    $cases   = $this->invalidOptStringCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional string test cases.
   *
   * @return array
   */
  public function invalidOptStringCases(): array
  {
    return [[new HelloWithoutToString()],
            [fopen('php://stdin', 'r')]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory strings.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidManStringCases
   */
  public function testManStringWithInvalidValues($value): void
  {
    $test = Cast::isManString($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toManString($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory strings.
   *
   * @param mixed  $value    The value.
   * @param string $expected The expected value.
   *
   * @dataProvider validManStringCases
   */
  public function testManStringWithValidValues($value, string $expected): void
  {
    $test = Cast::isManString($value);
    self::assertTrue($test);

    $casted = Cast::toManString($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional strings.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidOptStringCases
   */
  public function testOptStringWithInvalidValues($value): void
  {
    $test = Cast::isOptString($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toOptString($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional strings.
   *
   * @param mixed       $value    The value.
   * @param string|null $expected The expected value.
   *
   * @dataProvider validOptStringCases
   */
  public function testOptStringWithValidValues($value, ?string $expected): void
  {
    $test = Cast::isOptString($value);
    self::assertTrue($test);

    $casted = Cast::toOptString($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory string test cases.
   *
   * @return array
   */
  public function validManStringCases(): array
  {
    return [['value'    => 'Hello, world',
             'expected' => 'Hello, world'],
            ['value'    => -123,
             'expected' => '-123'],
            ['value'    => 0,
             'expected' => '0'],
            ['value'    => 123,
             'expected' => '123'],
            ['value'    => '0',
             'expected' => '0'],
            ['value'    => '',
             'expected' => ''],
            ['value'    => 123.0,
             'expected' => '123'],
            ['value'    => false,
             'expected' => '0'],
            ['value'    => true,
             'expected' => '1'],
            ['value'    => new HelloWithToString(),
             'expected' => 'Hello, world'],
            ['value'    => INF,
             'expected' => 'INF'],
            ['value'    => -INF,
             'expected' => '-INF'],
            ['value'    => NAN,
             'expected' => 'NAN']];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid optional string test cases.
   *
   * @return array
   */
  public function validOptStringCases(): array
  {
    $cases   = $this->validManStringCases();
    $cases[] = ['value' => null, 'expected' => null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
