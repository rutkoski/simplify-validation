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
 * @author Rodrigo Rutkoski Rodrigues, <rutkoski@gmail.com>
 */

namespace Simplify\Validation;

/**
 *
 * Validates data
 *
 */
class DataValidation
{

  /**
   * Validation errors
   *
   * @var string[]
   */
  protected $errors;

  /**
   * Validation rules
   *
   * @var \Simplify\ValidationInterface[]
   */
  protected $rules = array();

  /**
   * Constructor
   *
   * @param mixed[] $rules
   */
  public function __construct(array $rules = null)
  {
    if (is_array($rules)) {
      $this->parse($rules);
    }
  }

  /**
   *
   * @return \Simplify\Validation\DataValidation
   */
  public static function parseFrom($rules)
  {
    if (!($rules instanceof \Simplify\Validation\DataValidation)) {
      $rules = new self($rules);
    }

    return $rules;
  }

  /**
   * Get validation errors
   *
   * @return string[]
   */
  public function getErrors()
  {
    return $this->errors;
  }

  /**
   * Set a validation rule for a given key in a data set
   *
   * @param string $name
   * @param \Simplify\ValidationInterface $rule validation rule
   */
  public function setRule($name, \Simplify\ValidationInterface $rule)
  {
    if (!isset($this->rules[$name])) {
      $this->rules[$name] = array();
    }

    $this->rules[$name][] = $rule;
  }

  /**
   * Validates all or specific key in data set
   *
   * @param mixed[] $data data set
   * @param string $name key in data set
   * @param boolean $stepValidation if true, only one error will be returned per key
   * @throws \Simplify\ValidationException
   */
  public function validate(&$data, $name = null, $stepValidation = true)
  {
    $errors = array();

    if (empty($name)) {
      foreach ($this->rules as $name => $rules) {
        try {
          $this->validate($data, $name, $stepValidation);
        }
        catch (\Simplify\ValidationException $e) {
          $errors += $e->getErrors();
        }
      }
    }
    else {
      if (isset($this->rules[$name])) {
        foreach ($this->rules[$name] as $rule) {
          try {
            $rule->validate(sy_get_param($data, $name));
          }
          catch (\Simplify\ValidationException $e) {
            if ($stepValidation) {
              $errors[$name] = $e->getErrors();
              break;
            } else {
              $errors[$name][] = $e->getErrors();
            }
          }
        }
      }
    }

    $this->errors = $errors;

    if (!empty($errors))
      throw new \Simplify\ValidationException($errors);
  }

  /**
   *
   * @param mixed[] $rules
   */
  protected function parse($rules)
  {
    foreach ($rules as $name => $rule) {
      if (empty($rule)) {
        continue;
      }
      if ($rule instanceof \Simplify\ValidationInterface) {
          $this->setRule($name, $rule);
      }
      elseif (is_array($rule[0])) {
        foreach ($rule as $_rule) {
          if (is_array($_rule)) {
            $this->setRule($name, $this->factory($_rule[0], $_rule[1], sy_get_param($_rule, 2)));
          }
          else {
            $this->setRule($name, $_rule);
          }
        }
      }
      elseif (!($rule instanceof \Simplify\ValidationInterface)) {
        $this->setRule($name, $this->factory($rule[0], $rule[1], sy_get_param($rule, 2)));
      }
      else {
        $this->setRule($name, $rule);
      }
    }
  }

  /**
   * Factory validation rule
   *
   * @param string $rule rule class
   * @param string $message
   * @param mixed[string] $params
   * @throws Exception
   * @return \Simplify\ValidationInterface
   */
  protected function factory($rule, $message, array $params = null)
  {
    $Rule = new $rule($message);

    foreach ((array) $params as $param => $value) {
      $Rule->$param = $value;
    }

    return $Rule;
  }

}
