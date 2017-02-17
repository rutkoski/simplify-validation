<?php

/**
 * SimplifyPHP Framework
 *
 * This file is part of SimplifyPHP Framework.
 *
 * SimplifyPHP Framework is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * SimplifyPHP Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Rodrigo Rutkoski Rodrigues <rutkoski@gmail.com>
 * @copyright Copyright 2008 Rodrigo Rutkoski Rodrigues
 */

namespace Simplify\Validation;

/**
 * 
 * Validate that value exists in a given set of elements
 *
 */
class Enum extends \Simplify\Validation\AbstractValidation
{

  /**
   * Set of valid elements
   *
   * @var mixed[]
   */
  public $enum;

  /**
   *
   * @var boolean
   */
  public $negate;

  /**
   *
   * @var boolean
   */
  public $keys;
  
  /**
   * Constructor
   * 
   * @param string $message
   * @param mixed[] $enum
   * @param boolean $negate
   */
  public function __construct($message, array $enum = null, $negate = false, $keys = false)
  {
    parent::__construct($message);
    
    $this->enum = $enum;
    $this->negate = $negate;
    $this->keys = $keys;
  }

  /**
   * (non-PHPdoc)
   * @see \Simplify\ValidationInterface::validate()
   */
  public function validate($value)
  {
    if ($this->required($value)) {
      return;
    }

    $enum = (array) $this->enum;

    if ($this->keys) {
      if ($this->negate) {
        $fail = isset($enum[$value]);
      } else {
        $fail = !isset($enum[$value]);
      }
    } else {
      if ($this->negate) {
        $fail = in_array($value, $enum);
      } else {
        $fail = !in_array($value, $enum);
      }
    }

    if ($fail) {
      $this->fail();
    }
  }

}
