<?php 

   require_once '../ssdbconfig.php';
   session_start();

   include '../KeyFunctions/CommonFunctions.php';


function testingfunction($link, $MEMBER_ID) {
    
    $word = $_POST["valu"];
    $otp = generateComplexOTP(8);
    echo "Your OTP is: " . $otp;
    $_SESSION['otp'] = $otp;
    $to = $word;
    $header = 'OTP request test';
    $Message = "Please note the following \r\n";
    $Message .= "Your OTP".$otp."\r\n";
    $Message .=  "Thank you,  BSPD Hyderabad";
    SendTheMail ($to, $header, $Message);

}

function verifyfunction($link, $MEMBER_ID) {
    
    $word = $_POST["valu"];
    echo "<br> testing verification: " .$word. "<br> session value: " .$_SESSION['otp'];
    
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $word) {
        echo "OTP verified successfully!";
        // You can proceed with your further logic here
        unset($_SESSION['otp']); // Clear the OTP after successful verification
    } else { echo "Invalid OTP. Please try again.";}
}

?>
 
<!-- HTML Screen start -->
<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php 

echo 'This is working <br>' ;
if($_SESSION["name"]) {
    if(isset($_POST["register"])) {  testingfunction($link, $_SESSION["id"]); }
    elseif (isset($_POST["verify"])) {  verifyfunction($link, $_SESSION["id"]); }
    
}else header("Location:../ssLogout.php");

?>

<form method="post">
<br><div class = "border">   
<label for="option1"><b>OTP value:</b></label><br>

<div class="form-group">
                <label for="valu">Value:</label>
                <input type="text" class="form-control" id="valu" name="valu" ">
                <div id="suggestions" class="autocomplete-suggestions"></div>
</div>
</div><br>
<input type="submit" name = "register" value="Register">
<input type="submit" name = "verify" value="Verify">
</form>

</body>
</html>



