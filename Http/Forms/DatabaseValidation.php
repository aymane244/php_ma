<?php

namespace Http\Forms;

use Core\Validator;
use Core\LanguageValidator;

class DatabaseValidation{

    protected $errors = [];
    protected $min = 1;
    protected $max = 250;
    protected $user_type;

    public function __construct(public array $params, $user_type){
        $this->validate($params);
        $this->user_type = $user_type;
    }

    public function validate($params = []){
        foreach($params as $key => $value){
            $error_validation = new LanguageValidator($key);

            if(!is_array($value)){
                if(str_contains($key, 'mail') && strlen($value) > 0 && (!include_urls('login') && !include_urls('forgot') && !include_urls('message'))){
                    if(!Validator::check_email($value, $this->user_type)){
                        $errorMessage = $error_validation->langauge_validation('check_email');
                        $this->errors[$key][] = $errorMessage;
                    }
                }

                if(str_contains($key, 'mail') && strlen($value) > 0 && include_urls('forgot') && !include_urls('message')){
                    if(!Validator::matched_email($value, $this->user_type)){
                        $errorMessage = $error_validation->langauge_validation('matched_email');
                        $this->errors[$key][] = $errorMessage;
                    }
                }

                if(str_contains($key, 'phone')){
                    $this->phone_validation($value, $key);
                }
            }
        }
    }

    public function getErrors(){
        return $this->errors;
    }

    protected function phone_validation($value, $key){
        $error_validation = new LanguageValidator($key);

        if(strlen($value) > 0){
            if(!Validator::check_phone($value, $this->user_type)){
                $errorMessage = $error_validation->langauge_validation('check_phone');
                $this->errors[$key][] = $errorMessage;
            }
        }
    }
}