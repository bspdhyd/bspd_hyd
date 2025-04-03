 
<?php 
 require_once '../ssdbconfig.php';
 session_start();

function updRecognitionDetails($link){ 
 
$result1 = mysqli_query($link,"select * from BSPD_Event_Recognition where Event_ID = '" .$_POST["Event_ID"]. "' and MEMBER_ID = '" .$_POST["MEMBER_ID"]. "' and Sub_Category_ID = '" .$_POST["Sub_Category_ID"]. "'");
$row1 = mysqli_fetch_array($result1);

if(is_array($row1) ) { $message ="Member Recognition already exists" .$_POST["MEMBER_ID"] ;  } 
else {
      $sqlIns = "INSERT INTO BSPD_Event_Recognition ( Event_ID, BSPD_Member_ID, Sub_Category_ID, Notes) " ."SELECT   '" .$_POST["Event_ID"]. "', '" .$_POST["Member"]. "',  " .$_POST["Sub_Category_ID"]. ",' ".$_POST["Notes"]."' " ;
      $resultIns = mysqli_query($link, $sqlIns );
      $rowIns = mysqli_fetch_array($resultIns);
      if(is_array($rowIns)  ) { $message ="Member Not Exists but not Inserted" .$_POST["Member"]." ".$_POST["Event_ID"] ;  } 
      else {  $message ="Member Recognition Inserted" .$_POST["Member"]." ".$_POST["Event_ID"];   }
    } 
echo "<script type='text/javascript'>alert('$message');</script>";
 
mysqli_close($link);
} 
?> 

<html>
<head>
<title>Add new event Recognition</title>
</head>
<body>

<?php 
//getting login credentials;
  echo ' Welcome ';
  echo  $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"];
?>
<br><br>

<?php
if($_SESSION["name"]) {
   if(count($_POST)>0) {
      $errmessage= "Mandatory fields cannot  be null";
      if(empty($_POST["Event_ID"]) or empty($_POST["Member"]) or empty($_POST["Sub_Category_ID"])or empty($_POST["Notes"])){
          echo "<script type='text/javascript'>alert('$errmessage');</script>";  }
      else{  updRecognitionDetails($link); }
    }
} else  header("Location:../ssLogout.php");
?>

<form name="frmUser1" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>  </div>
<br> 
Event ID:
<select name="Event_ID">
<?php          
    if($link === false){  die("ERROR: Could not connect. " . mysqli_connect_error());   }
    $lstMembers = mysqli_query($link,"select * from BSPD_Event where Event_Status = 1 order by Event_Date desc");
    while($row = mysqli_fetch_assoc($lstMembers)) {
?>
   <option value="<?php echo $row['EVENT_ID']; ?>"><?php echo $row['Event_Description']; ?></option>
<?php     }  ?>
 </select>
<br><br>

 Member ID : <input type="text" name="Member">
<!-- <select name="Member">
   <option selected="selected" value="Unknown">Choose one</option>
<?php          
    /*if($link === false){  die("ERROR: Could not connect. " . mysqli_connect_error());   }
    $lstMembers = mysqli_query($link,"select * from BSPD_Member where MEMBER_ID > 999 order by MEMBER_ID");
    while($row = mysqli_fetch_assoc($lstMembers)) {*/
?>
   <option value="<?php /* echo $row['MEMBER_ID']; */?>"><?php /*echo $row['Alias']; */?></option>
<?php  // }     ?>
  </select>-->
<br><br>

Recognition Category:
<select name="Sub_Category_ID">
<!--<option value="715" selected>Bhikshavandana Prasadam</option>-->
 <!--  <option selected="selected" value="700">Choose one</option>  -->
<?php          
    if($link === false){  die("ERROR: Could not connect. " . mysqli_connect_error());   }
    $lstMembers = mysqli_query($link,"select * from BSPD_Transaction_Code_Master where Category_ID = 7 order by Sub_Category_ID");
    while($row = mysqli_fetch_assoc($lstMembers)) {
?>
   
   <option value="<?php echo $row['Sub_Category_ID']; ?>"><?php echo $row['Sub_Category_Desc']; ?></option>
<?php   }        ?>
  </select>
<br><br><br>

Recognition Notes:
<input type="text" name="Notes" class="txtField" value="Rudraksha">
 <span class="error">* </span>
<br>

<input type="submit" name="select" value="Submit" onclick="updRecognitionDetails($link)" /> 
<input type="button" value=" Back " class="eventsbutton" id="btnBack" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/selfservice.php';" />

</form>
<?php
  if (isset($_POST['btnBack'])){header("Location:../selfservice.php"); }
?>
</body>
</html>