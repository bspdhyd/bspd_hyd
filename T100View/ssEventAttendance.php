 
<?php 
   require_once '../ssdbconfig.php';
   session_start();

function updMemberDetails($link){ 
   $row1 = GetMemberData ($link, $_POST["Member_1"]);
if (!empty($row1['Surname'])) {
   echo "<table border='1' style='border-collapse: collapse'>";  
   echo "<th>MEMBER_ID</th><th>Last Name</th><th>First Name</th>";  
   echo "<tr><td>".$_POST["Member_1"]."</td><td>".$row1['Surname']."</td><td>".$row1['Name']."</td></tr>";
   echo "</table>";

   if (isset($_POST['select'])){
      $EntityCode = substr($_POST["Event_ID"], 0,8);
      $Eventid = substr($_POST["Event_ID"],8);
      $sql1 = "select * from BSPD_Event_Registration where MEMBER_ID =" .$_POST["Member_1"]. " and DEShCode ='" .$EntityCode. "' and EVENT_ID = '" .$Eventid. "'";
      $result2 = mysqli_query($link, $sql1) or die(mysqli_error()); 
      $row =  mysqli_fetch_array($result2);
      if (!empty($row['EVENT_ID'])){
        $sqlupd = " UPDATE BSPD_Event_Registration SET Attended = 'Y' where MEMBER_ID =" .$_POST["Member_1"]. " and DEShCode ='" .$EntityCode. "' and EVENT_ID ='" .$Eventid."'";
        if(mysqli_query($link, $sqlupd)){ echo "Attendance updated successfully - " .$Eventid;  } 
        else { echo "ERROR: Could not able to execute $sqlupd. " . mysqli_error($link);  }

      } else {
            $sqlins = "INSERT INTO BSPD_Event_Registration (MEMBER_ID, EVENT_ID, DEShCode,CreatedBy, UpdatedBy, Attended) " ."SELECT "
                       . $_POST["Member_1"] . ",'". $Eventid ."','". $EntityCode ."',". $_POST["Member_1"] .",". $_POST["Member_1"] .", 'Y' ";

           if(mysqli_query($link, $sqlins)){  echo "Attendance inserted successfully for - " .$Eventid;  } 
           else { echo "ERROR: Could not able to execute $sqlins. " . mysqli_error($link); }

      }
    } 

}else{ echo ' <b> Non existent member </b>'; }

} 
?> 


<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php  echo ' <b> Event Attendace </b><br><br>';   
   if($_SESSION["name"]) {
      if((count($_POST)>0) and ($_POST["Member_1"] > 1000)) {$MemberID = $_POST["Member_1"];
        if (isset($_POST['select'])){ $MemberID = " ";}
        updMemberDetails($link);
      }
   } else { header("Location:../ssLogout.php");}
?>

<form name="frmUser" method="post" action="">

<br> 
Member_ID :
 <input type="number" name="Member_1" value="<?php echo $MemberID; ?>">
 <input type="submit" name="check" value="Check" />    
<br><br>
Event ID :
<select name="Event_ID">
<?php          
   if($link === false){ die("ERROR: Could not connect. " . mysqli_connect_error());  }
   $lstMembers = mysqli_query($link, "select * from BSPD_Event where Event_Status = 0 order by Event_date");
   while($row = mysqli_fetch_assoc($lstMembers)) {
       $EventDesc = $row['DEShCode']. " ". $row['Event_Description'];
?>
 <option value="<?php echo $row['DEShCode'], $row['EVENT_ID']; ?>"><?php echo $row['DEShCode'],$row['Event_Description']; ?></option>

<?php  }  ?>
</select>

<br><br>
 <input type="submit" name="select" value="Update" />     
 
</form>
</body>
</html>