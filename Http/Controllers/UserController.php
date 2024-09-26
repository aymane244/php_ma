<?php

namespace Http\Controllers;

use Core\Authenticator;
use Core\Middleware\Auth;
use Core\Session;
use Http\Forms\FormValidation;
use Http\Utility\Utilities;
use ForgotPasswordMail;
use VerificationMail;

class UserController extends Utilities{

    public function index(){
        $name = 'Benutzeranmeldung';
        return view("users/login.view.php", [
            "name" => $name,
        ]);
    }

    public function create(){
        $name = "Benutzerregistrierung";
        return view("users/signup.view.php", [
            'errors' => Session::get('errors'),
            'success' => Session::get('success'),
            'failed' => Session::get('failed'),
            'database' => Session::get('database'),
            "name" => $name,
        ]);
    }

    public function store(){
        $first_name = sanitize('first_name');
        $user_category = sanitize('user_category');
        $last_name = sanitize('last_name');
        $email = sanitize('email');
        $phone = sanitize('phone');
        $code = sanitize('code');
        $company = sanitize('company_name');
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16));
    
        if(strpos($phone, "0") !== 0){
            $phone = "0".$phone;
        }
    
        $validationData = [
            'vorname' => $_POST['first_name'],
            'nachname' => $_POST['last_name'],
            'email' => $_POST['email'],
            'telefonnummer' => $_POST['phone'],
            'user_category' => $_POST['user_category'],
            'passwort' => $_POST['password'],
            'passwort_bestätigen' => $_POST['confirm'],
        ];
    
        if($user_category === "1"){
            $validationData['firmenname'] = $_POST['company_name'];
        }
    
        if($user_category === "2"){
            $validationData['code'] = $_POST['code'];
        }
        $validation = FormValidation::validation($validationData);
    
        if(!$validation->failed()){
            try{
                $this->db->beginTransaction();
                $user = $this->db->insert('users', [
                    "user_first_name" => $first_name,
                    "user_last_name" => $last_name,
                    "user_email" => $email,
                    "user_phone" => $phone,
                    "email_verified_at" => date("Y-m-d H:i:s"),
                    "user_password" => $password,
                    "is_manager" => $user_category,
                    "user_token" => $token,
                ]);
    
                if($user){
                    if($user_category === "1"){
                        $manager = $this->db->insert('managers', [
                            "manager_user" => $user->id,
                            "manager_company" => $company,
                        ]);
    
                        if(!$manager){
                            Session::flash('failed', 'Sie wurden nicht registriert. Bitte versuchen Sie es erneut');
                            $this->db->rollBack();
                            redirect('/user/signup');
                        }
                    }else if($user_category === "0"){
                        $manager = $this->db->select("managers")->where(['code' => $code])->first();
    
                        if($manager){
                            $worker = $this->db->insert('workers', [
                                "worker_user" => $user->id,
                                "worker_manager" => $manager->manager_user,
                            ]);
    
                            if(!$worker){
                                Session::flash('failed', 'Sie wurden nicht registriert. Bitte versuchen Sie es erneut');
                                $this->db->rollBack();
                                redirect('/user/signup');
                            }
                        }else{
                            Session::flash('failed', 'Ungültiger Manager-Code, bitte versuchen Sie es erneut');
                            $this->db->rollBack();
                            redirect('/user/signup');
                        }
                    }
    
                    $this->db->commit();
                    $sender = "aymane.chnaif@gmail.com";
                    $mail = new VerificationMail();
                    $sent = $mail->send_verification_mail(capital($first_name), capital($last_name), $sender, $email, $token);
    
                    if($sent){
                        Session::flash('success', 'Sie wurden erfolgreich registriert. Bitte überprüfen Sie Ihre E-Mail, um Ihr Konto zu bestätigen.');
                        redirect('/user/login');
                    }else{
                        Session::flash('failed', 'Fehler beim Senden der Bestätigungs-E-Mail. Bitte versuchen Sie es erneut');
                        $this->db->rollBack();
                        redirect('/user/signup');
                    }
                }else{
                    Session::flash('failed', 'Sie wurden nicht registriert. Bitte versuchen Sie es erneut');
                    $this->db->rollBack();
                    redirect('/user/signup');
                }
            }catch(\Exception $e){
                $this->db->rollBack();
                Session::flash('database', 'Database problem: ' . $e->getMessage());
                redirect('/user/signup');
            }
        }
    }

    public function check_code(){
        $code = sanitize('code');
        $codes = $this->db->select("managers")->where(['code' => $code])->get();
        $manager = $this->db->select("managers")->where(['code' => $code])->first();

        if(count($codes) > 0){
            echo json_encode(["success" => "Der Code existiert. Sind Sie ein Mitarbeiter von ". capital($manager->manager_company)."?"]);
        }else{
            echo json_encode(["error" => "Der Code existiert nicht in unserer Datenbank. Bitte kontaktieren Sie Ihren Arbeitgeber für weitere Informationen"]);
        }
    }
    
    public function verify(){
        if(isset($_GET['email']) && isset($_GET['token'])){
            $email = $_GET['email'];
            $name = 'Benutzerüberprüfung';
            $user = $this->db->select("users")->where(['user_email' => $email])->first();
            
            if($user){
                $this->db->update("users", [
                    "email_verified_at" => date("Y-m-d H:i:s"),
                ], ['user_email' => $email]);

                return view("users/verify/index.view.php",[
                    'name' => $name,
                ]);
            }else{
                return view("users/verify/error.view.php",[
                    'name' => $name,
                ]);;
            }
        }
    }

    public function login(){
        $auth = new Authenticator();
        
        $form = FormValidation::validation($attributes = [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
        ]);
        
        if(!$auth->attempt($attributes['email'], $attributes['password'])){
            $form->unique_error('failed', 'E-Mail oder Passwort stimmen nicht mit unseren Anmeldedaten überein')->throw();
        }else{
            if(!$auth->verify($attributes['email'])){
                $form->unique_error('failed', 'Bitte überprüfen Sie Ihre E-Mail, und überprüfen Sie Ihre E-Mail-Adresse')->throw();
            }
            $user = $this->db->select("users")->where(['user_email' => $attributes['email']])->first();
            
            if($user->is_manager == 1){
                redirect('/user/manager/dashboard');
            }else{
                redirect('/user/worker/dashboard');
            }
        }
    }

    public function log(){
        $email = sanitize("email");
        $password = $_POST['password'];
        $auth = new Authenticator();
        $response = [];
        $user = $this->db->select("users")->where(['user_email' => $email])->first();

        if($user->is_manager == 1){
            if(!$auth->attempt($email, $password)){
                $response["error"] = 'E-Mail oder Passwort stimmen nicht mit unseren Anmeldedaten überein'; 
            }else{
                $demand = $this->db->select("demands")->where(['demand_user' => Auth::id()])->first();
                if(!$auth->verify($email)){
                    $response["error"] = 'Bitte überprüfen Sie Ihre E-Mail, und überprüfen Sie Ihre E-Mail-Adresse';
                }else{
                    $response = [
                        "status" => "success",
                        "user" => $demand,
                    ];
                }
            }
        }else{
            $response["error"] = 'Nur der Manager kann auf diese Seite zugreifen';
        }

        echo json_encode($response);
    }

    public function dashboard(){
        $name = 'Dashboard';
        $month = date("m");
        $monthName = date("F");
        $year = date("Y");
        
        $plans = $this->db->select("plans")
        ->innerJoin("workers", "plan_manager", "=", "worker_manager")
        ->where(["worker_user" => AuthWorker::id()])
        ->limit(10)
        ->get();

        $schedule = $this->db->select("user_time", ["MONTH(time_date) as month", "SUM(time_working) as total_hours"])
        ->innerJoin("times", "time_user", "=", "time_id")
        ->where(["user_worker" => AuthWorker::id(), "YEAR(time_date)" => $year, "MONTH(time_date)" => $month])
        ->groupBy("MONTH(time_date)")
        ->first();
        
        return view("users/worker/index.view.php",[
            "name" => $name,
            "plans" => $plans,
            "schedule" => $schedule,
            "month" => $monthName,
        ]);
    }

    public function plans(){
        $name = 'Pläne';

        return view("users/worker/plans/index.view.php",[
            "name" => $name,
        ]);
    }

    public function getWorkingHours(){
        $month = sanitize("month");
        $year = sanitize("year");
        $workerId = AuthWorker::id();
        $today = date("Y-m-d");
        $before = "";
        $after = "";
        
        $hours_worked_before_today = $this->db->select("user_time", ["YEAR(time_date) as year", "MONTH(time_date) as month",
            "SUM(time_working) as total_hours"
        ])
        ->innerJoin("times", "time_user", "=", "time_id")
        ->where(["user_worker" => $workerId, "YEAR(time_date)" => $year, "MONTH(time_date)" => $month])
        ->whereSmaller("time_date", $today)
        ->groupBy("YEAR(time_date), MONTH(time_date)")
        ->get();
    
        $hours_worked_future = $this->db->select("user_time", ["YEAR(time_date) as year", "MONTH(time_date) as month",
            "SUM(time_working) as total_hours"
        ])
        ->innerJoin("times", "time_user", "=", "time_id")
        ->where(["user_worker" => $workerId, "YEAR(time_date)" => $year, "MONTH(time_date)" => $month])
        ->whereGreaterOrEqual("time_date", $today)
        ->groupBy("YEAR(time_date), MONTH(time_date)")
        ->get();
    
        foreach($hours_worked_before_today as $record){
            $before = $record->total_hours;
        }
    
        foreach ($hours_worked_future as $record){
            $after = $record->total_hours;
        }
        
        echo json_encode(["after" => $after, "before" => $before]);
    }

    public function logout(){
        Authenticator::logout_worker();
        redirect('/');
        die();
    }

    public function forgot_password(){
        $name = 'Benutzer hat Passwort vergessen';
        return view("users/forgot_password/index.view.php", [
            "name" => $name,
        ]);
    }

    public function change_password_page(){
        $email = sanitize('email');
        
        $validation = FormValidation::validation([
            'email' => $_POST['email'],
        ]);

        if(!$validation->failed()){
            $user = $this->db->select("users")->where(['user_email' => $email])->first();

            if($user){
                $sender = "aymane.chnaif@gmail.com";
                $mail = new ForgotPasswordMail();
                $sent = $mail
                ->send_forgot_password_mail(capital($user->user_first_name), capital($user->user_last_name), $sender, $email, $user->user_token);

                if($sent){
                    Session::flash('success', 'Bitte überprüfen Sie Ihre E-Mail, um Ihr Passwort zu ändern.');
                    redirect('/user/login');
                }else{
                    Session::flash('failed', 'Fehler beim Senden der Passwort-Zurücksetzungs-E-Mail.');
                    redirect('/user/forgot-password');
                }
            }
        }
    }

    public function change_password_index(){
        if(isset($_GET['email']) && isset($_GET['token'])){
            $name = 'Benutzer Passwort ändern';
            $email = $_GET['email'];
            return view("users/forgot_password/change.view.php", [
                "name" => $name,
                "email" => $email,
            ]);
        }
    }

    public function change_password(){
        $email = sanitize('email');
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $validation = FormValidation::validation([
            'password' => $_POST['password'],
            'confirm_password' => $_POST['confirm'],
        ]);

        if(!$validation->failed()){
            try{
                $user = $this->db->select("users")->where(['user_email' => $email])->first();

                if($user){
                    $user_updated = $this->db->update("users", [
                        "user_password" => $password,
                    ], ['user_email' => $email]);

                    if($user_updated){
                        Session::flash('success', 'Ihr Passwort wurde erfolgreich geändert.');
                        redirect('/user/login');
                    }else{
                        Session::flash('failed', 'Es ist ein Problem aufgetreten, das Passwort wurde nicht geändert.');
                        redirect('/user/change-password');
                    }
                }
            }catch(\Exception $e){
                Session::flash('database', 'Database problem: ' . $e->getMessage());
                redirect('/user/change-password');
            }
        }
    }

    public function accept(){
        $user_id = sanitize("user_id");

        try{
            $user_code = $this->db->select("users")->where(['user_id' => $user_id])->first();
            $uniqueNumber = generateUniqueNumber($this->db);
            
            $user = $this->db->update("users", [
                "is_accepted" => 1,
            ], ['user_id' => $user_id]);

            if($user_code->is_manager == 1){
                $user_manager = $this->db->update("managers", [
                    "code" => $uniqueNumber,
                ], ['manager_user' => $user_id]);

                if(!$user_manager){
                    echo json_encode(["error" => "Code wurde nicht eingefügt. Bitte versuchen Sie es später erneut"]);
                    exit;
                }
            }
            
            if($user){
                echo json_encode(["success" => "Benutzer wurde akzeptiert"]);
            }
        }catch(\Exception $e){
            echo json_encode(["error" => "Database problem: " . $e->getMessage()]);
        }
    }

    public function unaccept(){
        $user_id = sanitize("user_id");

        try{
            $user_code = $this->db->select("users")->where(['user_id' => $user_id])->first();
            
            $user = $this->db->update("users", [
                "is_accepted" => 0,
            ], ['user_id' => $user_id]);

            if($user_code->is_manager == 1){
                $user_manager = $this->db->update("managers", [
                    "code" => NULL,
                ], ['manager_user' => $user_id]);

                if(!$user_manager){
                    echo json_encode(["error" => "Code wurde nicht eingefügt. Bitte versuchen Sie es später erneut"]);
                    exit;
                }
            }
            
            if($user){
                echo json_encode(["success" => "Benutzer wurde abgelehnt"]);
            }
        }catch(\Exception $e){
            echo json_encode(["error" => "Database problem: " . $e->getMessage()]);
        }
    }
}