<?php

namespace Vendor\PhpMa\Mail;

class ContactMail{

    public function send_message_mail($first_name, $last_name, $email_from, $email_to, $subject, $message){
        $from = $email_from;
        $to = $email_to;
        $htmlContent = "
        <div style='background-color: #F8F9FA; font-family: Trebuchet MS; margin-top: 5px; margin-bottom: 5px; margin-right: 5px; margin-left: 5px; border-radius : 5px;'>
            <div style='margin-left: 10px'>
            </div>
            <h1 style='text-align:center'>Sie haben eine Nachricht von ".capital($first_name)." ".capital($last_name)." !</h1>
            <div style='font-size: 1.2rem; margin-right: 15px; margin-left: 15px; background-color:white; border-radius:5px'>            
                <p style='padding:10px; text-align:center;'>
                    Email: ".$email_from."
                </p>
                <p style='padding:10px; text-align:center;'>
                    Message: ". nl2br($message)."
                </p>
            </div>
            <br/>
            <br/>
        </div>";
        $mail = new SendMail($from, $to, $subject, $htmlContent);
        return $mail->send_mail();
    }
}