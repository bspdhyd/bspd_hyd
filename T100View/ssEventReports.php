 
<?php 
 require_once '../ssdbconfig.php';
 session_start();

function EventDetails($link){
    
 $radiovalue = $_POST["Search"];
 $row = GetEventDetail($link, $_POST["Event_ID"]);
 $name = $row['Event_Description']; 
 if (!empty($name)) {
   if ($radiovalue == "Recognition") { EventRecognitionDetails($link, $name); }
   if ($radiovalue == "Contribution") { EventContributionDetails($link, $name); }
   if ($radiovalue == "Expenses") { EventExpenseDetails($link, $name); }
   if ($radiovalue == "Registration") { EventRegistrationDetails($link, $name); }
   if ($radiovalue == "Attendance") { EventAttendanceDetails($link, $name); }
   if ($radiovalue == "ExpSum") { EventExpenseSummary($link, $name); }
   if ($radiovalue == "PayConfirm") { EventPaymentConfirmationMessages($link, $name); }
 } else {echo "Invalid Event";}
// Close connection
mysqli_close($link);
}

function EventRecognitionDetails($link, $name){ 

//Get the name of individual
 $sqlqry1 = "Select * from BSPD_View_Recognition where Event_ID = '".$_POST["Event_ID"]."'";
 $result = mysqli_query($link, $sqlqry1) or die(mysqli_error()); 
 echo ' Recognition details for the Event : ' .$name ;
//echo  '<p>' .$row1['Event_Description']. '</p>';
 echo "<table border='1' style='border-collapse: collapse'>";  
 echo "<th>MEMBER Name</th><th>Recognition</th><th>Recognition Desc</th>";
 while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['Alias']."</td><td>".$row['Recognition']."</td><td>".$row['Notes']."</td></tr>";
 }
}

function EventContributionDetails($link, $name){ 

$sql1 = "Select sum(Amount) as Amount, count(Transaction_Code) as count from BSPD_View_Contribution_Report where EVENT_ID = '".$_POST["Event_ID"]."';";
$result1 = mysqli_query($link, $sql1);
$row1 = mysqli_fetch_array($result1);


$sqlqry1 = "Select * from BSPD_View_Contribution_Report where EVENT_ID = '".$_POST["Event_ID"]."' order by Full_Name";
 $result = mysqli_query($link, $sqlqry1);
 echo ' Contribution details for the Event :' .$name ;

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<tr><td style='background-color:aqua;' colspan='6' align='center'><b>Total Amount: Rs.".$row1['Amount']."         Number of contributors: ". $row1['count']."</b></td></tr>";
    echo "<th>Member ID</th><th>Member Name</th><th>Contribution Date</th><th>Contribution_Type</th><th>Amount</th><th>Receipt URL</th>";  
    

   while ($row = mysqli_fetch_array($result)){
         $receiptURL = $row['Receipt_PDF_URL'];
         echo "<tr><td>".$row['Member_ID']."</td><td>".$row['Full_Name']."</td><td>".$row['Contribution_Date']."</td><td>".$row['Contribution_Type']."</td><td>"
               .$row['Amount']."</td><td>"." <a href='$receiptURL' target='_blank'>$receiptURL</a>"."</td></tr>";
         }
}

function EventExpenseSummary($link, $name){ 

   echo " Expense Summary for : " .$name ;
   echo "<table border='1' style='border-collapse: collapse'>";
//   echo "<table display:flex >";
   echo "<th>Category</th><th>Amount</th>";  
   $sql = "SELECT Category, sum(Amount) as Amount FROM BSPD_View_Expense_Report where EVENT_ID = '".$_POST['Event_ID']."' group by Category";
   $result = mysqli_query($link, $sql);
   $sum = 0;    
      while ($row = mysqli_fetch_array($result)){
            echo "<tr><td>".$row['Category']."</td><td align='right'>".number_format($row['Amount'])."</td></tr>";
            $sum+=$row['Amount'];
      }
   echo "<tr><td>Total</td><td align='right'>".number_format($sum)."</td></tr>";

}  

function EventExpenseDetails($link, $name){ 
  
    $sqlqry= "SELECT * FROM BSPD_View_Expense_Report where EVENT_ID = '".$_POST["Event_ID"]."'";
    $result = mysqli_query($link, $sqlqry);
    echo " voucher list for :" .$name ;

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Voucher</th><th>SCat Details</th><th>Amt</th><th>SCBill</th><th>Bill#</th><th>BillSt</th><th>VSig</th><th>PSts</th><th>Payee</th><th>UTR</th><th>Payment Conf</th><th>Amt Details</th>";  

   while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['Voucher_Num']."</td><td>".$row['Sub_Category']."</td><td>".$row['Amount']."</td><td>".$row['SoftCopyBill']."</td><td>".$row['Expense_Bill_Num'].
              "</td><td>".$row['Bill_Status']."</td><td>".$row['Voucher_Signed']."</td><td>".$row['Payment_Status']."</td><td>".$row['Payee_ID']." ".substr($row['Name'],0,20). "</td>
              <td>".$row['UTR_Number']."</td><td>".$row['Payment_Confirmation_ID']."</td><td>".substr($row['Amount_Details'],0,50)."</td></tr>";
         }
}

function DecryptDetails($link, $value) {
	//******************Code for Decryption********
	$decryption_iv = '1234567891011121';        // Non-NULL Initialization Vector for decryption 
	//$decryption_Key = $type;
	$decryption_key = "PayeeBankAccountNumber";   // Store the decryption key 
	$ciphering = "AES-128-CTR";
	$options = 0;
	$decryptvalue = openssl_decrypt($value, $ciphering, $decryption_key, $options, $decryption_iv);  // Use openssl_decrypt() function to decrypt the data 
	return $decryptvalue;
	//     echo "Decrypted String: " . $decryptAcct;   // Display the decrypted string 
}

function EventPaymentConfirmationMessages($link, $name){ 
  
    $sqlqry= "SELECT * FROM BSPD_View_Expense_Report where EVENT_ID = '".$_POST["Event_ID"]."' and Payment_Status = 'Paid' and Expense_Type = 'NEFT' order by Name";
    $event_id = $_POST['Event_ID'];
    $result = mysqli_query($link, $sqlqry);
    echo " voucher list for :" .$name ;
    
    /*
    
    payee name, phno, email,message : ""
    */
    $payee_name = "";
    $sum = 0;
    $count = 1;
    echo "<table border='1' style='border-collapse: collapse'><tr>"; 
    while($row = mysqli_fetch_array($result))
    {
        $name = $row['Name'];
        $payee_id = $row['Payee_ID'];
        if($payee_name != $name)
        {
            if($payee_name != "")
            {
               echo "Total: ₹$sum <br>";
               $sum = 0;
               $count = 1;
               echo "</td></tr>";
            }
            $payee_name = $name;
            echo "<tr><td>$payee_name<br>Ph:".$row['Phone_Num']."<br>Email:".$row['Email_ID']."</td><td>";
            echo "*BSPD ".$_POST['Event_ID']." PAYMENTS.*<br>"."Namaste $payee_name garu (Payee ID : $payee_id),<br>";
        }
        $Payee_Acnt_Num = DecryptDetails($link, $row["Payee_Acnt_Num"]);
        echo "[$count] Paid ₹".$row['Amount']." to your IFSC ".$row['IFSC_CODE']." Acc ".$Payee_Acnt_Num." on ".$row['Payment_Date']." for bill no. [".$row['Expense_Bill_Num']."]; AmountDetails : ".$row['Amount_Details'].".
        <br> UTR ".$row['UTR_Number']." Payment Confirmation  ".$row['Payment_Confirmation_ID'].".<br><br>";
        $count++;
        $sum += $row['Amount'];
        
    }
    echo "Total: ₹$sum <br>";
    echo "</td></tr></table><br><br>";
    
    $payee_name = "";
    $sum = 0;
    $count = 1;
    
    $sqlqry= "SELECT * FROM BSPD_View_Expense_Report where EVENT_ID = '".$_POST["Event_ID"]."' and Payment_Status = 'Paid' and Expense_Type = 'KIND' and Payee_ID != 106 order by Name";
    $result = mysqli_query($link, $sqlqry);
    
    echo "<table border='1' style='border-collapse: collapse'><tr>"; 
    while($row = mysqli_fetch_array($result))
    {
        $name = $row['Name'];
        if($payee_name != $name)
        {
            if($payee_name != "")
            {
               echo "<br><br>Total: ₹$sum <br>";
               $sum = 0;
               $count = 1;
               echo "</td></tr>";
            }
            $payee_name = $name;
            echo "<tr><td>$payee_name<br>Ph:".$row['Phone_Num']."<br>Email:".$row['Email_ID']."</td><td>";
            echo "*BSPD ".$_POST['Event_ID']." PAYMENTS*<br>"."Namaste $payee_name garu,<br>";
        }
        echo "[$count] Received ".$row['Amount_Details']." bill no. [".$row['Expense_Bill_Num']."] amount : ₹".$row['Amount']." your kind contribution. <br>";
        $sum += $row['Amount'];
        $count++;
    }
    echo "<br><br>Total: ₹$sum <br>";
    echo "</td></tr></table>";
    
}

function EventRegistrationDetails($link, $name){ 

 $sqlqry= "SELECT * FROM BSPD_View_Event_Registration where EVENT_ID = '".$_POST['Event_ID']."' and Registered = 'Y' ";

 $result = mysqli_query($link, $sqlqry);
 echo '  Registration details for the Event : ' .$name;

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Id</th><th>Member Name</th><th>Email ID</th>";  

   while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['MEMBER_ID']."</td><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td></tr>";
   }
}

function EventAttendanceDetails($link, $name){ 

$sqlqry= "SELECT * FROM BSPD_View_Event_Registration where EVENT_ID = '".$_POST['Event_ID']."' and Attended = 'Y' ";
 $result = mysqli_query($link, $sqlqry);
 echo '  Attendance details for the Event : ' .$name;

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Id</th><th>Member Name</th><th>Email ID</th>";  

   while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['MEMBER_ID']."</td><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td></tr>";
   }
}

?> 


<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>


<form name="frmUser" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>  </div>
<br>
<b>Report Based on Event:</b>
<fieldset>
Search by Event:
<input type="radio" style="margin-left:10px" name="Search" value="Expenses" checked> Expenses
<input type="radio" style="margin-left:10px" name="Search" value="Recognition"> Recognition
<input type="radio" style="margin-left:10px" name="Search" value="Contribution"> Contribution
<input type="radio" style="margin-left:10px" name="Search" value="Registration"> Registration
<input type="radio" style="margin-left:10px" name="Search" value="Attendance"> Attendance
<input type="radio" style="margin-left:10px" name="Search" value="ExpSum"> Expense-Summary
<input type="radio" style="margin-left:10px" name="Search" value="PayConfirm"> Payment Confirmation
<br><br>
Event :
 <input type="text" name="Event_ID" class="txtField" Value="">
 <input type="submit" name="check" value="Check" />    
</fieldset>
</form>
<br>
<?php
if($_SESSION["name"]) { if(count($_POST)>0) { EventDetails($link); } } 
else { header("Location:../ssLogout.php");}
?>

</body>
</html>