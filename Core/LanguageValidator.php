<?php

namespace Core;

use Core\Session;

class LanguageValidator{

    protected $key;

    public function __construct($key){
        $this->key = translate($key);
    }

    public  function langauge_validation($method = '', $number = 1){
        $lang = Session::language("lang");

        switch($lang){
            case 'en':
                return $this->english_validation($this->key, $method, $number);
            case 'fr':
                return $this->french_validation($this->key, $method, $number);
            case 'ar':
                return $this->arab_validation($this->key, $method, $number);
            default:
                return $this->arab_validation($this->key, $method, $number);
        }
    }

    private function english_validation($key, $method, $number){
        $replacedKey = str_replace('_', ' ', $key);

        switch($method){
            case 'text':
                return 'Field ' . $replacedKey . ' must be a text.';
            case 'numeric':
                return 'Field ' . $replacedKey . ' must be numeric.';
            case 'confirm':
                return "Password does not match.";
            case 'min':
                return 'Field ' . $replacedKey . ' must of ' . $number . ' characters or more.';
            case 'max':
                return 'Field ' . $replacedKey . ' must ' . $number . ' characters or less.';
            case 'exact':
                return 'Field ' . $replacedKey . ' must be of ' . $number . ' characters.';
            case 'password':
                return 'Field ' . $replacedKey . ' must have at least one capital letter, one number and one special character (@,?, !).';
            case 'check_email':
                return "Email address already registered with another user, please try again.";
            case 'matched_email':
                return "Email address does not exists in our database.";
            case 'phone_number':
                return "Invalid phone number, please try again.";
            case 'check_phone':
                return "Phone number already registered with another user, please try again.";
            case 'cin':
                return "The " . $replacedKey . " must start with a letter and be followed by numbers example :A7123.";
            case 'check_cin':
                return "ID card number already registered with another user, please try again.";
            default:
                return "Field ". $replacedKey . " is required.";
        }
    }

    private function french_validation($key, $method, $number){
        $replacedKey = str_replace('_', ' ', $key);

        switch($method){
            case 'text':
                return 'Le champ ' . $replacedKey . ' doit être un texte.';
            case 'numeric':
                return 'Le champ ' . $replacedKey . ' doit être doit être un chiffre.';
            case 'confirm':
                return "Le mot de passe ne correspond pas.";
            case 'min':
                return 'Le champ ' . $replacedKey . ' doit avoir ' . $number . ' caractères ou plus.';
            case 'max':
                return 'Le champ ' . $replacedKey . ' doit avoir ' . $number . ' caractères ou moins.';
            case 'exact':
                return 'Le champ ' . $replacedKey . ' doit comporter ' . $number . ' caractères.';
            case 'password':
                return "Le champ " . $replacedKey . " doit avoir au moins une lettre majuscule, un chiffre et un caractère spécial (@, ?, !).";
            case 'check_email':
                return "Addresse email déjà enregistré avec un autre utilisateur, veuillez réessayer.";
            case 'matched_email':
                return "Addresse email n'existe pas dans notre base de données.";
            case 'phone_number':
                return "Numéro de téléphone invalide, veuillez réessayer.";
            case 'check_phone':
                return "Numéro de téléphone déjà enregistré avec un autre utilisateur, veuillez réessayer.";
            case 'cin':
                return "Le " . $replacedKey . " doit commencer par une lettre et être suivi de chiffres example :A7123 .";
            case 'check_cine':
                return "Numéro d'étudiant CINE déjà enregistré avec un autre étudiant, veuillez réessayer.";
            default:
                return "Champ ". $replacedKey . " est obligatoire.";
        }
    }

    private function arab_validation($key, $method, $number){
        $replacedKey = str_replace('_', ' ', $key);

        switch($method){
            case 'text':
                return 'يجب أن تكون الخانة ' . $replacedKey . ' نصًا.';
            case 'numeric':
                return 'يجب أن تكون الخانة ' . $replacedKey . ' رقما.';
            case 'confirm':
                return "كلمة المرور غير متطابقة.";
            case 'min':
                return 'يجب أن تكون خانة ' . $replacedKey . ' من ' . $number . ' أحرف أو أكثر.';
            case 'max':
                return 'يجب أن تكون خانة ' . $replacedKey . ' من ' . $number . ' أحرف أو أقل.';
            case 'exact':
                return 'يجب أن تحتوي خانة ' . $replacedKey . ' على ' . $number . ' أحرف.';
            case 'password':
                return  "يجب أن تحتوي خانة " . $replacedKey . " على الأقل على حرف كبير واحد ورقم وحرف خاص (@، ؟، !).";
            case 'check_email':
                return "البريد الإلكتروني مُسجل مع مستخدم آخر، يرجى المحاولة مرة أخرى.";
            case 'matched_email':
                return "البريد الإلكتروني غير مُسجل  في بياناتنا.";
            case 'phone_number':
                return "رقم الهاتف غير صحيح، يرجى المحاولة مرة أخرى.";
            case 'check_phone':
                return "رقم الهاتف مُسجل مع مستخدم آخر، يرجى المحاولة مرة أخرى.";
            case 'cin':
                return "يجب أن يبدأ " . $replacedKey . " بحرف ويتبعه أرقام، على سبيل المثال: A7123.";
            default:
                return "خانة ". $replacedKey . " إلزامية.";
        }
    }
}