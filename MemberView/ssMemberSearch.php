<?php 

   include_once '../ssdbconfig.php';
   session_start();
    include '../KeyFunctions/CommonFunctions.php';

function updMemberDetails($link){ 
    $SrchPtr = $_POST['MemberID'];
    $sqlqry= "SELECT * FROM BSPD_Member where 
               BSPD_Member_ID like '%$SrchPtr%' or
               Surname like '%$SrchPtr%' or
               Name like '%$SrchPtr%' or
               Email_ID like '%$SrchPtr%' or
               Phone_Num like '%$SrchPtr%' 
               order by Year_Of_Birth ";
    $result = mysqli_query($link, $sqlqry);
//    echo '<br> Member Details : ' .$SrchPtr ;
    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Alias</th><th>Surname</th><th>Phone Num</th><th>YOB</th><th>EMail</th>"; 
    while ($row = mysqli_fetch_array($result)){
        $Mphone = maskPhoneNumber($row['Phone_Num']);
        $Memail = maskEmail($row['Email_ID']);
        echo "<tr><td>".$row['Alias']."</td><td>".$row['Surname']."</td><td>".$Mphone."</td><td>".$row['Year_Of_Birth']."</td><td>".$Memail."</td></tr>";
    }
}
?> 

<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>
<br>
<b>Member Search:</b>
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
<br>Search Value :
<input type="text" name="MemberID" value="<?php echo $MemberID; ?>">
<input type="submit" name="check" value="Check" />
<br>
</form>
</body>
</html>