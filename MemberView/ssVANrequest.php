 
<?php 

   include_once '../ssdbconfig.php';
   include_once '../KeyFunctions/SendingMail.php';
   session_start();

function updMemberDetails($link){ 
//Get the name of individual
$row1 = GetMemberData ($link, $_POST["MemberID"]);
if (!empty($row1['Surname'])) {
   echo "<table border='1' style='border-collapse: collapse'>";  
   echo "<th>MEMBER_ID</th><th>Email ID</th><th>Last Name</th><th>First Name</th>";  
   echo "<tr><td>".$_POST["MemberID"]."</td><td>".$row1['Email_ID']."</td><td>".$row1['Surname']."</td><td>".$row1['Name']."</td></tr>";
   echo "</table><br>";
if ((isset($_POST['select'])) or (isset($_POST['email'])) ){
   if ($_POST["ContrType"] == " ") {
       echo "Please select Contribution Type" ;}
      Else {
        $type = $_POST["ContrType"];
        $Member_Act = $row1['BSPD_Member_ID'];
        
        if ($type == 'CH') { $Contribute = "Chandihomam" ;}
        if ($type == 'BV') { $Contribute = "BikshaVandanam" ;}
        if ($type == 'GN') { $Contribute = "General Contribution" ;}
        $VanNum = "A345A11".$type."".$Member_Act;

        echo "For ".$Contribute." at BSPD Hyderabad ";
        if ($_POST["ContrType"] == 'CD') { 
            echo "<br><b>This account is exclusively created for Bulk transfer. Do not use for Individual member transfers</b>"; }
        echo "<br><br> Name of the Account(Beneficiary): BSPD";
        echo "<br>Nickname     : BSPD".$type." ";
        echo "<br>IFSC CODE    : SIBL0000722 ";
        echo "<br>Account Type : Current ";
        echo "<br>Accnt number : ".$VanNum;
        echo "<br>Bank Name    : South India Bank"; 
        echo "<br>Branch       : Corporate Branch, Hyderabad";
       
// start code for sending mail
      if (isset($_POST['email']))
      {
            $to = $row1["Email_ID"];
            $header = 'Van number request';
            $Message = "Dear ".$row1['Name']. " Garu,\r\n" ;
            $Message .= "BSPD Member Id : ".$row1['BSPD_Member_ID']. "\r\n";
            $Message .= "Please note the following \r\n";
            $Message .= "(1) This account number is exclusively created for ".$row1['Name']. " Irrespective of whoever transfers to this account, contribution will be treated as done by ".$row1['Name']. ". \r\n";
            $Message .="(2) You can do transfer by NEFT only. No other means would work \r\n";
            $Message .="------------------------------------------------\r\n";
            $Message .= "For ".$Contribute." at BSPD Hyderabad \r\n";
            $Message .= "Name of the Account(Beneficiary): BSPD \r\n";
            $Message .= "Nickname     : BSPD".$type."\r\n";
            $Message .= "IFSC CODE    : SIBL0000722 \r\n";
            $Message .= "Account Type : Current \r\n";
            $Message .= "Accnt number : ".$VanNum. "\r\n";
            $Message .= "Bank Name    : South India Bank \r\n";
            $Message .= "Branch       : Corporate Branch, Hyderabad \r\n";
            $Message .="------------------------------------------------\r\n";
            $Message .=  "Thank you,  BSPD Hyderabad";
            SendTheMail ($to, $header, $Message);
       }
    } 
    
}
}else  {  echo ' <b> Non existent member </b>';    } 
}
?> 

<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>
<br>
<b>Van Number for Contribution:</b>
<?php 
 $MemberID = "";
  if($_SESSION["name"]) {
    if((count($_POST)>0) and ($_POST["MemberID"] > 1000)) {
      updMemberDetails($link);
      $MemberID = $_POST["MemberID"];
    }
  } 
  else header("Location:../ssLogout.php");
?>

<form name="frmUser" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>
</div>
<br>member ID :
<input type="number" name="MemberID" value="<?php echo $MemberID; ?>">
<input type="submit" name="check" value="Check" />  
<br><br>
<fieldset>
<input type="radio" name="ContrType" value="CH" Checked> ChandiHomam
<input type="radio" name="ContrType" value="BV"> BikshaVandanam
<input type="radio" name="ContrType" value="GN"> General
<br><br>
<input type="submit" name="select" value="Display Now"  />  
<input type="submit" name="email" value="EMail"  />
</fieldset>
</form>
</body>
</html>