 
<?php 
 require_once '../ssdbconfig.php';
 session_start();

function uploadfiles($link){ 
$sqlqry1 = "Select * from BSPD_Member where MEMBER_ID =" .$_POST["Member"];
$result = mysqli_query($link, $sqlqry1) or die(mysqli_error()); 
$row1 = mysqli_fetch_array($result);
if(isset($_POST["submit"]) and ($row1["Password"] == md5($_POST["OldPassword"]))){
    if ($_POST["NewPassword"] == $_POST["NewPasswordVer"]){
        $sqlupd = " UPDATE BSPD_Member 
            SET Password = md5('".$_POST["NewPassword"]."')
            where MEMBER_ID =" .$_POST["Member"] ;
            $resultIns = mysqli_query($link, $sqlupd);
            $statusMsg = "Password is Changed";
        } else { $statusMsg = "New Password is not reverified";}
}else{  $statusMsg = 'Old Password is not matching'; }

echo $statusMsg;
mysqli_close($link);
}
?> 


<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php
if($_SESSION["name"]) { if(count($_POST)>0) { uploadfiles($link);  }} 
else { header("Location:../ssLogout.php");}
?>

<form name="frmUser1" method="post" action="" enctype ="multipart/form-data" >
<div class="col-md-10 mx-auto">
<div><?php  if(isset($message)) { echo $message; } ?>  </div>
<br> 
<div class="form-group row">
<div class="col-sm-6">
<label class = "label-control">Member</label>

<?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
  <input type="text" name="Member" class="form-control" value="" />
<?php } else { ?>
  <input type="text" name="Member" class="form-control" value="<?php  echo $_SESSION["id"]; ?>" readonly />
<?php }  ?>


</div>
</div>
<div class="form-group row">
<div class="col-sm-6">
<label class = "label-control">Old Password</label>
<input type="password" name="OldPassword" class="form-control" value="" />
*<span class="error"> </span>
</div>
</div>
<div class="form-group row">
<div class="col-sm-6">
<label class = "label-control">New Password</label>
 <input type="password" name="NewPassword" class="form-control" value="" />
<span class="error">* </span>
</div>
<div class="col-sm-6">
<label class = "label-control">Reverify New Password</label>
 <input type="text" name="NewPasswordVer" class="form-control" value="" />
<span class="error">* </span>
 </div>
</div>
<div class="form-group row">
<div class="col-sm-6">
<input type="submit" name="submit" value="Update" class="btn btn-primary"/>
</div>
</div>
</div>
</form>
</body>
</html>