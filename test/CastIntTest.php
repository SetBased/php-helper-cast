<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\CastException;

/**
 * Test cases with integers for Cast.
 */
class CastIntTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory integers.
   */
  public function testIsManIntWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptIntCases();
    $cases[] = ['value'   => null,
                'message' => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isManInt($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional integers.
   */
  public function testIsOptIntWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptIntCases();

    foreach ($cases as $case)
    {
      $test = Cast::isOptInt($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory integers.
   */
  public function testManIntWithValidValues(): void
  {
    $cases = $this->getValidManIntCases();

    foreach ($cases as $case)
    {
      $test = Cast::isManInt($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toManInt($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional integers.
   */
  public function testOptIntWithValidValues(): void
  {
    $cases   = $this->getValidManIntCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isOptInt($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toOptInt($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory integers.
   */
  public function testToManIntWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptIntCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      try
      {
        Cast::toManInt($case['value']);

        $exceptionHasBeenThrown = false;
      }
      catch (CastException $e)
      {
        $exceptionHasBeenThrown = true;
      }

      self::assertTrue($exceptionHasBeenThrown, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional integers.
   */
  public function testToOptIntWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptIntCases();

    foreach ($cases as $case)
    {
      try
      {
        Cast::toOptInt($case['value']);

        $exceptionHasBeenThrown = false;
      }
      catch (CastException $e)
      {
        $exceptionHasBeenThrown = true;
      }

      self::assertTrue($exceptionHasBeenThrown, $case['message']);
    }
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
  /**
   * Returns invalid optional int test cases.
   *
   * @return array
   */
  private function getInvalidOptIntCases(): array
  {
    $cases = [['value'   => '',
               'message' => "string('')"],
              ['value'   => 123.456,
               'message' => 'float(123.45)'],
              ['value'   => $this,
               'message' => 'object'],
              ['value'   => $this->getIntOverflow(),
               'message' => 'overflow'],
              ['value'   => $this->getIntUnderflow(),
               'message' => 'underflow'],
              ['value'   => fopen('php://stdin', 'r'),
               'message' => 'resource']];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory int test cases.
   *
   * @return array
   */
  private function getValidManIntCases(): array
  {
    $cases = [['value'    => '123',
               'expected' => 123,
               'message'  => 'string(123)'],
              ['value'    => '-123',
               'expected' => -123,
               'message'  => 'string(-123)'],
              ['value'    => '0',
               'expected' => 0,
               'message'  => 'string(0)'],
              ['value'    => '+123',
               'expected' => 123,
               'message'  => 'string(+123)'],
              ['value'    => 123,
               'expected' => 123,
               'message'  => 'int(123)'],
              ['value'    => 0,
               'expected' => 0,
               'message'  => 'int(0)'],
              ['value'    => 123.0,
               'expected' => 123,
               'message'  => 'float(123.0)']];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
