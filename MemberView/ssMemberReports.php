<?php 
 require_once '../ssdbconfig.php';
 include '../KeyFunctions/CommonFunctions.php';
 session_start();

function EventRecognitionDetails($link, $name){ 

  $sqlqry1 = "Select * from BSPD_View_Recognition where MemberID = '".$_POST["Member_id"]."' order by Event_ID";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Recognition details for:' .$name ;

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Event Description</th><th>Recognition</th><th>Notes</th>";
  while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['Event_Description']."</td><td>".$row['Recognition']."</td><td>".$row['Notes']."</td></tr>";
  }
}

function EventAttendanceDetails($link, $name){ 

    $sqlqry1= "SELECT * FROM BSPD_View_Event_Registration where MEMBER_ID = '".$_POST['Member_id']."' and Attended = 'Y' ";
    $result = mysqli_query($link, $sqlqry1);
    echo '<br> Attendance details for the Event : ' .$name ;

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Event Description</th><th>Event Date</th><th>Attended</th>"; 
    while ($row = mysqli_fetch_array($result)){
        echo "<tr><td>".$row['Event_Description']."</td><td>".$row['Event_date']."</td><td>".$row['Attended']."</td></tr>";
    }
}

function EventExpenseDetails($link, $name){ 
 echo '<br>  Expense details of the member :' .$name ;
 $MemberExp = GetMemberAllExpenses ($link, $_POST["Member_id"]) ;
      if (!empty($MemberExp)){
         echo "<table border='1' class='table table-bordered'>"; 
 
         echo "<th>Expense Date</th><th>Event_Decsription</th><th>Amount</th><th>Amt Details</th>";
      foreach ($MemberExp as $row){
     echo "<tr><td>".$row['TRN_DATE']."</td><td>".$row['Event_Description']."</td><td>".$row['Amount']."</td><td>".$row['Amount_Details']."</td></tr>";
   }
 }}

function EventContributionDetails($link, $name){ 
 echo '<br> Contribution for BSPD Events of:' .$name ;
 $MemberCntr = GetMemberAllContributions ($link, $_POST["Member_id"]) ;
      if (!empty($MemberCntr)){
         echo "<table border='1' style='border-collapse: collapse'>";  
         echo "<th>Contr Date</th><th>Receipt#</th><th>Event Description</th><th>Cntr Type</th><th>Amount</th><th>Receipt</th>";  
        foreach ($MemberCntr as $row){
         $receiptURL = $row['Receipt_PDF_URL'];
         echo "<tr><td>".$row['Contribution_Date']."</td><td>".$row['Transaction_Code']."</td><td>".$row['Event_Description']."</td><td>".$row['Contribution_Type']."</td><td>"
               .$row['Amount']."</td><td>"." <a href='$receiptURL' target='_blank'>$receiptURL</a>"."</td></tr>";
        }
}}

function MemberReferenceDetails($link, $name){ 

  $sqlqry1 = "Select * from BSPD_Member where Referrer_ID = '".$_POST["Member_id"]."' order by MEMBER_ID";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Members Referred by: ' .$name ;
  
  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Member ID</th><th>Member Name</th><th>Email ID</th><th>Phone Num</th>";
  while ($row = mysqli_fetch_array($result)){
     $Mphone = maskPhoneNumber($row['Phone_Num']);
     $Memail = maskEmail($row['Email_ID']);
//     echo "<tr><td>".$row['MEMBER_ID']."</td><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Phone_Num']."</td></tr>";
     echo "<tr><td>".$row['MEMBER_ID']."</td><td>".$row['Alias']."</td><td>".$Memail."</td><td>".$Mphone."</td></tr>";
  }
}


function EventDetails($link){
    
   $radiovalue = $_POST["Search"];
   $MemberID = $_POST["Member_id"];
   $row = GetMemberData ($link, $MemberID);
   $name = $row['Alias'];
   if (!empty($name)) {
 
   if ($radiovalue == "Recognition") { EventRecognitionDetails($link, $name); }
   if ($radiovalue == "Contribution") { EventContributionDetails($link, $name); }
   if ($radiovalue == "Attendance") { EventAttendanceDetails($link, $name); }
   if ($radiovalue == "Expenses") { EventExpenseDetails($link, $name); }
   if ($radiovalue == "Reference") { MemberReferenceDetails($link, $name); }
  
   } else {echo "Invalid Member";}
    
}

?> 


<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php
if($_SESSION["name"]) {
   if(count($_POST)>0) { EventDetails($link);  }
} else { header("Location:../ssLogout.php");}
?>

<form name="frmUser" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>  </div>
<br>
<b>Search by member ID:</b>
<fieldset>
  <input type="radio" style="margin-left:10px" name="Search" value="Recognition" Checked> Recognition
  <input type="radio" style="margin-left:10px" name="Search" value="Contribution"> Contribution
  <input type="radio" style="margin-left:10px" name="Search" value="Expenses">Expenses
  <input type="radio" style="margin-left:10px" name="Search" value="Attendance"> Attendance
  <input type="radio" style="margin-left:10px" name="Search" value="Reference"> References
  <br><br>
 Member ID :
  <?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
      <input type="text" class="txtField" name="Member_id"  value="" />
  <?php } else { ?>
      <input type="text" class="txtField" name="Member_id" value="<?php echo $_SESSION["id"]; ?>" readonly />
  <?php }  ?>
  <input type="submit" name="check" value="Check" class="btn btn-primary"/>    
</fieldset>
</form>

</body>
</html>