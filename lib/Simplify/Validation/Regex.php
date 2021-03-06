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
 * Use a regular expression to validate data
 *
 */
class Regex extends \Simplify\Validation\AbstractValidation
{

  /**
   * Regular expression
   *
   * @var string
   */
  public $regex;

  /**
   * Constructor
   *
   * @param string $message validation fail message
   * @param string $regex regular expression
   */
  public function __construct($message, $regex = null, $required = true)
  {
    parent::__construct($message);

    $this->regex = $regex;
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

    if (!preg_match($this->regex, $value)) {
      $this->fail();
    }
  }

}
