<?php

class Validator
{
    private $_passed = false,
        $_errors = array();
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = $source[$item];
                if ($rule === 'required' && $value == null) {
                    $this->addError($item, "{$item} is required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'numeric':
                        if (!is_numeric($value)) {
                            $this->addError(422, "{$item} must be a number");
                        }

                        break;
                        case 'min':
                            if ($value < $rule_value) {
                                $this->addError(422, "{$item} must be a minimum of {$rule_value}");
                            }
                            break;
                        case 'max':
                            if ($value > $rule_value) {
                                $this->addError(422, "{$item} must be a maximum of {$rule_value}");
                            }
                            break;
                        case 'enum':
                            $pass = false;
                            foreach ($rule_value as $enum_value) {
                                if ($value == $enum_value) {
                                    $pass = true;
                                }
                            }
                            if (!$pass) {
                                $this->addError(422, "The lenguage you selected is not valid");
                            }

                            break;
                    }
                }
            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }
    public function addError($item, $error)
    {
        $this->_errors[$item] = $error;
    }
    public function getErrors()
    {
        return $this->_errors;
    }
    public function getPassed()
    {
        return $this->_passed;
    }
}
