<?php

namespace Mail;

class SendMail{
    protected $sender_email;
    protected $recipient_email;
    protected $subject;
    protected $htmlContent;
    protected $file_path;

    function __construct($sender_email, $recipient_email, $subject, $htmlContent, $file_path = null){
        $this->sender_email = $sender_email;
        $this->recipient_email = $recipient_email;
        $this->subject = $subject;
        $this->htmlContent = $htmlContent;
        $this->file_path = $file_path;
    }

    public function send_mail(){
        $to = $this->recipient_email; 
        $from = $this->sender_email; 
        $fromName = 'Test'; 
        $subject = $this->subject;  
        $htmlContent = $this->htmlContent; 
        $file_name = $this->file_path;
        $headers = "From: $fromName"." <".$from.">"; 
        $boundary = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$boundary}x"; 
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
        "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 

        if(!empty($file_name) > 0){ 
            if(is_file($file_name)){ 
                $message .= "--{$mime_boundary}\n"; 
                $fp = @fopen($file_name,"rb"); 
                $data = @fread($fp,filesize($file_name)); 
                @fclose($fp); 
                $data = chunk_split(base64_encode($data)); 
                $message .= "Content-Type: application/octet-stream; name=\"".basename($file_name)."\"\n" .  
                "Content-Description: ".basename($file_name)."\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"".basename($file_name)."\"; size=".filesize($file_name).";\n" .  
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
            }        
        } 

        $message .= "--{$mime_boundary}--"; 
        $returnpath = "-f" . $from; 
        $mail = mail($to, $subject, $message, $headers, $returnpath);
        return $mail;
    }
}