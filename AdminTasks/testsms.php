<?php 
   require_once 'textlocal.class.php';
   
// Start : Code to send sms thru TextLocal api       
// Authorisation details.
	    $username = "bspd.hyd@gmail.com";
	    $hash = "72bc4bd55c987064e36c2aa64338f023975a1e5209fa4a6ccb191c55673dcd1b";
	// Config variables. Consult http://api.textlocal.in/docs for more info.
        $name = "Test Dakshinamurthy";
        $numbers = "9849600200";
        $test = "0";
        $sender = "BSPDIN"; // This is who the message appears to be from.

        $message = "Received Rs  500 from Dakshinamurthy TESTING";
    // 612 chars or less
	// A single number or a comma-seperated list of numbers
	    $message = urlencode($message);
    	echo $message;
	    $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers; //."&test=".$test;
	    $ch = curl_init('https://api.textlocal.in/send/');
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $result = curl_exec($ch); // This is the result from the API
     	echo $result;
	    curl_close($ch);
//End code to send sms thru TextLocal api
 

?>
 