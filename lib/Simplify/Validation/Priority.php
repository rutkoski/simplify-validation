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
 * Organize validation rules by priority
 * Priority 0 is highest, positive numbers are lower priority
 * 
 */
class Priority extends \Simplify\Validation\AbstractValidation
{

  /**
   * Validation rules
   * 
   * @var array
   */
  private $rules = array();

  /**
   * Holds the last rule that failed
   * If validation was successfull, this value is null
   * 
   * @var Simplify_ValidationInterface
   */
  private $lastRule;

  /**
   * 
   * @return unknown_type
   */
  public function __construct()
  {
    $this->rules = array();
  }

  /**
   * (non-PHPdoc)
   * @see \Simplify\Validation\AbstractValidation::getError()
   */
  public function getError()
  {
    return $this->getLastRule() ? $this->getLastRule()->getError() : null;
  }

  /**
   * Get the last rule that failed
   * 
   * @return Simplify_ValidationInterface
   */
  public function getLastRule()
  {
    return $this->lastRule;
  }

  /**
   * (non-PHPdoc)
   * @see \Simplify\ValidationInterface::validate()
   */
  public function validate($value)
  {
    $this->lastRule = null;
    
    foreach ($this->rules as $priority => $rules) {
      foreach ($rules as $rule) {
        try {
          $rule->validate($value);
        }
        
        catch (\Simplify\ValidationInterface $e) {
          $this->lastRule = $rule;
          $this->fail();
        }
      }
    }
  }

  /**
   * Add a rule to the chain.
   * 
   * @param \Simplify\ValidationInterface $rule validation rule
   * @param int $priority priority level for rule
   * @return \Simplify\Validation\Priority
   */
  public function addRule(\Simplify\ValidationInterface $rule, $priority = 0)
  {
    if (!isset($this->rules[$priority])) {
      $this->rules[$priority] = array();
    }
    
    $this->rules[$priority][] = $rule;
    
    return $this;
  }

}
