<?php

namespace Vendor\PhpMa\Http\Forms;

use Vendor\PhpMa\Core\Validator;
use Vendor\PhpMa\Core\LanguageValidator;

class InputValidation{

    protected $errors = [];
    protected $min = 1;
    protected $max = 250;
    
    public function __construct(public array $params){
        $this->validate($params);
    }

    public function validate($params = []){
        foreach($params as $key => $value){
            $error_validation = new LanguageValidator($key);
            
            if(!is_array($value)){
                if(!Validator::string($value)){
                    $errorMessage = $error_validation->langauge_validation();
                    $this->errors[$key][] = $errorMessage;
                }

                if(str_contains($key, 'name')){
                    if(!Validator::text($value)){
                        $errorMessage = $error_validation->langauge_validation('text');
                        $this->errors[$key][] = $errorMessage;
                    }
                }

                if(strlen($value) > 0){
                    if(str_contains($key, 'phone')){
                        if(!Validator::numeric($value)){
                            $errorMessage = $error_validation->langauge_validation('phone_number');
                            $this->errors[$key][] = $errorMessage;
                        }
                    }
                }

                if($key === 'confirm_password' && strlen($value) > 0){
                    if(!Validator::confirm($params['password'], $params['confirm_password'])){
                        $errorMessage = $error_validation->langauge_validation('confirm');
                        $this->errors[$key][] = $errorMessage;
                    }
                }

                if($key === 'password'){
                    $this->password_validation($value, $key);
                }
            }
        }
    }

    protected function password_validation($value, $key){
        $error_validation = new LanguageValidator($key);

        if(strlen($value) > 0){
            if(!Validator::minimum_strings($value, $this->min = 8)){
                $errorMessage = $error_validation->langauge_validation('min', $this->min);
                $this->errors[$key][] = $errorMessage;
            }else if(!Validator::check_password($value) && !include_urls('login')){
                $errorMessage = $error_validation->langauge_validation('password');
                $this->errors[$key][] = $errorMessage;
            }
        }
    }
    
    public function getErrors(){
        return $this->errors;
    }
}