<?php 

   require_once '../ssdbconfig.php';
   session_start();

   include '../KeyFunctions/CommonFunctions.php';

function UploadImage($link){
    
    if (isset($_POST['submit'])) {
        // Check if a file was uploaded
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            // Validate file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            echo "<br> third level" .$file_ext ; 
            if (in_array(strtolower($file_ext), $allowed_types)) {
                // Read the file contents into a binary string
                $imageData = file_get_contents($file_tmp);
                // Get the record ID from the form
                $Mid = $_POST['Mid'];
                $info = filesize($file_tmp);
                $ratio1 = 100;
                $passportWidth = 118;
                $passportHeight = 177;

                // Resize the image and save it
                $fileName = 'uploads/' . uniqid() . '.' .$file_ext;
                $result = resizeImage($_FILES["file"]["tmp_name"], $fileName, $passportWidth, $passportHeight);
                $file_tmp = $fileName;
                $imageData = file_get_contents($file_tmp);
                $info = filesize($file_tmp);
                if ($info > 50000) {
                    $ratio = 50000/$info;
                    $ratio1 = 100*$ratio;
//                    echo $ratio1;
                    $fileName = 'uploads/' . uniqid() . '.' .$file_ext ;
//                    $targetFilePath = "uploads/" . $fileName;
//                    compressImage($_FILES["file"]["tmp_name"], $fileName, $ratio1);  
                    compressImage($file_tmp, $fileName, $ratio1);  
                    $file_tmp = $fileName;
                    $imageData = file_get_contents($file_tmp);
        
                }
                
                $updqry = "UPDATE BSPD_Member SET MemImage = ? WHERE MEMBER_ID = ?";
                // Initialize a statement
                $stmt = mysqli_prepare($link, $updqry);
                // Bind the parameters
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'bi', $imageData, $Mid);
                    
                    // Send the binary data in chunks
                    mysqli_stmt_send_long_data($stmt, 0, $imageData);
                    // Execute the statement
                    if (mysqli_stmt_execute($stmt)) { echo "<br>Image uploaded and updated successfully."; } 
                    else { echo "<br>Failed to update the image."; }
                    // Close the statement
                    mysqli_stmt_close($stmt);
                }
                 else { echo "<br>Statement preparation failed: " . mysqli_error($link);}}
                else { echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed."; } }
        else { echo "No file was uploaded or there was an error in the upload process."; } }

    sleep (2) ;
    unlink ($fileName) ; 

}   
    
function ReadImage ($link){
    echo "<br> reading function";
    $Mid = $_POST['Mid'];
    $query = "SELECT * FROM BSPD_Member where MEMBER_ID = " .$Mid; // ORDER BY Image_Num";
    $result = mysqli_query($link, $query) or die(mysqli_error()); 

      while ($row = mysqli_fetch_array($result)){
            $imagepic = $row["MemImage"];
            $extension = getImageExtensionFromBlob($imagepic);
            $file = 'uploads/' . uniqid() . '.'.$extension;
            echo "<br> file place" .$file ; 
            if (file_put_contents($file, $imagepic)) {
//                echo "1".$row['MEMBER_ID']."2 Image saved successfully.";
                echo "<br><img src='$file' alt='Image'/><br>"; }
            else { echo "Failed to save the image."; }}

}

function testingfunction($link, $MEMBER_ID) {
         echo "test start" ;
         UploadImage($link);
         ReadImage($link) ;
}
?>
 
<!-- HTML Screen start -->
<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php 

//echo 'This is still work in progress and not working yet' ;

if($_SESSION["name"]) {
    if(isset($_POST["submit"])) {  testingfunction($link, $_SESSION["id"]); }
} else header("Location:../ssLogout.php");

?>

<form method="post" enctype="multipart/form-data">
    <br><div class = "border">
     <label for="Mid">Member ID:</label>
<!--     <input type="text" name="Mid" id="Mid" required>   -->
     <input type="text" name="Mid" value="<?php echo $_SESSION["id"]; ?>" readonly />
     <label for="file">Choose an image:</label>
     <input type="file" name="file" id="file" accept="uploads/*" required>
     <button type="submit" name="submit">Upload</button>
    </div><br>
</form>

</body>
</html>
