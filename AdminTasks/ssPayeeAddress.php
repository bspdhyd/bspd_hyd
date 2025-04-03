 
<?php 
 require_once '../ssdbconfig.php';
 require_once 'encryptdecrypt.php';
 session_start();
 
// $link = $link;


function delMemberDetails($link) {
  if(isset($_POST["Delete"]))   {
//    echo "testing"; 
    if(!empty($_POST["payeeacct"]))
      { foreach($_POST["payeeacct"] as $payeeacct1)
        {
//        echo "b-".$payeeacct1."-b";    
        $sqlqry1 = "Delete from BSPD_Payee_Account where Bank_Registration_Code = '$payeeacct1'";
//        echo "c" .$sqlqry1. "-c";
        $result = mysqli_query($link, $sqlqry1) or die(mysqli_error()); 
        }
    }}}

function updMemberDetails($link){ 
 if(isset($_POST["select"]))   {   
   $message = "Updated details of " .$_POST["Name"];
   echo "<script type='text/javascript'>alert('$message');</script>";
   $PayeeID = $_POST["PayeeID"];
   $PayeeAcct = $_POST["AcctNum"];
//   $Accttype = "PayeeBankAccountNumber";
//   $encryptacct = EncryptDetails($link,$PayeeAcct,$Accttype);
   
//*****Code for encryption*****************
// Store a string into the variable which need to be Encrypted 
   $simple_string = $_POST["AcctNum"]; 
//echo "Original String: " . $simple_string;  // Display the original string 
   $ciphering = "AES-128-CTR";   // Store the cipher method 
   $iv_length = openssl_cipher_iv_length($ciphering);  // Use OpenSSl Encryption method 
   $options = 0; 
   $encryption_iv = '1234567891011121';   // Non-NULL Initialization Vector for encryption 
   $encryption_key = "PayeeBankAccountNumber";  // Store the encryption key 
   $encryptAcct = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv); // Use openssl_encrypt() function to encrypt the data 
// echo "Encrypted String: " . $encryptAcct . "\n";  // Display the encrypted string 
//****end of encryption************
if (!empty($_POST["AcctNum"])){
   $sql1 = "INSERT INTO BSPD_Payee_Account (Payee_ID, IFSC_CODE, Payee_Acnt_Num, Name_In_Account, Bank_Name, Branch, Nick_Name) " ."SELECT " 
  . $_POST["PayeeID"] . ",'" . $_POST["BankIFSC"] . "', '" . $encryptAcct. "', '" . $_POST["AcctName"] . "', '" . $_POST["BankName"] . "',  '" . $_POST["BankBrnch"] . "',  '" . $_POST["NickName"] . "'";

    if(mysqli_query($link, $sql1)){ echo "Member record was updated successfully.";     } 
    else { echo "ERROR: Could not able to execute $sql1. " . mysqli_error($link);   }
}
}
} 
?> 

<html>
<head>
<title>Update Payee Account Details</title>
</head>
<body>
<form name="frmUser" method="post" action="">

Welcome <b> <?php echo $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"]; ?>  </b>
<br><br> 

<?php
if($_SESSION["name"]) { 
    $PayeeID = "";
    $Name = ' ';
    $NickName = "";
  if(count($_POST)>0) { 
      if (isset($_POST['select'])){   updMemberDetails($link);   }
      if (isset($_POST['Delete'])){   delMemberDetails($link);   }
      
 $PayeeID = $_POST["PayeeID"];
// echo $PayeeID;
 $result1 = mysqli_query($link, "Select * from BSPD_Payee WHERE Payee_ID= ".$PayeeID);  
 $row1 = mysqli_fetch_array($result1);
 if(is_array($row1)) {
   $Payee = $row1['Payee_ID']; 
   $Name = $row1['Name'];
   $EmailID = $row1['Email_ID'];
   $PhoneNum = $row1['Phone_Num'];
   $str = str_pad($Payee,5,"0",STR_PAD_LEFT);
   $NickName =substr("P".$str." ".$Name,0,44);
   
 
   echo "<table border='1' style='border-collapse: collapse'>";  
   echo "<th>Payee ID</th><th>Name</th><th>EMail</th><th>Phone Num</th>";  
   echo "<tr><td>".$PayeeID."</td><td>".$Name."</td><td>".$EmailID."</td><td>".$PhoneNum."</td></tr>"; 
   echo "</table>" ;
 
// details of Account
   echo ".";
   echo "<table border='1' style='border-collapse: collapse'>";  
   echo "<tr><th>Delete</th><th>Name in Acct</th><th>IFSC</th><th>Acct Num</th><th>Bank</th><th>Branch</th><th>BankRegCode</th></tr>";  
   $result = mysqli_query($link, "select * from BSPD_Payee_Account WHERE Payee_ID= ".$PayeeID);  
// $row = mysqli_fetch_array($result);
   while ($row = mysqli_fetch_array($result))   
   {  
      $ifsc = $row['IFSC_CODE'];
      $AcctName =$row['Name_In_Account'];
      $AccountNum =$row['Payee_Acnt_Num']; 
      $BankName =$row['Bank_Name'];   
      $Branch =$row['Branch']; 
      $BankRegCode =$row['Bank_Registration_Code'];
//      $Accttype = "PayeeBankAccountNumber";
//      $decryptAcct = DecryptDetails($link, $AccountNum, $Accttype);

//******************Decryption method for Account number********
       $decryption_iv = '1234567891011121';        // Non-NULL Initialization Vector for decryption 
       $decryption_key = "PayeeBankAccountNumber";   // Store the decryption key 
       $ciphering = "AES-128-CTR"; 
       $options = 0; 
       $decryptAcct=openssl_decrypt ($AccountNum, $ciphering, $decryption_key, $options, $decryption_iv);  // Use openssl_decrypt() function to decrypt the data 
//echo "Decrypted String: " . $decryptAcct;   // Display the decrypted string 
//******************Decryption method for Account number********

      echo "<tr><td><input type='checkbox' name='payeeacct[]' value='".$row['Bank_Registration_Code']. "'/></td><td>".$AcctName."</td><td>$ifsc</td><td>".$decryptAcct."</td><td>".$BankName."</td><td>".$Branch."</td><td>".$BankRegCode."</td></tr>"; 
   }
  echo "</table>";
  } else { echo "Payee does not exist" .$PayeeID; }
}
    
} else { header("Location:../ssLogout.php");}

?>

<div><?php if(isset($message)) { echo $message; } ?>  </div>
<br><br> 

Payee ID :
  <input type="text" name="PayeeID" class="txtField" value="<?php echo $PayeeID; ?> " />
      <input type="submit" name="Check" value="Check" /> 

<br><br>
 
<b>Account Details</b>:<br>
Name: <input type="text" name="Name" class="txtField" value="<?php echo $Name; ?>" readonly />
<br><br>
Payee Account Number:
<input type="text" name="AcctNum" class="txtField" value="">
 <span class="error">* </span>
Name In Account:
<input type="text" name="AcctName" class="txtField" value="">
 <span class="error">* </span>
Nick Name:
<input type="text" name="NickName" class="txtField" value="<?php echo $NickName; ?>" readonly>
 <br><br>
Bank Name:
<input type="text" name="BankName" class="txtField" value="">
 <span class="error">* </span>
 Bank Branch:
<input type="text" name="BankBrnch" class="txtField" value="">
 <span class="error">* </span>
 IFSC Code:
<input type="text" name="BankIFSC" class="txtField" value="">
 <span class="error">* </span>
<br><br><br>

<!--<input type="submit" name="Delete" value="Delete" onclick="delMemberDetails($link)" />  -->
<input type="submit" name="Delete" value="Delete" />  

<input type="submit" name="select" value="Update" onclick="updMemberDetails($link)" />  
<input type="button" value=" Back " class="eventsbutton" id="btnBack" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/selfservice.php';" />
<?php  if (isset($_POST['btnBack'])){header("Location:../selfservice.php"); }    ?>
 
</form>
</body>
</html>