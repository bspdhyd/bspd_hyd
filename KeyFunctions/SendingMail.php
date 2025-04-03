<?php 

   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\SMTP;
   use PHPMailer\PHPMailer\Exception;
  
   include_once 'daily_email/src/PHPMailer.php';   
   include_once 'daily_email/src/SMTP.php';   
   include_once 'daily_email/src/Exception.php';   
     
function SendTheMail ($to, $header, $Message) {
           $mail = new PHPMailer(true);
//           echo 'testing';
           try {
               $mail->SMTPDebug = SMTP::DEBUG_OFF;
               $mail->isSMTP();                                            //Send using SMTP
               $mail->Host       = 'bspd.in';                     //Set the SMTP server to send through
               $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
               $mail->Username   = 'service@bspd.in';                     //SMTP username
               $mail->Password   = 'bspd2012';                               //SMTP password
               $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
               $mail->Port       = 465;   
               $mail->SMTPKeepAlive = true; //SMTP connection will not close after each email sent, reduces SMTP overhead

               $mail->setFrom('service@bspd.in', 'BSPD');
               $mail->addReplyTo('bspd.hyd@gmail.com', 'To BSPD');
               $mail->addAddress($to);
//               echo $to;
               $mail->Subject = $header;
               $mail->Body    = $Message;
               $mail->send();
               }
               catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    $mail->getSMTPInstance()->reset();
                    }
    
}
?>