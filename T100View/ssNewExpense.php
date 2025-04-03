 
<?php 
 require_once '../ssdbconfig.php';
 session_start();

function updExpenseDetails($link){ 

$sqlqry1 = "select * from BSPD_Expenses where Event_ID = '" .$_POST["Event_ID"]. "' and Payee_ID = '" .$_POST["Payee_ID"]. "' and Subcategory_ID = " .$_POST["Sub_Category_ID"];
$result1 = mysqli_query($link, $sqlqry1) or die(mysqli_error());
$row1 = mysqli_fetch_array($result1);
if(is_array($row1) ) { $message ="Expense for the Payee already exists" .$_POST["Payee_ID"] ;  }
Else {  
      if(isset($_POST["submit"])) {
         $insert = "INSERT into BSPD_Expenses 
            (Payee_ID, Amount, EVENT_ID, Subcategory_ID, Amount_Details, Expense_Type, Expense_Bill_Num)" . 
            "SELECT '" .$_POST["Payee_ID"]. "',   ".$_POST["Amount"].", '" .$_POST["Event_ID"]. "' , " .$_POST["Sub_Category_ID"]. ", '" .$_POST["Amount_Details"]. "', '" .$_POST["Expense_Type"]. "', '" .$_POST["Expense_Bill_Num"]. "'";
         $resultIns = mysqli_query($link, $insert);
         $rowIns = mysqli_fetch_array($resultIns);
         if(is_array($rowIns)  ) { 
            $message ="Expense Not Exists but not Inserted" .$_POST["Payee_ID"]." ".$_POST["Event_ID"] ;
         } else { $message ="Payee Expense Inserted" .$_POST["Payee_ID"]." ".$_POST["Event_ID"];  }
      }
    }
echo $message;
mysqli_close($link);
}

?> 

<html>
<head>
<title>Add new Expense record</title>
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
     if(empty($_POST["Event_ID"])){ echo "<script type='text/javascript'>alert('$errmessage');</script>";  }
     else{  updExpenseDetails($link);  }
    }
} else echo header("Location:../ssLogout.php");
?>

<form name="frmUser1" method="post" action="" enctype ="multipart/form-data" >
<div><?php if(isset($message)) { echo $message; } ?>   </div>

<br> 
Event ID:
<select name="Event_ID">
<?php          
    if($link === false){   die("ERROR: Could not connect. " . mysqli_connect_error());    }
    $lstMembers = mysqli_query($link,"select * from BSPD_Event where Event_Status = 0 order by Event_Date");
    while($row = mysqli_fetch_assoc($lstMembers)) {
     $Description = $row['EVENT_ID']. "-" .$row['Event_Description'];
?>
    <option value="<?php echo $row['EVENT_ID']; ?>"><?php echo $Description; ?></option>
 <?php     }    ?>
  </select>
  
<br><br>

 Payee ID :
<select name="Payee_ID">
 <!--  <option selected="selected" value="">Choose one</option> -->
<?php          
    if($link === false){  die("ERROR: Could not connect. " . mysqli_connect_error());    }
    $lstMembers = mysqli_query($link,"select * from BSPD_Payee order by Payee_ID");
    while($row = mysqli_fetch_assoc($lstMembers)) {
?>
   <option value="<?php echo $row['Payee_ID']; ?>"><?php echo $row['Name']; ?></option>
<?php    }  ?>
  </select>
<br><br>

Recognition Category:
<select name="Sub_Category_ID">
 <!--  <option selected="selected" value="700">Choose one</option>  -->
<?php          
    if($link === false){  die("ERROR: Could not connect. " . mysqli_connect_error());    }
    $lstMembers = mysqli_query($link,"select * from BSPD_Transaction_Code_Master where Categroy_Type = 'Expense'  order by Sub_Category_ID");
    while($row = mysqli_fetch_assoc($lstMembers)) {
?>
   <option value="<?php echo $row['Sub_Category_ID']; ?>"><?php echo $row['Sub_Category_Desc']; ?></option>
<?php      }        ?>
  </select>
<br><br><br>

Expense Amount:
<input type="number" name="Amount" value="0">
 <span class="error">* </span>
 Expense Amount Description: 
 <input type="text" name="Amount_Details" class="txtField" value="">
 <span class="error">* </span>
<br><br><br>

Expense Type:
<select name="Expense_Type">
  <option value="NEFT">NEFT</option>
  <option value="CASH">CASH</option>
  <option value="KIND">KIND</option>
</select>

Expense Bill Number: 
 <input type="text" name="Expense_Bill_Num" class="txtField" value="">
 <span class="error">* </span>
<br><br><br>

<input type="submit" name="submit" value="Upload">
<input type="button" value=" Back " class="eventsbutton" id="btnBack" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/selfservice.php';" />

</form>
<?php
if (isset($_POST['btnBack'])){header("Location:../selfservice.php"); }
?>
</body>
</html>