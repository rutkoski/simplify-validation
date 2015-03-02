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
 * Validate string length
 *
 */
class Length extends \Simplify\Validation\AbstractValidation
{

  /**
   * Min length
   *
   * @var int
   */
  public $min;

  /**
   * Max length
   *
   * @var int
   */
  public $max;

  /**
   *
   * @param string $message
   * @param int|boolean $min minimun string length or false for no limit
   * @param int|boolean $max maximun string length or false for no limit
   */
  public function __construct($message, $min = false, $max = false, $required = null)
  {
    parent::__construct($message);

    $this->min = $min;
    $this->max = $max;
    $this->required = $required;
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

    if (($this->min !== false && mb_strlen($value) < $this->min) || ($this->max !== false && mb_strlen($value) > $this->max)) {
      $this->fail();
    }
  }

}
