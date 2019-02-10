<?php

namespace SetBased\Abc\Helper\Test;

/**
 * This is not a float.
 */
class NoFloat
{
//----------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the string representation.
   *
   * @return string
   */
  function __toString()
  {
    return '1.0';
  }
}

//----------------------------------------------------------------------------------------------------------------------
