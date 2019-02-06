<?php

namespace SetBased\Abc\Helper\Test;

/**
 * Class with __toString method.
 */
class HelloWithToString
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a greeting.
   *
   * @return string
   */
  public function __toString(): string
  {
    return 'Hello, world';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
