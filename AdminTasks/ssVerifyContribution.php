<?php 
   require_once '../ssdbconfig.php';
   require_once 'textlocal.class.php';
   session_start();
    
//Identify the user
  echo ' Welcome ';
  echo  $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"];

if($_SESSION["name"]) {}  else header("Location:../ssLogout.php");

if (isset($_POST['btnBack']))  header("Location:../selfservice.php");

if( ($_SESSION["id"] == 1116) or ($_SESSION["id"] == 1123) or ($_SESSION["id"] == 1636) ){}
        else  header("Location:../selfservice.php"); 

if(isset($_POST["confirm"]))   {
  echo "<br/>"; 
  if(!empty($_POST["contributions"]))
    { foreach($_POST["contributions"] as $products)
//     echo "b-".$products."-b";
       $reqevenid = explode ("-", $products);
//     echo "c" .$reqevenid[0]. "c";
       insEvntReg($link,$reqevenid[0],$reqevenid[1]);
       $sqlqry1 = "SELECT Name, Phone_Num from BSPD_Member where MEMBER_ID = ".$reqevenid[0];
       $result = mysqli_query($link, $sqlqry1) or die(mysqli_error()); 
     
       while ($row = mysqli_fetch_array($result)){
// Start : Code to send sms thru TextLocal api       
// Authorisation details.
	    $username = "bspd.hyd@gmail.com";
	    $hash = "72bc4bd55c987064e36c2aa64338f023975a1e5209fa4a6ccb191c55673dcd1b";
	// Config variables. Consult http://api.textlocal.in/docs for more info.
        $name = $row[Name];
        $numbers = $row[Phone_Num];}
        $test = "0";
        $sender = "BSPDIN"; // This is who the message appears to be from.

    // Data for text message. This is the text message data.
    //Received Rs xxxx from xxxx with receipt num xxxx for xxxx, Rgds-BSPD
        $message = "Received Rs".$reqevenid[2]."from ".$name." with receipt num".$reqevenid[3]."for ".$reqevenid[1].", Rgds-BSPD";
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
     }
}

echo "<form align= 'center' method='post'>";
echo '<p> Unconfirmed Contributios </p>';
echo "<input type='submit' name='confirm'  value='Confirm'/>";
echo "<input type='submit' value='  Back  ' class='backbutton' name='btnBack'/>"."<br/><br/>";

$sqlqry = "SELECT Member_ID, EVENT_ID, Amount, Transaction_code, Contribution_Type, Contribution_Date, Reference_Details from BSPD_Member_Contribution where Approved = 'N'";
$result = mysqli_query($link, $sqlqry) or die(mysqli_error()); 

 echo "<table border='1'  style='border-collapse: collapse'>";  
 echo "<tr><th>Validate</th><th>Member ID</th><th>Event ID</th><th>Amount</th><th>Transaction Code</th><th>Contribution Type</th><th>Contribution Date</th><th>Reference Details</th></tr>"; 

  while ($row = mysqli_fetch_array($result)){
//      echo "<input type='checkbox' name='contributions[]' value='".$row["Member_ID"]. "-".$row["EVENT_ID"]." - ".$row["Amount"]." - ".$row["Transaction_code"]." - ".$row["Contribution_Type"]." - ".$row["Contribution_Date"]." - ".$row["Reference_Details"]." '>  - ".$row["Member_ID"]. " - ".$row["EVENT_ID"]." - ".$row["Amount"]." - ".$row["Transaction_code"]." - ".$row["Contribution_Type"]." - ".$row["Contribution_Date"]." - ".$row["Reference_Details"]." <br/>";
//      echo "<input type='checkbox' name='contributions[]' value='".$row["Member_ID"]. "-".$row["EVENT_ID"]." - ".$row["Amount"]." - ".$row["Transaction_code"]." - ".$row["Contribution_Type"]." - ".$row["Contribution_Date"]." - ".$row["Reference_Details"]." '>  - ".$row["Reference_Details"]." <br/>";
//        echo "<input type='checkbox' name='contributions[]' value='Y'>";
        echo "<tr><td><input type='checkbox' name='contributions[]' value='".$row["Member_ID"]. "-".$row["EVENT_ID"]." - ".$row["Amount"]." - ".$row["Transaction_code"]." ' /></td><td>".$row["Member_ID"]."</td><td>".$row['EVENT_ID']."</td><td>".$row["Amount"]."</td><td>".$row["Transaction_code"]."</td><td>".$row["Contribution_Type"]."</td><td>".$row["Contribution_Date"]."</td><td>".$row["Reference_Details"]."</td></tr>";

  }
echo "</table>";
echo "</form>";

function insEvntReg($link,$MEMBER_ID, $EVENT_ID) {
  if ($link->connect_error) { die("Connection failed: " .$link->connect_error);  } 
//Update the record
  $sqlupd = " UPDATE BSPD_Member_Contribution SET Approved ='Y' where Member_ID =" .$MEMBER_ID. " and EVENT_ID = '".$EVENT_ID. "'";
  mysqli_query($link, $sqlupd) or exit(mysqli_error()); 
}

?>
 