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
   * Test cases with invalid mandatory booleans.
   */
  public function testIsManBoolWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptBoolCases();
    $cases[] = ['value'   => null,
                'message' => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isManBool($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional booleans.
   */
  public function testIsOptBoolWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptBoolCases();

    foreach ($cases as $case)
    {
      $test = Cast::isOptBool($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory booleans.
   */
  public function testManIntWithValidValues(): void
  {
    $cases = $this->getValidManBoolCases();

    foreach ($cases as $case)
    {
      $test = Cast::isManBool($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toManBool($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional booleans.
   */
  public function testOptBoolWithValidValues(): void
  {
    $cases   = $this->getValidManBoolCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isOptBool($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toOptBool($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory booleans.
   */
  public function testToManBoolWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptBoolCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      try
      {
        Cast::toManBool($case['value']);

        $exceptionHasBeenThrown = false;
      }
      catch (InvalidCastException $e)
      {
        $exceptionHasBeenThrown = true;
      }

      self::assertTrue($exceptionHasBeenThrown, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory booleans.
   */
  public function testToOptBoolWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptBoolCases();

    foreach ($cases as $case)
    {
      try
      {
        Cast::toOptBool($case['value']);

        $exceptionHasBeenThrown = false;
      }
      catch (InvalidCastException $e)
      {
        $exceptionHasBeenThrown = true;
      }

      self::assertTrue($exceptionHasBeenThrown, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid optional int test cases.
   *
   * @return array
   */
  private function getInvalidOptBoolCases(): array
  {
    $cases = [['value'   => '',
               'message' => "string('')"],
              ['value'   => 'abc',
               'message' => "string(abc)"],
              ['value'   => 123.456,
               'message' => 'float(123.45)'],
              ['value'   => $this,
               'message' => 'object'],
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
  private function getValidManBoolCases(): array
  {
    $cases = [['value'    => false,
               'expected' => false,
               'message'  => 'bool(false)'],
              ['value'    => 0,
               'expected' => false,
               'message'  => 'int(0)'],
              ['value'    => '0',
               'expected' => false,
               'message'  => 'string(0)'],
              ['value'    => true,
               'expected' => true,
               'message'  => 'bool(true)'],
              ['value'    => 1,
               'expected' => true,
               'message'  => 'int(1)'],
              ['value'    => '1',
               'expected' => true,
               'message'  => 'string(1)']];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
