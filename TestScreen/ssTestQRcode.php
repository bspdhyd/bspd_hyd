<?php 

require_once '../ssdbconfig.php';
session_start();
include '../KeyFunctions/CommonFunctions.php';



function testingfunction($link, $MEMBER_ID, $UID, $Amount) {

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// UPI payment details
//$upi_id = "yourbankaccountnumber@upi";  //if this is bank account number
$upi_id = $UID;  //if this is UPI ID
$amount = $Amount;
$note = $MEMBER_ID;
$payee_name = "BSPD";
$merchant_code = "BNR";
//$merchant_code = "IFSC1234567"; // IFSC code of the bank

// Create the UPI URL with parameters
$upi_url = "upi://pay?pa=$upi_id&pn=$payee_name&am=$amount&cu=INR&mc=$merchant_code&tn=$note";

// Log UPI URL for debugging purposes 
//echo 'UPI URL: ' . htmlspecialchars($upi_url) . '<br>';

// URL encode the UPI URL for the Google Chart API
$upi_url_encoded = urlencode($upi_url);

// Log encoded UPI URL for debugging purposes
//echo 'Encoded UPI URL: ' . htmlspecialchars($upi_url_encoded) . '<br>';

// Generate the QR code URL using Google Chart API
//$qr_code_url = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=" . $upi_url_encoded . "&choe=UTF-8";
$qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . $upi_url_encoded;

// Log QR code URL for debugging purposes
//echo 'QR Code URL: ' . htmlspecialchars($qr_code_url) . '<br>';

// Display the QR code image
echo '<img src="' . $qr_code_url . '" alt="UPI QR Code">';


}

?>
 
<!-- HTML Screen start -->
<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php 

echo 'This is working <br>' ;
if($_SESSION["name"]) {
    if(isset($_POST["register"])) {  testingfunction($link, $_POST["Member"], $_POST["UPIid"], $_POST["Contri"]); }
} else header("Location:../ssLogout.php");

?>

<form method="post">


<br><div class = "border">   
<label for="option1"><b>Encrypt value:</b></label><br>

<div class="form-group">
                <label for="UPIid">UPIID:</label>
                <input type="text" class="form-control" id="UPIid" name="UPIid" ">
                <label for="Member">Paying MemberID:</label>
                <input type="text" class="form-control" id="Member" name="Member" ">
                <label for="Contri">Contribution:</label>
                <input type="text" class="form-control" id="Contri" name="Contri" ">
                <div id="suggestions" class="autocomplete-suggestions"></div>
</div>
</div><br>
<input type="submit" name = "register" value="Register">
</form>

</body>
</html>


