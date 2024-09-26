<?php

namespace Http\Forms;

use Core\ValidationException;

class FormValidation{

    protected $errors = [];
    protected $success = [];
    protected $user_type;

    public function __construct(public array $params, $user_type){
        $validation = new InputValidation($params);
        $this->errors = $validation->getErrors();
        $this->user_type = $user_type;
        $database_validation = new DatabaseValidation($params, $this->user_type);
        $this->errors = array_merge($this->errors, $database_validation->getErrors());
    }

    public static function validation($attributes, $user_type){
        $instance = new static($attributes, $user_type);
        return $instance->failed() ? $instance->throw() : $instance;
    }

    public static function validation_ajax($attributes, $user_type){
        $instance = new static($attributes, $user_type);
        return $instance;
    }
    
    public function errors(){
        $error = [];
        
        foreach ($this->errors as $key => $value){
            if(is_array($value)){
                if(empty($value) || is_array(current($value))){
                    $error[$key] = $value;
                }else{
                    $error[$key] = implode(' ', $value);
                }
            }else{
                $error[$key] = $value;
            }
        }
        return $error;
    }

    public function throw(){
        ValidationException::throw($this->errors(), $this->params);
    }

    public function failed(){
        return count($this->errors) > 0;
    }

    public function unique_error($field, $message){
        $this->errors[$field][] = $message;

        return $this;
    }

    public function success($field, $message){
        $this->success[$field][] = $message;
        
        return $this;
    }

    public function old(){
        return $this->params;
    }
}