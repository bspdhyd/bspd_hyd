<?php 
   session_start();
   unset($_SESSION["id"], $_SESSION["name"], $_SESSION["MEMBER_TYPE"], $_SESSION["Entity_Code"], $_SESSION["ipaddress"]);

require_once 'ssdbconfig.php';
$message="";


if(count($_POST)>0 and !empty($_POST["MEMBER_ID"])) {
   $result = mysqli_query($link,"SELECT * FROM BSPD_Member WHERE MEMBER_ID=" . $_POST["MEMBER_ID"] . " and Status= 'Active' and Password = md5('". $_POST["Password"]."')");

   $row = mysqli_fetch_array($result);
     if(is_array($row)) {
        $_SESSION["id"] = $row['MEMBER_ID'];
        $_SESSION["name"] = $row['Name'];
        $_SESSION["MEMBER_TYPE"] = $row['MEMBER_TYPE'];
        $_SESSION["Entity_Code"] = $row['DEShCode'];
        $_SESSION["First_Name"]=$row['Name'];
        $_SESSION["Alias"]=$row["Alias"];
        $_SESSION["Last_Name"]=$row['Surname'];
        $_SESSION["Location"]=$row['Location'];
        $_SESSION["Gender"]=$row['Gender'];
        $_SESSION["email"]=$row['Email_ID'];
        $_SESSION["Nakshatra"]=$row['Nakshatra'];
        $_SESSION["Pada"]=$row['Pada'];
        $_SESSION["Gotram_ID"] =$row['Gotram_ID'];
        $_SESSION["Phone_Num"]=$row['Phone_Num'];
        $_SESSION["Referrer_ID"]=$row['Referrer_ID'];
        $_SESSION["Address1"]=$row['Address1'];
        $_SESSION["Address2"]=$row['Address2'];
        $_SESSION["city_name"]=$row['City'];
        $_SESSION["PIN_or_ZIP"]=$row['PIN_or_ZIP'];
        $_SESSION["State"]=$row['State'];
        $_SESSION["Country"]=$row['Country'];
        $_SESSION["Spouse_ID"]=$row['Spouse_ID'];
        $_SESSION["Father_ID"]=$row['Father_ID'];
        $_SESSION["Mother_ID"]=$row['Mother_ID'];
     } else {  $message = "Invalid Username or Password!";}
}

 If(isset($_SESSION["id"])) {  
    //User IP address to be retrieved
    $ipaddress = '';
      if (getenv('HTTP_CLIENT_IP')) 
          $ipaddress = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
          $ipaddress = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
          $ipaddress = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
         $ipaddress = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
          $ipaddress = getenv('REMOTE_ADDR');
      else
          $ipaddress = 'UNKNOWN';
    $createdon = date('Y-m-d H:i:s');
    $_SESSION["ipaddress"] = $ipaddress;

    $sqlIns = "INSERT INTO bspd_tokens ( emp_uid, createdon, token) " ." select   '" .$_SESSION["id"]. "','" .$createdon. "', '" .$ipaddress. " ' ";

    if(mysqli_query($link, $sqlIns)){ echo "Member record was updated successfully.";     } 
    else { echo "ERROR: Could not able to execute $sqlIns. " . mysqli_error($link);   }
    
    header("Location:selfservice.php"); 
 }

?>

<!-- Initial lines of HTML screen with Head in the below file with Bootstrap classes  -->
<?php include 'Bootstraplink.php' ?>
<body>
<div class = "container col-md-4 col-sm-6 mx-auto">
    <div class="message"><?php if($message!="") { echo "<div class = 'alert alert-danger'>".$message,"</div>"; } ?></div>
    <h1 class="text-primary">BSPD Login</h1>
        
    <form name="frmUser" method="post" action="" class = "form-vertical">
        <div class="form-group">
        <label class="control-label">Username</label>
        <input type="text" name="MEMBER_ID" class="form-control">
        </div>
        <div class="form-group">
        <label class="control-label">Password</label>
        <input type="password" name="Password" class="form-control">
        </div>
        <div class="form-group">
        <input type="submit" name="submit" value="Submit" class="btn btn-primary">
        <input type="reset" class="btn btn-primary">
        </div>
    </form>
</div>
</body>
</html>