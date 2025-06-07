<?php
declare(strict_types=1);

namespace SetBased\Helper\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SetBased\Helper\Cast;
use SetBased\Helper\InvalidCastException;

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
  public static function invalidManStringCases(): array
  {
    $cases   = CastStringTest::invalidOptStringCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional string test cases.
   *
   * @return array
   */
  public static function invalidOptStringCases(): array
  {
    return [[new HelloWithoutToString()],
            [fopen('php://stdin', 'r')]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory string test cases.
   *
   * @return array
   */
  public static function validManStringCases(): array
  {
    $cases = [];

    // String test cases.
    $cases[] = ['value'    => 'Hello, world',
                'expected' => 'Hello, world'];
    $cases[] = ['value'    => '0',
                'expected' => '0'];
    $cases[] = ['value'    => '',
                'expected' => ''];

    // Integer test cases.
    $cases[] = ['value'    => -123,
                'expected' => '-123'];
    $cases[] = ['value'    => 0,
                'expected' => '0'];
    $cases[] = ['value'    => 123,
                'expected' => '123'];

    // Float test cases.
    $cases[] = ['value'    => 123.0,
                'expected' => '1.23E+2'];
    $cases[] = ['value'    => INF,
                'expected' => 'INF'];
    $cases[] = ['value'    => -INF,
                'expected' => '-INF'];
    $cases[] = ['value'    => NAN,
                'expected' => 'NaN'];

    // Boolean test cases.
    $cases[] = ['value'    => false,
                'expected' => '0'];
    $cases[] = ['value'    => true,
                'expected' => '1'];

    // Class with __toString method test cases.
    $cases[] = ['value'    => new HelloWithToString(),
                'expected' => 'Hello, world'];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid optional string test cases.
   *
   * @return array
   */
  public static function validOptStringCases(): array
  {
    $cases = CastStringTest::validManStringCases();

    // Test cases with null.
    $cases[] = ['value'    => null,
                'expected' => null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testManStringWithDefault()
  {
    // Default must not be used.
    $casted = Cast::toManString('php', 'java');
    self::assertSame('php', $casted);

    // Default must be returned.
    $casted = Cast::toManString(null, 'python');
    self::assertSame('python', $casted);

    // When value and default is null an exception must be thrown.
    $this->expectException(InvalidCastException::class);
    Cast::toManString(null, null);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory strings.
   *
   * @param mixed $value The invalid value.
   */
  #[DataProvider('invalidManStringCases')]
  public function testManStringWithInvalidValues(mixed $value): void
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
   */
  #[DataProvider('validManStringCases')]
  public function testManStringWithValidValues(mixed $value, string $expected): void
  {
    $test = Cast::isManString($value);
    self::assertTrue($test);

    $casted = Cast::toManString($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test the number of digits of a float cast tot a string.
   */
  public function testOptStringDigits(): void
  {
    $pi = Cast::toOptString(M_PI);
    self::assertEquals(PHP_FLOAT_DIG + 5, strlen($pi));

    $value = Cast::toOptString(100.0);
    self::assertEquals('1E+2', $value);

    $value = Cast::toOptString(0.01);
    self::assertEquals('1E-2', $value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with default value.
   */
  public function testOptStringWithDefault()
  {
    // Default must not be used.
    $casted = Cast::toOptString('php', 'java');
    self::assertSame('php', $casted);

    // Default must be returned.
    $casted = Cast::toOptString(null, 'python');
    self::assertSame('python', $casted);

    // When both the value and default are null, null must be returned.
    $casted = Cast::toOptString(null, null);
    self::assertNull($casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional strings.
   *
   * @param mixed $value The invalid value.
   */
  #[DataProvider('invalidOptStringCases')]
  public function testOptStringWithInvalidValues(mixed $value): void
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
   */
  #[DataProvider('validOptStringCases')]
  public function testOptStringWithValidValues(mixed $value, ?string $expected): void
  {
    $test = Cast::isOptString($value);
    self::assertTrue($test);

    $casted = Cast::toOptString($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
