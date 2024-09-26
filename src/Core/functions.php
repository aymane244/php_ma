<?php

use Vendor\PhpMa\Core\App;
use Vendor\PhpMa\Core\Database;
use Vendor\PhpMa\Core\Response;
use Vendor\PhpMa\Core\Session;

function dd($val){
    echo "<pre>";
        var_dump($val);
    echo "</pre>";
    die();
}

function urls($val){
    return $_SERVER['REQUEST_URI'] === $val;
}

function include_urls($val){
    return str_contains($_SERVER['REQUEST_URI'], $val);
}

function abort($status = 404){
    http_response_code($status);
    require base_path("views/".$status.".php");
    die();
}

function autorize($condition, $status = Response::FORBIDDEN){
    if(!$condition){
        abort($status);
    }
}

function base_path($path){
    return BASE_PATH . $path;
}

function asset($path){
    return BASE_URL . '/' . $path;
}

function view($path, $attributes = []){
    extract($attributes);
    return require base_path('views/'. $path);
}

function redirect($path, $attributes = []){
    extract($attributes);
    header('location:'. $path);
    exit();
}

function old($key, $default = ''){
    return Session::get('old')[$key] ?? $default;
}

function error($key){
    return Session::get('errors')[$key] ?? '';
}

function success($key){
    return Session::get($key) ?? '';
}

function sanitize($data){
    if(isset($_POST[$data])){
        if(is_array($_POST[$data])){
            return array_map(function($value){
                return htmlspecialchars(strtolower($value), ENT_QUOTES, 'UTF-8');
            }, $_POST[$data]);
        }else{
            $lower_name = strtolower($_POST[$data]);
            return htmlspecialchars($lower_name, ENT_QUOTES, 'UTF-8');
        }
    }
    
    return null;
}

function language_code($lang){
    if(isset($_SESSION['_lang'])){
        return $_SESSION['_lang'] === $lang;
    }else{
        return $lang === "ar";
    }
}

function dir_html(){
    if(isset($_SESSION['_lang'])){
        return $_SESSION['_lang'] === "ar" ? 'dir="rtl"' : '';
    }
}

function arabic_language(){
    if(isset($_SESSION['_lang'])){
        return $_SESSION['_lang'] === "ar";
    }
}

function translate($value, $input = ""){
    $langSession = Session::language('lang');
    $lang = [];
    require base_path("lang/".$langSession.".php");
    $translatedValue = isset($lang[$value]) ? $lang[$value] : $value;
    $translatedValue = str_replace(':input', $input, $translatedValue);
    
    return $translatedValue;
}

function sanitize_files($fileInput){
    if(isset($_FILES[$fileInput])){
        $file = $_FILES[$fileInput];
        $fileName = $file['name'];
        $fileError = $file['error'];

        if(is_array($file)){
            $validFiles = [];
            for($i=0; $i<count($fileName); $i++){
                if($fileError[$i] === UPLOAD_ERR_OK){
                    $validFiles[] = $fileName[$i];
                }
            }

            return $validFiles;
        }
    }

    return [];
}

function sanitize_file($fileInput){
    if(isset($_FILES[$fileInput])){
        $file = $_FILES[$fileInput];
        $fileName = $file['name'];
        $fileError = $file['error'];

        if($fileError === UPLOAD_ERR_OK){
            return [$fileName];
        }
    }

    return false;
}

function tmp_file($fileInput){
    if(isset($_FILES[$fileInput])){
        $fileTmpName = $_FILES[$fileInput]['tmp_name'];
        $fileError = $_FILES[$fileInput]['error'];

        if(is_array($fileTmpName)){
            $validTmpFiles = [];
            for($i=0; $i<count($fileTmpName); $i++){
                if($fileError[$i] === UPLOAD_ERR_OK){
                    $validTmpFiles[] = $fileTmpName[$i];
                }
            }
            return $validTmpFiles;
        }else{
            if($fileError === UPLOAD_ERR_OK){
                return [$fileTmpName];
            }
        }
    }

    return [];
}

function file_name($file){
    $lower_name = strtolower($file);
    $removeExtension = explode(".", $lower_name); 
    $splited_word = implode(".", array_slice($removeExtension, 0, -1));
    $uniqueID = uniqid('', true);
    $extension = pathinfo($lower_name, PATHINFO_EXTENSION);
    $final_name = str_replace(' ', '_', $splited_word) . '_' . $uniqueID . '.' . $extension;

    return $final_name;
}

function move_file($tmp_file, $folder, $file_name){
    $name = $file_name;
    $path_file = base_path("public/".$folder."/");
    $final_file_path = $path_file.$name;

    if(!file_exists($path_file) && !is_dir($path_file)){
        mkdir($path_file, 0777, true);       
    }
    
    return move_uploaded_file($tmp_file, $final_file_path);
}

function delete_file($folder, $file){
    $path_file = base_path("public/".$folder."/".$file);

    if(file_exists($path_file)){
        return unlink($path_file);
    }

    return false;
}

function capital($text){
    if(strlen($text) > 0){
        $splited_text = str_split($text); 
        $capital_first_letter = strtoupper($splited_text[0]);
        $rest_of_text = implode('', array_slice($splited_text, 1));
        $final_text = $capital_first_letter.$rest_of_text;
        
        return $final_text;
    }
}

function csrf_token(){
    if(empty($_SESSION['csrf_token'])){
        $_SESSION['csrf_token'] = bin2hex(random_bytes(35));
        return $_SESSION['csrf_token'];
    }
}

function csrf_field(){
    return '<input type="hidden" name="_token" class="_token" value="' . $_SESSION['csrf_token'] . '">';
}

function verify_csrf_token($token){
    return hash_equals($_SESSION['csrf_token'], $token);
}

function page_name($name){
    return $name;
}

function is_user($user_type){
    return Session::has($user_type);
}

function user($user_type){
    $table = $user_type !== "admin" ? $user_type . "s" : $user_type;
    $db = App::resolve(Database::class);
    if(is_user($user_type)){
        return $db->select($table)
        ->where([$user_type.'_id' => $_SESSION[$user_type]['id']])
        ->first();
    }
}

function langMonths($month, $lang){
    $months = [
        "ar" => [
            "January" => "يناير",
            "February" => "فبراير",
            "March" => "مارس",
            "April" => "أبريل",
            "May" => "ماي",
            "June" => "يونيو",
            "July" => "يوليوز",
            "August" => "غشت",
            "September" => "شتنبر",
            "October" => "أكتوبر",
            "November" => "نونبر",
            "December" => "ديسمبر"
        ],
        "fr" => [
            "January" => "Janvier",
            "February" => "Février",
            "March" => "Mars",
            "April" => "Avril",
            "May" => "Mai",
            "June" => "Juin",
            "July" => "Juillet",
            "August" => "Août",
            "September" => "Septembre",
            "October" => "Octobre",
            "November" => "Novembre",
            "December" => "Décembre"
        ]
    ];

    // Fallback messages
    $fallback = [
        "ar" => 'مجهول',
        "fr" => 'Inconnue'
    ];

    // Check if the selected language exists
    if(!isset($months[$lang])){
        return $fallback[$lang] ?? 'Unknown';
    }

    // Handle month/year format
    if(strpos($month, '/') !== false){
        list($monthName, $year) = explode('/', $month);
        return isset($months[$lang][$monthName]) ? $months[$lang][$monthName] . "/" . $year : $fallback[$lang];
    }

    // Return translated month or fallback message
    return isset($months[$lang][$month]) ? $months[$lang][$month] : $fallback[$lang];
}