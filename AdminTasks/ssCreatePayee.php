 
<?php 
 require_once '../ssdbconfig.php';

 session_start();
 
// $link = $link;

function updMemberDetails($link){ 
    
If ($_POST["Member"] == $_POST["MemberID"]){
   $fullname = $_POST["Name"]." ".$_POST["Surname"];
   $result1 = mysqli_query($link,"select * from BSPD_Payee where MEMBER_ID = '" .$_POST["MemberID"]. "'");  

   $row1 = mysqli_fetch_array($result1);
   if(is_array($row1) ) { $message ="Payee already exists" .$fullname;
     } else {
         $message = "Payee does not exists! ".$_POST["Name"];
         ///////////Execute Insert statement here
         $sqlIns = "INSERT INTO BSPD_Payee ( Name, MEMBER_ID, Govt_ID, Govt_ID_Num, Email_ID, Phone_Num, Address1, Address2, City, State, Country, Created_By) " .
                  "SELECT   '" .$fullname. "', " .$_POST["MemberID"]. ", '" .$_POST["GovtIdType"]. "', '" .$_POST["GovtID"]. "', '" .$_POST["Email_ID"]. "','" .$_POST["Phone_Num"]. "','" .$_POST["Address1"]."', '".$_POST["Address2"]. "', '" .$_POST["City"]. "', '" .$_POST["State"]. "', '" .$_POST["Country"]. "', ".$_SESSION["id"]. " " ;
      
//         echo "xxxxxxxxxxxxxx.." . $sqlIns;
         $resultIns = mysqli_query($link, $sqlIns );
         $rowIns = mysqli_fetch_array($resultIns);
         if(is_array($rowIns)  ) { $message ="Payee Not Exists but not Inserted" .$_POST["Name"];} 
         else { $message ="Payee Inserted" .$_POST["Name"]." " .mysqli_insert_id($link); }
        } 
     echo "<script type='text/javascript'>alert('$message');</script>";
}   
    
// Close connection
//mysqli_close($link);
} 

?> 

<html>
<head>
<title>Update Payee Details</title>
</head>
<body>


Welcome <b> <?php echo $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"]; ?>  </b>
<br><br> 

<?php

if($_SESSION["name"]) {
  $MemberID = $_SESSION["id"];  
// Initializing variables 
  $Surname = $Member = $Email_ID = $Name = $Phone_Num = $City = $State = $Country = $Address1 = $Address2 = "" ; 
  
  if(count($_POST)>0) { $MemberID = $_POST["MemberID"];
    if (isset($_POST['select'])){ updMemberDetails($link);}
  } 
//  validateMemberLoginInfo($MemberID); 

// testing by removing function START
  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>MEMBER_ID</th><th>Gotram</th><th>Location</th><th>Referrer Id</th>";  
 $result = mysqli_query($link, "select * from BSPD_Member WHERE MEMBER_ID= ".$MemberID);  
   while ($row = mysqli_fetch_array($result))   
   {  
    $Name =$row['Name'];
    $Member = $row['MEMBER_ID'];
    $Surname =$row['Surname'];
    $Location =$row['Location'];
    $Email_ID =$row['Email_ID'];
    $Birthyear =$row['Year_Of_Birth'];
    $Gotram =$row['Gotram'];
    $Phone_Num =$row['Phone_Num'];
    $Referrer_ID =$row['Referrer_ID'];
    $MEMBER_TYPE =$row['MEMBER_TYPE'];
    $Address1 =$row['Address1'];
    $Address2 =$row['Address2'];
    $City =$row['City'];
    $State =$row['State'];
    $Country =$row['Country'];
    $Spouse_ID =$row['Spouse_ID'];
    $Father_ID =$row['Father_ID'];
    $Mother_ID =$row['Mother_ID'];
    echo "<tr><td>".$MemberID."</td><td>".$Gotram."</td><td>".$Location."</td><td>".$Referrer_ID."</td></tr>";
   } 

   echo "</table>";

} else { echo "<h1>Please login first .</h1>";
         header("Location:../ssLogout.php"); }

?>

<form name="frmUser" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>   </div>
<br><br> 

Member ID :
<?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
      <input type="text" name="MemberID" class="txtField" value="<?php echo $MemberID; ?>" />
<?php } else { ?>
      <input type="text" name="MemberID" class="txtField" value="<?php echo $MemberID; ?>" readonly />
<?php }  ?>
<input type="submit" name="Check" value="Check" onclick="checkclicked()" />

<br><br>
Member ID(below details belong to):
<input type="text" name="Member" class="txtField" value="<?php echo $Member; ?>" readonly>
<input type="text" name="Phone_Num" class="txtField" value="<?php echo $Phone_Num; ?>" readonly />
<br><br> 
First Name :
<input type="text" name="Name" class="txtField" value="<?php echo $Name; ?>">
<br>
Last Name  :
<input type="text" name="Surname" class="txtField" value="<?php echo $Surname; ?>">
<br><br> 
Government ID(Type) :
<select name="GovtIdType">
   <option value="Aadhar Card">Aadhar Card</option>
   <option value="PAN Card">PAN Card</option>
</select>

Government ID :
<input type="text" name="GovtID" class="txtField" value="">
<br><br>
Address1 :
<input type="text" name="Address1" class="txtField" value="<?php echo $Address1; ?>">
<br>
Address2 :
<input type="text" name="Address2" class="txtField" value="<?php echo $Address2; ?>">
<br><br>
City :
<input type="text" name="City" class="txtField" value="<?php echo $City; ?>">
State :
<input type="text" name="State" class="txtField" value="<?php echo $State; ?>">
Country :
<input type="text" name="Country" class="txtField" value="<?php echo $Country; ?>">
<br><br>
Email:<br>
<input type="text" name="Email_ID" class="txtField" value="<?php echo $Email_ID; ?>">
<br>
<br>
<input type="submit" name="select" value="CrPayee" onclick="updMemberDetails($link)" />  
<input type="button" value=" Back " class="eventsbutton" id="btnBack" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/selfservice.php';" />
<?php if (isset($_POST['btnBack'])){header("Location:../selfservice.php"); } ?>
 
 
</form>
</body>
</html>