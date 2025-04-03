<?php 

   include_once '../ssdbconfig.php';
   session_start();
   include '../KeyFunctions/CommonFunctions.php';

function viewMemberDetails($link){ 
    $SrchPtr = $_POST['MemberID'];
    $sqlqry= "SELECT * FROM BSPD_Member where 
               BSPD_Member_ID like '%$SrchPtr%' or
               Surname like '%$SrchPtr%' or
               Name like '%$SrchPtr%' or
               Email_ID like '%$SrchPtr%' or
               Phone_Num like '%$SrchPtr%'  order by Year_Of_Birth";
    $result = mysqli_query($link, $sqlqry);
    echo "<table border='1' style='border-collapse: collapse'>";  
    echo "<th>Alias</th><th>Surname</th><th>Phone Num</th><th>YOB</th><th>EMail</th><th>Dup</th>"; 
    while ($row = mysqli_fetch_array($result)){
        $Mphone = maskPhoneNumber($row['Phone_Num']);
        $Memail = maskEmail($row['Email_ID']);
        if ($row['DupIndicator'] > 0 ) {
           echo "<tr><td>".$row['Alias']."</td><td>".$row['Surname']."</td><td>".$Mphone."</td><td>".$row['Year_Of_Birth']."</td><td>".$Memail."</td><td><input type='checkbox' checked='checked' disabled name='DupInd1[]' value='".$row["Surname"]."' /></td></tr>";
        } else {
           echo "<tr><td>".$row['Alias']."</td><td>".$row['Surname']."</td><td>".$Mphone."</td><td>".$row['Year_Of_Birth']."</td><td>".$Memail."</td><td><input type='checkbox' name='DupInd[]' value='".$row["MEMBER_ID"]. "-".$row["Surname"]."-".$row["DupIndicator"]."' /></td></tr>";
        }
            
            
    }
}

function updMemberDetails($link, $MemberID, $Surname, $DupNum) {

        $Dupflag = "Flag* ";
        $NewSurname = $Dupflag."".$Surname;
//        echo "<br> update" .$NewSurname. "a".$MemberID."b".$DupNum;
        
        $sqlupd = " UPDATE BSPD_Member SET DupIndicator = '$DupNum' ,
                                            Surname = '$NewSurname'
                where MEMBER_ID =" .$MemberID ;
                
        if(mysqli_query($link, $sqlupd)){ echo "Attendance updated successfully - ";  } 
        else { echo "ERROR: Could not able to execute " .mysqli_error($link);  } 
 
}

?> 

<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>
<br>
<form name="frmUser" method="post" action="">
<?php 
 $MemberID = "";

  if($_SESSION["name"]) {
    if((count($_POST)>0)) {
      if (isset($_POST["Check"])) { viewMemberDetails($link); }
      elseif (isset($_POST["Update"])) {
          $DupSQL = "Select Max(DupIndicator) as Counter from BSPD_Member" ;
          $DupCnterResult = mysqli_query($link, $DupSQL) ;
          $DupCnter = mysqli_fetch_assoc($DupCnterResult);
          $DupCounter = $DupCnter["Counter"];
          
            if(!empty($_POST["DupInd"])) { 
                foreach($_POST["DupInd"] as $products) { 
                    $DupVal = explode ("-", $products); 
                    echo "<br> testing2";
                    if ($DupVal[2] == 0){
                        echo "<br> ind" .$DupVal[2];
                        $DupCounter = $DupCounter + 1;
                        updMemberDetails($link,$DupVal[0],$DupVal[1], $DupCounter ); }}}
          viewMemberDetails($link);}
      $MemberID = $_POST["MemberID"];
      
    }
  } 
  else header("Location:../ssLogout.php");
?>

<br>Member Search Value :
<input type="text" name="MemberID" value="<?php echo $MemberID; ?>">
<input type="submit" name="Check" value="Check" />
<input type="submit" name="Update" value="Update" />
</form>
</body>
</html>