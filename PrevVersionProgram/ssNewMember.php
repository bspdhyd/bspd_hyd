 
<?php 
 require_once '../ssdbconfig.php';
// require_once 'textlocal.class.php';
 session_start();

function updMemberDetails($link){ 
 
$result1 = mysqli_query($link,"select * from BSPD_Member where 
                      TRIM(Name) = '" .$_POST["first_name"]. "' and TRIM(Surname) = '" .$_POST["last_name"]. "' and TRIM(Phone_Num) = '" .$_POST["Phone_Num"]. "'");

$row1 = mysqli_fetch_array($result1);
if(is_array($row1) ) { $message ="Member already exists" .$_POST["first_name"]." ".$_POST["last_name"] ; } 
else { 
    $message = "Member does not exist! ".$_POST["first_name"]." ".$_POST["last_name"] ;
      ///////////Execute Insert statement here
    $sqlIns = "INSERT INTO BSPD_Member ( Surname, Name, Gender, Year_Of_Birth, Gotram_ID, Email_ID, Phone_Num, Referrer_ID, Location, BloodGroup, Password, Created_By, Notes) 
                    " ."SELECT   '" .$_POST["last_name"]. "', '" .$_POST["first_name"]. "',  '" .$_POST["Gender"]. "', ".$_POST["YOB"].", " .$_POST["GotramID"]. ", '" .$_POST["email"]. "','" .$_POST["Phone_Num"]."', ".$_POST["Referrer_Id"]. ", '" .$_POST["Location"]. "', '" .$_POST["Blood_Group"]. "',md5('" .$_POST["Phone_Num"]."'), ".$_SESSION["id"]. ", '".$_POST["Notes"]. "' " ;
    $resultIns = mysqli_query($link, $sqlIns );
    if(!$resultIns) { $message ="Member not inserted. Error in query." .$_POST["first_name"]." ".$_POST["last_name"] ; } 
    else { $message ="Member Inserted ".mysqli_insert_id($link)." ".$_POST["last_name"]." ".$_POST["first_name"];                } 
} 
echo "<script type='text/javascript'>alert('$message');</script>";
      
} 
?>


<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php
if($_SESSION["name"]) {
    if(count($_POST)>0) { $errmessage= "Mandatory fields cannot  be null";
       if(empty($_POST["first_name"]) or empty($_POST["last_name"]) or empty($_POST["email"])or empty($_POST["GotramID"]) or empty($_POST["Phone_Num"])){
         echo "<script type='text/javascript'>alert('$errmessage');</script>";
       } else{ updMemberDetails($link);  }
    }

} else  header("Location:../ssLogout.php");
?>

<div> <h3>Create new member</h3> </div>
<div><?php if(isset($message)) { echo $message; } ?>  </div>

<!--<div class="col-md-12 mx-auto"> -->
<div class="container-fluid mx-auto">
<div class="row">
<div class="col-md-10">
  <form name="frmUser1" method="post" action="" class = "form-horizontal">
    <div class="form-group row">
        <div class="col-sm-6">
            <label class = "label-control">Last Name(<b>Surname</b>)*</label>
            <input type="text" name="last_name" class = "form-control" value="" required>
        </div>
        <div class="col-sm-6">
            <label class = "label-control">First Name(Name)*</label>
            <input type="text" name="first_name" class = "form-control" value="" required>
        </div>
    </div>
    
    <div class="form-group row">
        <div class="col-sm-3">
            <label class = "label-control">Year of Birth (YOB)</label>
            <input type="number" name="YOB" value=" " min="1900" max="2050" class = "form-control">
        </div>
        <div class="col-sm-3">
            <label class = "label-control">Gender</label>
            <select name="Gender" class = "form-control">
                <option value="M">Male</option>  
                <option value="F">Female</option>
            </select>
        </div>
        <div class="col-sm-6">
            <label class = "label-control">Gotram</label>
            <select name="GotramID" class = "form-control">
                <option selected="selected" value="">Choose one</option>
                <?php
                    $Gotras = GetAllGotras($link);
                    if (!empty($Gotras)){
                        foreach ($Gotras as $row){
                        $gotramlst = $row['Gotra'].'-'.$row['Risheya'].'-'.$row['Pravara'];
                ?>
                <option value="<?php echo $row['PG_ID']; ?>"><?php echo $gotramlst; ?></option>
                <?php
                    }}
                ?>
            </select>
        </div>
    </div>
    
    <div class="form-group row">
        <div class="col-sm-6">
            <label class = "label-control">Email*</label>
            <input type="text" name="email" class = "form-control" value="">
        </div>
        <div class="col-sm-6">
            <label class = "label-control">Phone*</label>
            <input type="text" name="Phone_Num" class = "form-control" value="">
        </div>
    </div>
    
    <div class="form-group row">
        <div class="col-sm-6">
            <label class = "label-control">Location</label>
            <!--input type="text" name="Location" class="txtField" value=""-->
            <select name="Location" class = "form-control">
                <option selected="selected" value="OutsideHYD">Choose one</option>
                <?php          
                    $Locns = GetAllLocations($link);
                    if (!empty($Locns)){
                        foreach ($Locns as $row){
                ?>
                <option value="<?php echo $row['Ward']; ?>"><?php echo $row['Ward']; ?></option>
                <?php
                    }}
                ?>
            </select>
        </div>
        <div class="col-sm-6">
            <label class = "label-control">Notes</label>
            <textarea class = "form-control" name="Notes" max=250></textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-3">
            <label class = "label-control">Referrer ID*</label>
            <input type="number" name="Referrer_Id" value="" min="1000" max="99999" class = "form-control">
        </div>
        <div class="col-sm-3">
            <label class = "label-control">Blood Group:</label>
            <select name="Blood_Group" class = "form-control">
                <option value="O+">O+</option>
                <option value="O-">O-</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="NA">Unknown</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6">
            <input type="submit" name="select" value="Submit" onclick="updMemberDetails($link)" class="btn btn-success"/> 
        </div>
    </div>
  </form>
</div>
<div class="col-md-2">
    <h3>Last 10 member IDs created</h3>
    <?php

    $sql = "SELECT * FROM BSPD_Member order by MEMBER_ID desc LIMIT 10;";
    $query = mysqli_query($link, $sql);

    while($row = mysqli_fetch_array($query))
    echo $row["Alias"]."<br>";

    ?>
  
  </div>
</div>
</div>
</body>
</html>