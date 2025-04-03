 
<?php 
 require_once 'ssdbconfig.php';
 require_once 'ssDatabaseConfig.php';
 session_start();

function MemberContributionDetails(){ 

if (isset($_POST['btnBack'])){header("Location:selfservice.php"); }

if(isset($_POST["check"])) { 
  $radiovalue = $_POST["Search"];
  //echo "radio value" .$radiovalue;

  if ($radiovalue == "Payee_ID") {
    $sqlqry1 = "Select * from BSPD_Member_Contribution where Member_id = '".$_POST["ID"]."'"; 
  }
  if ($radiovalue == "EVENT_ID") {
    $sqlqry1 = "Select * from BSPD_Member_Contribution where EVENT_ID = '".$_POST["ID"]."'";
  }
  if ($radiovalue == "Receipt_ID") {
     $sqlqry1 = "Select * from BSPD_Member_Contribution where Transaction_Code >= '".$_POST["StartID"]."' and Transaction_Code <= '".$_POST[EndID]."'";  
  }
//Get the name of individual

  $result = mysql_query($sqlqry1) or die(mysql_error()); 
  echo '<h5> Contributios receipt details </h5>';
//   echo  '<p>' .$row1['Payee_ID']. '</p>';
//     echo "<input type='checkbox' name='Allcontributions[]' value='All'> Select all <br/>";
echo "<form method='post'>";
echo "<input type='submit' value='SndEmail' name='SndEmail' />";
echo " -    Select All";
echo "<input type='radio' name='Sendall' value='Sendall' />";
echo " -    Select few";
echo "<input type='radio' name='Sendall' value='Sendfew' Checked />"."<br/><br/>";

  while ($row = mysql_fetch_array($result)){
     
    echo "<input type='hidden' name='Hcontributions[]' value='".$row["Member_id"]. " - - ".$row["EVENT_ID"]." - - ".$row["Amount"]." - - ".$row["Contribution_Type"]." - - ".$row["Transaction_Code"]." - - ".$row["Receipt_PDF_URL"]." '></>"; 
      
    echo "<input type='checkbox' name='contributions[]' value='".$row["Member_id"]. " - - ".$row["EVENT_ID"]." - - ".$row["Amount"]." - - ".$row["Contribution_Type"]." - - ".$row["Transaction_Code"]." - - ".$row["Receipt_PDF_URL"]." '>  - - ".$row["Member_id"]. " - - ".$row["EVENT_ID"]." - - ".$row["Amount"]." - - ".$row["Contribution_Type"]." - - ".$row["Transaction_Code"]." - - ".$row["Receipt_PDF_URL"]." <br/>";
  }
 echo "</form>";
 
}

if(isset($_POST["SndEmail"])){
//  echo "sending mail";
  echo "<br/>"; 
 // echo "Confirmed records";
// echo '<p>' .$_POST["contributions"]. '</p>';

  if ($_POST["Sendall"] == 'Sendall'){
//      echo "sending all";
//      echo "<p> ".$_POST["Hcontributions"]. "</p>";
    foreach($_POST["Hcontributions"] as $products) {
    $reqevenid = split ("\- -", $products); 
//    echo "testing1";
//call the function here
         MailContributionDetails($reqevenid[0], $reqevenid[4], $reqevenid[5]);
//        MailContributionDetails($reqevenid[0], $reqevenid[4]);

    }  
  }else {

    foreach($_POST["contributions"] as $products) {
       $reqevenid = split ("\- -", $products); 
//       echo "testing";
//call the function here
       MailContributionDetails($reqevenid[0], $reqevenid[4], $reqevenid[5]);
//       MailContributionDetails($reqevenid[0], $reqevenid[4]);
    }
 }
// Close connection
mysqli_close($link);
mysql_close($con);
} 
}

function MailContributionDetails($MEMBERID, $TRNCODE, $RCPTLINK){ 

//$sqlqry1 = "SELECT MEMBER_ID, Name, Email_ID from BSPD_Member where MEMBER_ID = " .$MEMBERID;
$sqlqry1 = "SELECT * from BSPD_View_Contribution_Report where Transaction_Code = " .$TRNCODE;
$result = mysql_query($sqlqry1) or die(mysql_error()); 

  while ($row = mysql_fetch_array($result)){

// start code for sending mail
        if (isset($_POST['SndEmail'])){
          $to = $row["Email_Address"];
          $header = 'BSPD Contribution receipt link';
          $From = 'From: bspd.hyd@gmail.com';
    //Start Message Body    
          $Message = "Dear ".$row['Full_Name']. " Garu,\r\n" ;
          $Message .= "Your Member id as per our records" .$MEMBERID. "  \r\n";
          $Message .= "Your receipt for your contribution towards" .$row['EVENTID']. " is attached  \r\n";
          
          $Message .= "Please download the receipt with the link below and let us know if you see any errors. \r\n";
          $Message .= "Please go to ".$row['Receipt_PDF_URL']. "\r\n"; 
          $Message .="------------------------------------------------\r\n";
          $Message .=  "Thank you,  BSPD Hyderabad";
          $word = "https";
//          echo "url" .$row['Receipt_PDF_URL'];
    //End Message Body
          if ((strpos($row['Receipt_PDF_URL'], $word) !== false)){
             $this_mail = mail($to,$header,$Message,$From);
             if($this_mail) {
                 echo '<br> Mail sent!';
                 echo " to: " .$MEMBERID. "mailid: " .$to;
             }
             else {echo error_message;}
          }
        }
//end code for sending mail    
    }
}

?> 


<html>
<head>
<title>Contribution Receipts</title>
</head>
<body>

<?php 
//getting login credentials;
  echo ' Welcome to ';
  echo  $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"];

if (isset($_POST['btnBack'])){header("Location:selfservice.php"); }

?>

<form name="frmUser" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>
</div>

<br> 
Search by:
<input type="radio" name="Search" value="Payee_ID"> Member ID
<input type="radio" name="Search" value="EVENT_ID"> Event ID
<input type="radio" name="Search" value="Receipt_ID"> Receipt range
<br>
<br>
ID :
<input type="text" name="ID" class="txtField" Value="b ">
<input type="submit" name="check" value="Check" onclick="MemberContributionDetails()" />    
<br>
<br>
Start Receipt:
<input type="text" name="StartID" class="txtField" Value="">
End receipt
<input type="text" name="EndID" class="txtField" Value="">
<br>
<br>

<input type='submit' value='  Back  ' name='btnBack' />

</form>

<?php
if($_SESSION["name"]) {
   if(count($_POST)>0) {
//     echo "testing";
      MemberContributionDetails();
   }
} 
else echo "<h1>Please login first .</h1>";
?>

</body>
</html>