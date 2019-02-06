<?php
declare(strict_types=1);

namespace SetBased\Abc\Helper\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Abc\Helper\Cast;
use SetBased\Abc\Helper\CastException;

/**
 * Test cases with floats for Cast.
 */
class CastFloatTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory floats.
   */
  public function testIsManFloatWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptFloatCases();
    $cases[] = ['value'   => null,
                'message' => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isManFloat($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid optional floats.
   */
  public function testIsOptFloatWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptFloatCases();

    foreach ($cases as $case)
    {
      $test = Cast::isOptFloat($case['value']);
      self::assertFalse($test, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid mandatory floats.
   */
  public function testManIntWithValidValues(): void
  {
    $cases = $this->getValidManFloatCases();

    foreach ($cases as $case)
    {
      $test = Cast::isManFloat($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toManFloat($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with valid optional floats.
   */
  public function testOptFloatWithValidValues(): void
  {
    $cases   = $this->getValidManFloatCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      $test = Cast::isOptFloat($case['value']);
      self::assertTrue($test, $case['message']);

      $casted = Cast::toOptFloat($case['value']);
      self::assertSame($case['expected'], $casted, $case['message']);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases with invalid mandatory floats.
   */
  public function testToManFloatWithInvalidValues(): void
  {
    $cases   = $this->getInvalidOptFloatCases();
    $cases[] = ['value'    => null,
                'expected' => null,
                'message'  => 'NULL'];

    foreach ($cases as $case)
    {
      try
      {
        Cast::toManFloat($case['value']);

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
   * Test cases with invalid mandatory floats.
   */
  public function testToOptFloatWithInvalidValues(): void
  {
    $cases = $this->getInvalidOptFloatCases();

    foreach ($cases as $case)
    {
      try
      {
        Cast::toOptFloat($case['value']);

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
   * Returns invalid optional int test cases.
   *
   * @return array
   */
  private function getInvalidOptFloatCases(): array
  {
    $cases = [['value'   => '',
               'message' => "string('')"],
              ['value'   => 'abc',
               'message' => "string(abc)"],
              ['value'   => '123  ',
               'message' => 'string(123  )'],
              ['value'   => '123.456  ',
               'message' => 'string(123.45  )'],
              ['value'   => '0.0  ',
               'message' => 'string(0.0  )'],
              ['value'   => '00.0',
               'message' => 'string(00.0)'],
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
  private function getValidManFloatCases(): array
  {
    $cases = [['value'    => 123.45,
               'expected' => 123.45,
               'message'  => 'float(123.45)'],
              ['value'    => 0,
               'expected' => 0.0,
               'message'  => 'int(0)'],
              ['value'    => 1,
               'expected' => 1.0,
               'message'  => 'int(1)'],
              ['value'    => '0',
               'expected' => 0.0,
               'message'  => 'string(0)'],
              ['value'    => '0.0',
               'expected' => 0.0,
               'message'  => 'string(0.0)'],
              ['value'    => '0.1',
               'expected' => 0.1,
               'message'  => 'string(0.1)'],
              ['value'    => '123.45',
               'expected' => 123.45,
               'message'  => 'string(123.45)']];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
