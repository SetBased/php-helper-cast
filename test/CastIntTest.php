<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\InvalidCastException;

/**
 * Test cases with integers for Cast.
 */
class CastIntTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid mandatory int test cases.
   *
   * @return array
   */
  public function invalidManIntCases(): array
  {
    $cases   = $this->invalidOptIntCases();
    $cases[] = [null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional int test cases.
   *
   * @return array
   */
  public function invalidOptIntCases(): array
  {
    return [[''],
            [123.456],
            [$this],
            [$this->getIntOverflow()],
            [$this->getIntUnderflow()],
            [fopen('php://stdin', 'r')]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory integers.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidManIntCases
   */
  public function testManIntWithInvalidValues($value): void
  {
    $test = Cast::isManInt($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toManInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory integers.
   *
   * @param mixed $value    The value.
   * @param int   $expected The expected value.
   *
   * @dataProvider validManIntCases
   */
  public function testManIntWithValidValues($value, int $expected): void
  {
    $test = Cast::isManInt($value);
    self::assertTrue($test);

    $casted = Cast::toManInt($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional integers.
   *
   * @param mixed $value The invalid value.
   *
   * @dataProvider invalidOptIntCases
   */
  public function testOptIntWithInvalidValues($value): void
  {
    $test = Cast::isOptInt($value);
    self::assertFalse($test);

    $this->expectException(InvalidCastException::class);
    Cast::toOptInt($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional integers.
   *
   * @param mixed    $value    The value.
   * @param int|null $expected The expected value.
   *
   * @dataProvider validOptIntCases
   */
  public function testOptIntWithValidValues($value, ?int $expected): void
  {
    $test = Cast::isOptInt($value);
    self::assertTrue($test);

    $casted = Cast::toOptInt($value);
    self::assertSame($expected, $casted);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory int test cases.
   *
   * @return array
   */
  public function validManIntCases(): array
  {
    return [['123', 123,],
            ['-123', -123],
            ['0', 0,],
            ['+123', 123,],
            [123, 123,],
            [0, 0],
            [123.0, 123]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory int test cases.
   *
   * @return array
   */
  public function validOptIntCases(): array
  {
    $cases   = $this->validManIntCases();
    $cases[] = [null, null];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the string representation of the smallest integer that is an integer overflow.
   *
   * @return string
   */
  private function getIntOverflow(): string
  {
    if ((string)PHP_INT_MAX=='9223372036854775807')
    {
      return '9223372036854775808';
    }

    throw new \RuntimeException(sprintf('Fix getIntOverflow for %s', (string)PHP_INT_MAX));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the string representation of the largest integer that is an integer underflow.
   *
   * @return string
   */
  private function getIntUnderflow(): string
  {
    if ((string)PHP_INT_MIN=='-9223372036854775808')
    {
      return '-9223372036854775809';
    }

    throw new \RuntimeException(sprintf('Fix getIntUnderflow for %s', (string)PHP_INT_MIN));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
