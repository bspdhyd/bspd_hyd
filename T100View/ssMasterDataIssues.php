<?php 
 require_once '../ssdbconfig.php';
 session_start();
 include '../KeyFunctions/CommonFunctions.php';
 
function ActionDetails($link){
    
   $radiovalue = $_POST["Search"];
   
   if ($radiovalue == "InvMemberD") { InvMemberDetails($link); }
   if ($radiovalue == "ReferrerD") { ReferrerIssueDetails($link); }
   if ($radiovalue == "EmailD") { EmailIssueDetails($link); }
   if ($radiovalue == "YOBD")   { YOBIssueDetails($link); }
   if ($radiovalue == "DuplicateD")   { DuplicateIssueDetails($link); }
   if ($radiovalue == "SurnameD")   { SurnameIssueDetails($link); }
   if ($radiovalue == "NonContributor")   { NonContributorIssueDetails($link); }
}

function InvMemberDetails($link){ 

  $sqlqry = "Select count(*) as AllInv from BSPD_Member where Status = 'Invalid' or Status = 'Inactive' or Status = 'Expired' order by MEMBER_ID";
  $result = mysqli_query($link, $sqlqry);
  $Count = mysqli_fetch_array($result);
  
  $sqlqry1 = "Select * from BSPD_Member where Status = 'Invalid' or Status = 'Inactive' or Status = 'Expired'";
  $result = mysqli_query($link, $sqlqry1);
  echo ' Inactive and Invalid Member Details Total--:' .$Count['AllInv'] ;

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Status</th><th>Email ID</th><th>Phone Num</th><th>Notes</th>";  
    while ($row = mysqli_fetch_array($result)){
        $Mphone = maskPhoneNumber($row['Phone_Num']);
        $Memail = maskEmail($row['Email_ID']);
         echo "<tr><td>".$row['Alias']."</td><td>".$row['Status']."</td><td>".$Memail."</td><td>".$Mphone."</td><td>".$row['Notes']."</td></tr>";
    }
}

function ReferrerIssueDetails($link){ 

  $sqlqry1 = "Select * from BSPD_Member where Referrer_ID = 0";
  $result = mysqli_query($link, $sqlqry1);
  echo ' Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Email ID</th><th>Phone Num</th><th>Notes</th>";  
    while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Phone_Num']."</td><td>".$row['Notes']."</td></tr>";
    }
}

function EmailIssueDetails($link){ 

  $sqlqry1 = "Select * from BSPD_Member where Email_ID is Null or Email_ID like 'nobody%'";
  $result = mysqli_query($link, $sqlqry1);
  echo ' Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Email ID</th><th>Phone Num</th><th>Notes</th>";  
    while ($row = mysqli_fetch_array($result)){
         echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Phone_Num']."</td><td>".$row['Notes']."</td></tr>";
    }
}

function YOBIssueDetails($link){ 

  $sqlqry1 = "Select * from BSPD_Member where Year_Of_Birth = 1980 or Year_Of_Birth = 1900";
  $result = mysqli_query($link, $sqlqry1);
  echo ' Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Email ID</th><th>Phone Num</th><th>YOB</th><th>Notes</th>";  
    while ($row = mysqli_fetch_array($result)){
        $Mphone = maskPhoneNumber($row['Phone_Num']);
        $Memail = maskEmail($row['Email_ID']);
         echo "<tr><td>".$row['Alias']."</td><td>".$Memail."</td><td>".$Mphone."</td><td>".$row['Year_Of_Birth']."</td><td>".$row['Notes']."</td></tr>";
    }
}

function DuplicateIssueDetails($link){ 

  $sqlqry1 = "Select * from BSPD_Member where DupIndicator > 0";
  $result = mysqli_query($link, $sqlqry1);
  echo ' Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>YOB</th><th>Gender</th><th>Gotram</th><th>Phone Num</th><th>Email ID</th><th>Notes</th>";  
    while ($row = mysqli_fetch_array($result)){
//        echo $row['Phone_Num'] ;
        $Mphone = maskPhoneNumber($row['Phone_Num']);
        $Memail = maskEmail($row['Email_ID']);
        $row2 = GetPravaraDetail ($link, $row['Gotram_ID']);
       $gotram1 = $row2['Gotra'].'-'.$row2['Risheya'].'-'.$row2['Pravara'];
        echo "<tr><td>".$row['Alias']."</td><td>".$row['Year_Of_Birth']."</td><td>".$row['Gender']."</td><td>".$gotram1."</td><td>".$Mphone."</td><td>".$Memail."</td><td>".$row['Notes']."</td></tr>";
    }
}

function SurnameIssueDetails($link) {
  $sqlqry1 = "Select * from BSPD_Member where length(Surname) < 4 order by Alias";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Details of quality error with No Surname:' ;

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Alias</th><th>EMail</th><th>Surname</th><th>Phone Num</th>";
  while ($row = mysqli_fetch_array($result)){
      $Mphone = maskPhoneNumber($row['Phone_Num']);
      $Memail = maskEmail($row['Email_ID']);
     echo "<tr><td>".$row['Alias']."</td><td>".$Memail."</td><td>".$row['Surname']."</td><td>".$Mphone."</td></tr>";
}}

function NonContributorIssueDetails($link){ 

  $sqlqry1 = "Select Alias, Email_ID, Gotram_ID, Phone_Num from BSPD_Member where Member_ID not in (Select Member_ID from BSPD_View_Contribution_Report)";
  $result = mysqli_query($link, $sqlqry1);
  echo ' Member Details';

    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Member Name</th><th>Gotram</th><th>Phone Num</th><th>Email ID</th><th>Notes</th>";  
    while ($row = mysqli_fetch_array($result)){
        $Mphone = maskPhoneNumber($row['Phone_Num']);
        $Memail = maskEmail($row['Email_ID']);
        $row2 = GetPravaraDetail ($link, $row['Gotram_ID']);
       $gotram1 = $row2['Gotra'].'-'.$row2['Risheya'].'-'.$row2['Pravara'];
        echo "<tr><td>".$row['Alias']."</td><td>".$gotram1."</td><td>".$Mphone."</td><td>".$Memail."</td><td>".$row['Notes']."</td></tr>";
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
  <input type="radio" style="margin-left:10px" name="Search" value="InvMemberD"> Inactive Member Details
  <input type="radio" style="margin-left:10px" name="Search" value="ReferrerD"> Referrer Missing
  <input type="radio" style="margin-left:10px" name="Search" value="EmailD"> EmailID Missing
  <input type="radio" style="margin-left:10px" name="Search" value="YOBD"> Year of Birth Missing
  <input type="radio" style="margin-left:10px" name="Search" value="DuplicateD"> Identified Duplicates
  <input type="radio" style="margin-left:10px" name="Search" value="SurnameD"> Surname Issues
  <input type="radio" style="margin-left:10px" name="Search" value="NonContributor"> Non-Contributors <br><br>
  <input type="submit" name="check" value="Check" />    
 </fieldset>
</form>
<br>
<?php
if($_SESSION["name"]) { if(count($_POST)>0) { ActionDetails($link);  }}   
else { header("Location:../ssLogout.php");}
?>
</body>
</html>