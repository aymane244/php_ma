<?php

namespace Vendor\PhpMa\Mail;

class VerificationMail{

    public function send_verification_mail($first_name, $last_name, $email_from, $email_to, $token, $user_type){
        $from = $email_from;
        $to = $email_to;
        $subject = translate("subject_verification");
        $text = translate("email_text_subject_verification_password");
        $htmlContent = "
        <div style='background-color: #F8F9FA; font-family: Trebuchet MS; margin-top: 5px; margin-bottom: 5px; margin-right: 5px; margin-left: 5px; border-radius : 5px;'>
            <div style='margin-left: 10px'>
                <br/><img src='' alt='logo' style='width:150px;'/> <br/> <br/>
            </div>
            <h1 style='text-align:center'>Liebe/r " . $first_name . " " . $last_name . "!</h1>
            <div style='font-size: 1.2rem; margin-right: 15px; margin-left: 15px; background-color:white; border-radius:5px'>            
                <p style='padding:10px; text-align:center;'>
                    ".$text."<br>
                    <a href='http://localhost:8000/user/verify?token=$token&email=$to'>".translate("verify")."</a><br>
                </p>
            </div>
            <br/>
            <br/>
        </div>";
        $mail = new SendMail($from, $to, $subject, $htmlContent);
        return $mail->send_mail();
    }
}