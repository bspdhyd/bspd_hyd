<?php 

   require_once '../ssdbconfig.php';
   session_start();


function delEvntReg($link, $MEMBER_ID) {
//delete all future record    
    $sql = "DELETE FROM BSPD_Event_Registration WHERE MEMBER_ID=" . $MEMBER_ID . " AND EVENT_ID IN (SELECT EVENT_ID FROM BSPD_Event WHERE Event_status = '0')";
    mysqli_query($link, $sql) or exit(mysqli_error()); 
}

function insEvntReg($link, $MEMBER_ID, $DEShCode, $EVENT_ID) {
//Insert the record
  $sql = "INSERT INTO BSPD_Event_Registration (MEMBER_ID, EVENT_ID, DEShCode,CreatedBy, UpdatedBy, Registered) " .
         "SELECT " . $MEMBER_ID . ",'". $EVENT_ID ."','". $DEShCode ."','". $MEMBER_ID ."','". $MEMBER_ID ."', 'Y' ";
  mysqli_query($link, $sql) or exit(mysqli_error()); 
}
?>
 
<!-- HTML Screen start -->
<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php 

if($_SESSION["name"]) {
    if(isset($_POST["register"])) {
      delEvntReg($link, $_SESSION["id"]);
      if (!empty($_POST["products"])){
        foreach($_POST["products"] as $products) { $reqevenid = explode ("-", $products); 
           if (!empty($_POST["products"])){ insEvntReg($link, $_SESSION["id"] ,$reqevenid[0],$reqevenid[1]);}}
      }
    }
} else header("Location:../ssLogout.php");

?>

<form method="post">
<br><div class = "border">   
<label for="option1"><b>Event Registration:</b></label><br>

<?php    
 $sqlqry = "SELECT A.EVENT_ID, A.DEShCode, A.Event_Description, B.Registered FROM BSPD_Event A LEFT JOIN BSPD_Event_Registration B on A.DEShCode =  B.DEShCode and A.EVENT_ID =  B.EVENT_ID and B.MEMBER_ID = '".$_SESSION["id"]."' where A.Event_status = '0'";    $result = mysqli_query($link, $sqlqry);
    while ($row = mysqli_fetch_array($result)){
       if (!empty($row['Registered'])){
         echo "<input type='checkbox' name='products[]' checked='checked' value='" .$row['DEShCode']. "-".$row['EVENT_ID']. "'>  - "
               .$row['DEShCode']. " - ".$row['EVENT_ID']. " - ".$row['Event_Description']."<br/><br/>";
         }
       else {
         echo "<input type='checkbox' name='products[]' value='".$row['DEShCode']. "-".$row['EVENT_ID']. "'>  - "
               .$row['DEShCode']. " - ".$row['EVENT_ID']. " - ".$row['Event_Description']."<br/><br/>";
            }
     }
?>
</div><br>
<input type="submit" name = "register" value="Register">
</form>

</body>
</html>



