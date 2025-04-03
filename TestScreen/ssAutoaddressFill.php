<?php 

   require_once '../ssdbconfig.php';
   session_start();

   include '../KeyFunctions/CommonFunctions.php';

function delEvntReg($link, $MEMBER_ID) {
echo "aaa" .$_POST["City"];

}

?>
 
<!-- HTML Screen start -->
<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php 

if($_SESSION["name"]) {
    if(isset($_POST["register"])) {  delEvntReg($link, $_SESSION["id"]); }
} else header("Location:../ssLogout.php");

?>

<form method="post">
<br><div class = "border">   
<label for="option1"><b>Auto Address:</b></label><br>

<div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" onkeyup="showSuggestions()">
                <div id="suggestions" class="autocomplete-suggestions"></div>
                <br>
                <label for="Address1">Address1:</label>
                <input type="text" id="address_line1" name="Address1" placeholder="Address1" readonly>
                
                <label for="Address2">Address2:</label>
                <input type="text" id="address_line2" name="Address2" placeholder="Address2" readonly>

                <label for="City">City:</label>
                <input type="text" id="city" name="City" placeholder="City" readonly>

                <label for="State">State:</label>
                <input type="text" id="state" name="State" placeholder="State" readonly>

                <label for="Pincode">PinCode:</label>
                <input type="text" id="postcode" name="PinCode" placeholder="PinCode" readonly>

                <label for="Country">Country:</label>
                <input type="text" id="country" name="Country" placeholder="Country" readonly>
</div>
</div><br>
<input type="submit" name = "register" value="Register">
</form>

</body>
</html>



