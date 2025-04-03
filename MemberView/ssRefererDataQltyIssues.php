<?php 
 require_once '../ssdbconfig.php';
 session_start();

function EventYrBirthDetails($link, $name){ 

  $sqlqry1 = "Select * from BSPD_Member where Referrer_ID = '".$_POST["Member_id"]."' and (Year_Of_Birth = '1980' or Year_Of_Birth = '1900') order by Alias";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Details of YOB quality error with Referrer:' .$name ;

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Alias</th><th>EMail</th><th>YearOfBirth</th>";
  while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Year_Of_Birth']."</td></tr>";
}}

function EventSurnameDetails($link, $name){ 
  $sqlqry1 = "Select * from BSPD_Member where Referrer_ID = '".$_POST["Member_id"]."' and (length(Surname) < 4) order by Alias";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Details of YOB quality error with Referrer:' .$name ;

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Alias</th><th>EMail</th><th>Surname</th>";
  while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Surname']."</td></tr>";
}}

function EventAddrIssueDetails($link, $name){ 
  $sqlqry1 = "Select * from BSPD_Member where Referrer_ID = '".$_POST["Member_id"]."' and Pin_Or_Zip is Null order by Alias";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Details of YOB quality error with Referrer:' .$name ;

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Alias</th><th>EMail</th><th>Address 1</th><th>Address 2</th><th>Pin Code</th>";
  while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Address1']."</td><td>".$row['Address2']."</td><td>".$row['Pin_Or_Zip']."</td></tr>";
 }}

function EventParentDataDetails($link, $name){ 
  $sqlqry1 = "Select * from BSPD_Member where Referrer_ID = '".$_POST["Member_id"]."' and (Father_ID = '0' or Mother_ID = '0') order by Alias";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Details of YOB quality error with Referrer:' .$name ;

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Alias</th><th>EMail</th><th>Father ID</th><th>Mother ID</th><th>Spouse ID</th>";
  while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['Father_ID']."</td><td>".$row['Mother_ID']."</td><td>".$row['Spouse_ID']."</td></tr>";
}}

function MemberDupIssueDetails($link, $name){ 

  $sqlqry1 = "Select * from BSPD_Member where Referrer_ID = '".$_POST["Member_id"]."' and DupIndicator > 0 order by Alias";
  $result = mysqli_query($link, $sqlqry1); 
  echo '<br>Members Referred by: ' .$name ;
  
  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Member ID</th><th>Member Name</th><th>Email ID</th><th>Duplicate</th>";
  while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['MEMBER_ID']."</td><td>".$row['Alias']."</td><td>".$row['Email_ID']."</td><td>".$row['DupIndicator']."</td></tr>";
}}

function EventDetails($link){
    
   $radiovalue = $_POST["Search"];
   $MemberID = $_POST["Member_id"];
   $row = GetMemberData ($link, $MemberID);
   $name = $row['Alias'];
   if (!empty($name)) {
 
       if ($radiovalue == "YrBirth") { EventYrBirthDetails($link, $name); }
       if ($radiovalue == "Surname") { EventSurnameDetails($link, $name); }
       if ($radiovalue == "ParentData") { EventParentDataDetails($link, $name); }
       if ($radiovalue == "AddrIssue") { EventAddrIssueDetails($link, $name); }
       if ($radiovalue == "DupIssue") { MemberDupIssueDetails($link, $name); }
   } else {echo "Invalid Member";}
    
}

?> 


<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php
if($_SESSION["name"]) { if(count($_POST)>0) { EventDetails($link);  }
} else { header("Location:../ssLogout.php");}
?>

<form name="frmUser" method="post" action="">
    <div><?php if(isset($message)) { echo $message; } ?>  </div>
    <br> <b>Data Issues by Referrer ID:</b>
    <fieldset>
      <input type="radio" style="margin-left:10px" name="Search" value="YrBirth" Checked> YearofBirth
      <input type="radio" style="margin-left:10px" name="Search" value="Surname"> SurnameIssue
      <input type="radio" style="margin-left:10px" name="Search" value="AddrIssue">Address Issue
      <input type="radio" style="margin-left:10px" name="Search" value="ParentData"> ParentData
      <input type="radio" style="margin-left:10px" name="Search" value="DupIssue"> Duplicate Issues
      <br><br>
     Referrer ID :
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