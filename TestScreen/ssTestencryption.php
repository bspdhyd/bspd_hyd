<?php 

   require_once '../ssdbconfig.php';
   session_start();

   include '../KeyFunctions/CommonFunctions.php';


function testingfunction($link, $MEMBER_ID) {
            $word = $_POST["valu"];
            $type = "Testing";
            
            $eword = EncryptDetails($link, $word, $type);
            echo "encrypted value: " .$eword. "<br>";
            $dword = DecryptDetails($link, $eword, $type) ;
            echo "decrypted word: " .$dword ;
          
            
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
} else header("Location:../ssLogout.php");

?>

<form method="post">
<br><div class = "border">   
<label for="option1"><b>Encrypt value:</b></label><br>

<div class="form-group">
                <label for="valu">Value:</label>
                <input type="text" class="form-control" id="valu" name="valu" ">
                <div id="suggestions" class="autocomplete-suggestions"></div>
</div>
</div><br>
<input type="submit" name = "register" value="Register">
</form>

</body>
</html>



