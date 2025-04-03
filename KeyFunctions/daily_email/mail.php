<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'madhup');
define('DB_PASSWORD', 'madhup');
define('DB_NAME', 'bspdhyd_wp1');

$lockfile = sys_get_temp_dir() . '/urfwabs93fwz_mail.lock';
if(file_exists($lockfile)) { 
    $pid = file_get_contents($lockfile);
    if (posix_getsid($pid) === false) {
        print "process has died! restarting...\n";
        file_put_contents($lockfile, getmypid()); // create lockfile
     }
     else {
        print "PID is still alive! can not run twice!\n";
        exit;
     }
}
else {
    file_put_contents($lockfile, getmypid()); // create lockfile
}

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'bspd.in';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'service@bspd.in';                     //SMTP username
    $mail->Password   = 'bspd2012';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;   
    $mail->SMTPKeepAlive = true; //SMTP connection will not close after each email sent, reduces SMTP overhead

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// $date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$date = date('Y-m-d');

$prev_date = date('Y-m-d', strtotime($date .' -1 day'));
$next_date = date('Y-m-d', strtotime($date .' +1 day'));
echo $prev_date." ".$next_date;
// die();

sendreceipt($link, $prev_date, $prev_date, $mail);
unlink($lockfile);

function sendreceipt($link, $from, $to, $mail)
{
    $result5=mysqli_query($link, "    
       SELECT 
        `a`.`Contribution_Date`,
        `a`.`Transaction_Code`,
        `a`.`Member_id`,
        `b`.`Surname` ,
        `b`.`Name` ,
        `b`.`Email_ID`,
        `a`.`Receipt_PDF_URL`,
        `a`.`EVENT_ID`
        FROM
            (`BSPD_Member_Contribution` `a`
            JOIN `BSPD_Member` `b`)
        WHERE
            (`a`.`Member_id` = `b`.`MEMBER_ID`) AND
            (a.Transaction_Code > (SELECT MAX(TRansaction_Code) from bspdhyd_wp1.BSPD_Sent_Mail))
        ORDER BY 
            `a`.Transaction_Code;");

    print_r($result5);
    
    if(mysqli_num_rows($result5) == 0) {
        $result5 = mysqli_query($link, "
        SELECT 
        a.Contribution_Date,         
        a.Transaction_Code, 
        a.Member_id,         
        b.Surname ,         
        b.Name ,
        b.Email_ID,         
        a.Receipt_PDF_URL,         
        a.EVENT_ID
        FROM
            (BSPD_Member_Contribution a
            JOIN BSPD_Member b)
        WHERE
            (a.Member_id = b.MEMBER_ID) AND
             a.Transaction_Code >  (SELECT MIN(Transaction_Code) FROM BSPD_Sent_Mail) AND
            (a.Transaction_Code  IN (SELECT Transaction_Code from bspdhyd_wp1.BSPD_Sent_Mail WHERE Status = 'Fail'))
        ORDER BY a.Transaction_Code;");
        
    }

    $old_event_id="NO0011";  // Changed EVENT Code string to similar format.
    //Test Email sending and limiting number of email messages
    $messagecount=0;
    // echo "Messages sent successfully ";
    while($row=mysqli_fetch_array($result5)) 
    {
        $event_id=$row["EVENT_ID"];
        $messagecount++;// Test Email sending and limiting number of email messages
                if($old_event_id!=$event_id)
        {
            $sqlevent = "select Event_Description,Event_Notes from BSPD_Event where EVENT_ID= ".$event_id.";";
            $result6=mysqli_query($link, "select Event_Description,Event_Notes from BSPD_Event where EVENT_ID='".$event_id."';");
            $row1=mysqli_fetch_array($result6);
            $event_description=$row1["Event_Description"];
            $event_notes=$row1["Event_Notes"];
            $old_event_id=$event_id;
        }
            // echo $messagecount;
            // echo $row1["Event_Description"];
            // echo $event_description;
            sendmail($row, $row1, $mail, $link); 
            
    }
}

function sendmail($row, $row1, $mail, $link)
{
    $Contribution_Date=$row["Contribution_Date"];
    $Receipt_No=$row["Transaction_Code"];
    $MEMBER_ID=$row["Member_id"];
    $Surname=$row["Surname"];
    $Name=$row["Name"];
    $Email_ID=$row["Email_ID"]; 
    
    // The following line helps in testing functionality without any disturbance to Real members.
    // $Email_ID="kommu.srividya@gmail.com";// sending all email to dkommu@gmail.com to test
    $Receipt_PDF_URL=$row["Receipt_PDF_URL"];
                
    $Event_Notes=$row1["Event_Notes"];
    $Event_Description=$row1["Event_Description"];
            
    if($Email_ID!="nobody@bspd.in" && $Receipt_PDF_URL != NULL)
    {
    $to = $Email_ID;
    $header = 'Receipt No '.$Receipt_No.' for '.$Event_Description;
    $From = 'From: bspd.hyd@gmail.com';
    $Message = "Namaste ".$Surname." ".$Name. " Garu,<br/>" ;
    $Message .= "BSPD Member Id : ".$MEMBER_ID. "<br/>";
    $Message .= "<br/>";
    $Message .= $Event_Notes."<br/>";
    $Message .= "<br/>";
    $Message .= $Event_Description."<br/>";
    $Message .= "Receipt link : ".$Receipt_PDF_URL. "<br/><br/>";
    $Message .= "Jaya Jaya Sankara Hara Hara Sankara<br/>";
    $Message .= "Brahmana Sabha(Pancha Dravida), Hyderabad<br/><br/><br/>";
    $Message .= "Dharmo Rakshati Rakshitah<br/>";
    echo "\r\nEmail ".$Email_ID."---";
    bspd_email($Email_ID, $header, $Message, $mail, $link, $Receipt_No);
    // sleep(1);
    //$this_mail = mail($to,$header,$Message,$From);
    //$this_mail = phpmailer($to,$header,$Message,$From);
    $Message="";
    $header="";
    }
    // if($this_mail) {
    //     echo "..\n";
    // }
    // else echo "error";
}

function bspd_email($receiver, $subject, $content, $mail, $link, $Receipt_No) {
    try {
        //Server settings
                                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('service@bspd.in', 'BSPD');
        $mail->addAddress($receiver);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo('bspd.hyd@gmail.com', 'To BSPD');
        $mail->addCC('service@bspd.in');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->Body    = $content;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        

        $mail->send();
        echo 'Message has been sent';
        // INSERT INTO bspdhyd_wp1.BSPD_Sent_Mail(Transaction_Code) VALUES(50645);
        $resultID = mysqli_query($link, "SELECT * FROM bspdhyd_wp1.BSPD_Sent_Mail WHERE Transaction_Code = $Receipt_No;");
        
        // print_r(mysqli_num_rows($resultID));
        
        if(mysqli_num_rows($resultID) > 0) {
            $query_str = "UPDATE BSPD_Sent_Mail SET Status = 'Sent' WHERE Transaction_Code = $Receipt_No;";
            $resultSM = mysqli_query($link, $query_str);
        }
        else {
            $query_str = "INSERT INTO bspdhyd_wp1.BSPD_Sent_Mail(Transaction_Code, Status, Retries) VALUES($Receipt_No, 'Sent', 0);";
            $resultSM = mysqli_query($link, $query_str);
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        
        $resultID = mysqli_query($link, "SELECT * FROM bspdhyd_wp1.BSPD_Sent_Mail WHERE Transaction_Code = $Receipt_No;");
        if(mysqli_num_rows($resultID) > 0) {
            $query_str = "UPDATE BSPD_Sent_Mail SET Status = 'Fail' WHERE Transaction_Code = $Receipt_No;";
            $resultSM = mysqli_query($link, $query_str);
        }
        else {
            $query_str = "INSERT INTO bspdhyd_wp1.BSPD_Sent_Mail(Transaction_Code, Status, Message, Retries) VALUES($Receipt_No, 'Fail', '$mail->ErrorInfo', 1);";
            $resultSM=mysqli_query($link, $query_str);
        }

        $mail->getSMTPInstance()->reset();
    }
    $mail->clearAddresses();
    $mail->clearAttachments();
}

