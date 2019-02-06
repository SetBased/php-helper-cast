<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\CastException;

/**
 * Test cases with strings for Case.
 */
class CastStringTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory strings.
   */
  public function testIsManStringWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptStringCases();
    $cases[] = ['value'   => null,
                'message' => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isManString($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional strings.
   */
  public function testIsOptStringWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptStringCases();

    foreach ($cases as $case)
    {
      $test = Cast::isOptString($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory strings.
   */
  public function testManStringWithValidValues(): void
  {
    $cases = $this->getValidManStringCases();

    foreach ($cases as $case)
    {
      $test = Cast::isManString($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toManString($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional strings.
   */
  public function testOptStringWithValidValues(): void
  {
    $cases   = $this->getValidManStringCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isOptString($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toOptString($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory strings.
   */
  public function testToManStringWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptStringCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      try
      {
        Cast::toManString($case['value']);

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
   * Test cases with invalid optional strings.
   */
  public function testToOptStringWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptStringCases();

    foreach ($cases as $case)
    {
      try
      {
        Cast::toOptString($case['value']);

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
   * Returns invalid optional string test cases.
   *
   * @return array
   */
  private function getInvalidOptStringCases(): array
  {
    $cases = [['value'   => new HelloWithoutToString(),
               'message' => "object without __toString"],
              ['value'   => fopen('php://stdin', 'r'),
               'message' => 'resource']];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid mandatory string test cases.
   *
   * @return array
   */
  private function getValidManStringCases(): array
  {
    $cases = [['value'    => 'Hello, world',
               'expected' => 'Hello, world',
               'message'  => 'string(Hello, world)'],
              ['value'    => -123,
               'expected' => '-123',
               'message'  => 'int(-123)'],
              ['value'    => 0,
               'expected' => '0',
               'message'  => 'int(0)'],
              ['value'    => 123,
               'expected' => '123',
               'message'  => 'int(123)'],
              ['value'    => '0',
               'expected' => '0',
               'message'  => 'string(0)'],
              ['value'    => '',
               'expected' => '',
               'message'  => 'string()'],
              ['value'    => 123.0,
               'expected' => '123',
               'message'  => 'float(123.0)'],
              ['value'    => false,
               'expected' => '0',
               'message'  => 'false'],
              ['value'    => true,
               'expected' => '1',
               'message'  => 'true'],
              ['value'    => new HelloWithToString(),
               'expected' => 'Hello, world',
               'message'  => "object with __toString"]];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
