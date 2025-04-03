 
<?php 
 require_once '../ssdbconfig.php';
 session_start();

function updPayeeDetails($link){ 
 
$result1 = mysqli_query($link,"select * from BSPD_Payee where Phone_Num = '" .$_POST["Phone_Num"]. "'");  

$row1 = mysqli_fetch_array($result1);
if(is_array($row1) ) {  $message ="Payee already exists" .$_POST["Name"]; } 
else {
      $message = "Payee does not exists! ".$_POST["Name"];
      ///////////Execute Insert statement here
      $sqlIns = "INSERT INTO BSPD_Payee ( Name,  Govt_ID, Govt_ID_Num, Email_ID, Phone_Num, Address1, Address2, City, State, Country, Created_By) " ."SELECT   '" .$_POST["name"]. "',  '" .$_POST["GovtIdType"]. "', '".$_POST["GovtID"]."', '" .$_POST["email"]. "','" .$_POST["Phone_Num"]. "','" .$_POST["Address1"]."', '".$_POST["Address2"]. "', '" .$_POST["City"]. "', '" .$_POST["State"]. "', '" .$_POST["Country"]. "', ".$_SESSION["id"]. " " ;
      $resultIns = mysqli_query($link, $sqlIns );
      $rowIns = mysqli_fetch_array($resultIns);
      if(is_array($rowIns)  ) { $message ="Payee Not Exists but not Inserted" .$_POST["name"]; } 
      else {  $message ="Payee Inserted" .$_POST["name"]." " .mysqli_insert_id($link);         }
    } 
    echo "<script type='text/javascript'>alert('$message');</script>";
 //   echo mysql_insert_id();
} 
?> 

<html>
<head>
<title>New Payee Registration</title>
</head>
<body>

Welcome <b> <?php echo $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"]; ?>  </b>

<br><br>

<?php
if($_SESSION["name"]) {

  if(count($_POST)>0) {
    $errmessage= "Mandatory fields cannot  be null";
    if(empty($_POST["name"]) or empty($_POST["email"])or empty($_POST["Phone_Num"])){
       echo "<script type='text/javascript'>alert('$errmessage');</script>";
    } else{
       updPayeeDetails($link); 
    }
  }

} else { echo "<h1>Please login first .</h1>";
         header("Location:../ssLogout.php"); }
?>

<form name="frmUser1" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?> </div>
<b>New Payee Registration</b>:
<br><br>
Name:
<input type="text" name="name" class="txtField" value="">
 <span class="error">* </span>
<br><br>
Government ID(Type) :
<select name="GovtIdType">
   <option value="Aadhar Card">Aadhar Card</option>
   <option value="PAN Card">PAN Card</option>
</select>

Government ID :
<input type="text" name="GovtID" class="txtField" value="">
<br><br>
Email :
<input type="text" name="email" class="txtField" value="">
 <span class="error">* </span>

Phone :
<input type="text" name="Phone_Num" class="txtField" value="">
 <span class="error">* </span>
<br><br><br>

<b>Address</b>:
<br>
Address 1:
<input type="text" name="Address1" class="txtField" value="">
Address 2:
<input type="text" name="Address2" class="txtField" value="">
<br><br>

City:
<input type="text" name="City" class="txtField" value="">
State:
<input type="text" name="State" class="txtField" value="">
Country:
<input type="text" name="Country" class="txtField" value="">
<br><br><br>

<input type="submit" name="select" value="Submit" onclick="updPayeeDetails($link)" /> 
<input type="button" value=" Back " class="eventsbutton" id="btnBack" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/selfservice.php';" />

<?php if (isset($_POST['btnBack'])){header("Location:../selfservice.php"); } ?>

</form>
</body>
</html>