<?php

class Validator
{
    private $_passed = false,
        $_errors = array();
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                if($rule == 'required' && !isset($source[$item])){
                    $this->addError('422',"Miss parameters");
                    break;
                }
                $value = $source[$item];
                if ($rule === 'required' && empty($value)) {
                    $this->addError($item, "{$item} is required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError($item, "{$item} must be a minimum of {$rule_value}");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError($item, "{$item} must be a maximum of {$rule_value}");
                            }
                            break;
                        case 'enum':
                            $pass = false;
                            foreach ($rule_value as $enum_value) {
                                if ($value == $enum_value) {
                                    $pass = true;
                                 }
                            }
                            if(!$pass){
                                $this->addError($value, "The lenguage you selected is not valid");
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
