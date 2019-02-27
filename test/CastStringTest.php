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
                'expected' => '123'];
    $cases[] = ['value'    => INF,
                'expected' => 'INF'];
    $cases[] = ['value'    => -INF,
                'expected' => '-INF'];
    $cases[] = ['value'    => NAN,
                'expected' => 'NAN'];

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
  public function validOptStringCases(): array
  {
    $cases = $this->validManStringCases();

    // Test cases with null.
    $cases[] = ['value'    => null,
                'expected' => null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
