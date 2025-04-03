<?php 
 require_once '../ssdbconfig.php';
 session_start();
  include '../KeyFunctions/CommonFunctions.php';

function EventDetails($link){
    
   $radiovalue = $_POST["Search"];
   
   if ($radiovalue == "PayeeD") { PayeeDetails($link); }
   if ($radiovalue == "VaidikaL") { VaidikaDetails($link); }
   if ($radiovalue == "MemberD") { MemberDetails($link); }
   if ($radiovalue == "PendingBill") { PendingBilletails($link); }
   if ($radiovalue == "PravaraD") { PravaraDetails($link); }
   if ($radiovalue == "T100D") { T100Details($link); }
   if ($radiovalue == "CategoryID") { trans_code_master($link); }

// Close connection
mysqli_close($link);
}


function PayeeDetails($link){ 
  
  if ($_POST['ActID']) { $sqlqry1 = "Select * from BSPD_Payee where Payee_ID = ".$_POST["ActID"].""; }
  Else {  $sqlqry1 = "SELECT * FROM BSPD_Payee"; }
  
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Payee details';

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>PID</th><th>Name</th><th>MID</th><th>GID</th><th>AadharURL</th><th>Email</th><th>Phno</th>";  
  
  while ($row = mysqli_fetch_array($result)){
   echo "<tr><td>".$row['Payee_ID']."</td><td>".$row['Name']."</td><td>".$row['MEMBER_ID']."</td><td>".$row['Govt_ID_Num']."</td>
   <td><a href='".$row['Aadhar_Img_URL']."' target='blank'>".$row['Aadhar_Img_URL']."</a></td><td>".$row['Email_ID']."</td><td>".$row['Phone_Num']."</td></tr>";
   
  }
}

function VaidikaDetails($link){ 

  if ($_POST['ActID']) {$sqlqry1 = "Select * from BSPD_Vaidika_List where MEMBER_ID = ".$_POST["ActID"]." and Account_Status = 'Active'"; }
  else { $sqlqry1 = "Select * from BSPD_Vaidika_List where Account_Status = 'Active'  order by MEMBER_ID"; }
  $result = mysqli_query($link, $sqlqry1);
  echo '<br> Vaidikas Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Veda Pandit Level</th><th>Email Id</th><th>Phone Num</th>";  
    while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['Alias']."</td><td>".$row['Veda_Pandi']."</td><td>".$row['Email_ID']."</td><td>".$row['Phone_Num']."</td></tr>";
    }
}

function MemberDetails($link){ 

  if ($_POST['ActID']) {$sqlqry1 = "Select * from BSPD_Member where MEMBER_ID = ".$_POST["ActID"]." and Status = 'Active'"; }
  else { $sqlqry1 = "Select * from BSPD_Member"; }
  $result = mysqli_query($link, $sqlqry1);
  echo '<br> Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Email ID</th><th>Phone Num</th><th>Notes</th>";  
    while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Phone_Num']."</td><td>".$row['Notes']."</td></tr>";
    }
}

function PendingBilletails($link){ 

  $sqlqry1 = "Select * from BSPD_View_Pending_Expenses order By EVENT_ID desc";
  $result = mysqli_query($link, $sqlqry1);
  echo '<br> Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>EventID</th><th>Amount Details</th><th>Amount</th><th>Bill Status</th><th>SoftCopy Bill</th>";  
    while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['Name']."</td><td>".$row['EVENT_ID']."</td><td>".$row['Amount_Details']."</td><td>".$row['Amount']."</td><td>".$row['Bill_Status']."</td><td>".$row['SoftCopyBill']."</td></tr>";
    }
}


function PravaraDetails($link){ 

  if ($_POST['ActID']) {$sqlqry1 = "Select * from BSPD_Pravara_Gotra where Gotra = '".$_POST["ActID"]."'"; }
  else { $sqlqry1 = "Select * from BSPD_Pravara_Gotra"; }
  $result = mysqli_query($link, $sqlqry1);
  echo '<br> Pravara Master Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Gotra ID</th><th>Gotram</th><th>Risheya</th><th>Pravara</th>";  
    while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['PG_ID']."</td><td>".$row['Gotra']."</td><td>".$row['Risheya']."</td><td>".$row['Pravara']."</td></tr>";
    }
}

function trans_code_master($link){ 
  if ($_POST['ActID']) {$sqlqry1 = "select * from BSPD_Transaction_Code_Master where Category_ID = '".$_POST["ActID"]."'"; }
  else { $sqlqry1 = "Select * from BSPD_Transaction_Code_Master"; }
  $result = mysqli_query($link, $sqlqry1);
  echo '<br> Transaction Code Master Details';
  
    echo "<table border='1' style='border-collapse: collapse'>";
    echo  "<th>Category_Type</th><th>Category_Desk</th><th>Category_ID</th><th>Sub_Category_Desk</th><th>Sub_Category_ID</th>";  
    while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['Categroy_Type']."</td><td>".$row['Category_Desc']."</td><td>".$row['Category_ID']."</td><td>".$row['Sub_Category_Desc']."</td><td>".$row['Sub_Category_ID']."</td></tr>";
    }
}

function T100Details($link){ 

  if ($_POST['ActID']) {$sqlqry = "Select * from BSPD_Member where MEMBER_ID = ".$_POST["ActID"]." and MEMBER_TYPE = 'ADMIN'" ;}
  else { $sqlqry = "Select * from BSPD_Member where MEMBER_TYPE = 'ADMIN'"; }
  $result = mysqli_query($link, $sqlqry);
  echo '<br> T100 Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Email ID</th><th>Phone Num</th><th>Nakshatra-Pada</th><th>Address</th>";  
    while ($row = mysqli_fetch_array($result)){
           $row1 = GetNakshatraDetail ($link, $row["Nakshatra"]) ;
           echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Phone_Num']."</td><td>".$row1["All_S_English"]."/".$row1['All_S_Telugu']."-".$row["Pada"]."</td>
           <td class = 'text-wrap'>".$row["Address1"]. "<br>".$row["Address2"]."<br>".$row["City"]." ".$row["State"]." ".$row["Country"]."<br>".$row["PIN_or_ZIP"]."</td></tr>";
    }
}

?> 


<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<form name="frmUser" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>  </div>
<br>
<b>Master Data Details:</b>
<fieldset>
Master Data:
<input type="radio" style="margin-left:10px" name="Search" value="PayeeD" Checked> Payee Details
<input type="radio" style="margin-left:10px" name="Search" value="VaidikaL"> Vaidikas List
<input type="radio" style="margin-left:10px" name="Search" value="MemberD"> Member Details
<input type="radio" style="margin-left:10px" name="Search" value="PravaraD"> Gotra-Pravara
<input type="radio" style="margin-left:10px" name="Search" value="T100D"> T100 Details
<input type="radio" style="margin-left:10px" name="Search" value="CategoryID"> trans code master
<input type="radio" style="margin-left:10px" name="Search" value="PendingBill"> Bills Not Submitted

<br><br>
Input ID :
 <input type="text" class="txtField" name="ActID"  value="">
 <input type="submit" name="check" value="Check" />    
</fieldset>
</form>

<?php
if($_SESSION["name"]) { if(count($_POST)>0) { EventDetails($link);  }}   
else { header("Location:../ssLogout.php");}
?>



</body>
</html>