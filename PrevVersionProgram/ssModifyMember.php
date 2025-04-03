 
<?php 
 require_once '../ssdbconfig.php';
 session_start();
 include '../KeyFunctions/CommonFunctions.php';



function updMemberDetails($link){ 
   $message = "Updated details of " .$_POST["Name"];
   echo "<script type='text/javascript'>alert('$message');</script>";
 
// Check connection
if($link === false){ die("ERROR: Could not connect. " . mysqli_connect_error()); }
 
// Attempt update query execution

If ($_POST["Member"] == $_POST["MemberID"]){
   $sql1 = "UPDATE BSPD_Member SET
                Surname='" . $_POST["Surname"] . "', 
                Name='" . $_POST["Name"] . "',
                Gotram_ID = '" . $_POST["GotramID"] ."',
                Nakshatra = '" . $_POST["Nakshatra"] ."',
                Pada = '" . $_POST["Pada"] ."',
                Location='" . $_POST["Location"] ."',
                Email_ID='" . $_POST["Email_ID"] ."',
                Phone_Num = '" . $_POST["Phone_Num"] ."',
                Year_Of_Birth='" . $_POST["YOB"] ."', 
                Address1='" . $_POST["Address1"] . "', 
                Address2='" . $_POST["Address2"] . "',
                Father_ID='" . $_POST["Father_ID"] ."',
                Mother_ID='" . $_POST["Mother_ID"] ."',
                Spouse_ID='" . $_POST["Spouse_ID"] ."',
                Status='" . $_POST["Status"] ."',
                Notes='" . $_POST["Notes"] ."',
                DupIndicator='" . $_POST["DupIndicator"] ."',
                City='" . $_POST["City"] ."',
                State='" . $_POST["State"] ."',
                Country='" . $_POST["Country"] ."',
                PIN_or_ZIP='" . $_POST["PIN_or_ZIP"] ."',
                Updated_By='" . $_SESSION["id"] ."' 
            WHERE MEMBER_ID=" . $_POST["MemberID"]; 
    if(mysqli_query($link, $sql1)){ 
        echo "<div class='alert alert-success'>Member record was updated successfully."; 
        $sql2 = "UPDATE BSPD_Member_Privileges SET 
                 Smarta_Purohit='" .$_POST["SmartaPurohit"]. "',
                 Veda_Pandit='" .$_POST["VedaPandit"]. "'
                 WHERE MEMBER_ID=" . $_POST["MemberID"]; 
         if(mysqli_query($link, $sql2)){   echo "Member Privelege record was updated successfully.</div>"; }      
    } 
    else { echo "ERROR: Could not able to execute $sql1. " . mysqli_error($link);   }
}
} 
?> 

<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php
if($_SESSION["name"]) {
  if(count($_POST)>0) { $MemberID = $_POST["MemberID"];
     if (isset($_POST['select'])){ updMemberDetails($link);   }
  } else { $MemberID = $_SESSION["id"];}

 $row = GetMemberData ($link, $MemberID);
   $Member = $row['MEMBER_ID'];
   $Location_Num = $row["Location"];
   $Email_ID =$row['Email_ID'];
   $Gotram_ID =$row['Gotram_ID'];
   $Nakshatra = $row['Nakshatra'];
   $Pada = $row['Pada'];
   $Referrer_ID =$row['Referrer_ID'];
   $Address1 =$row['Address1'];
   $Address2 =$row['Address2'];
   $City =$row['City'];
   $State =$row['State'];
   $Country =$row['Country'];
   $PIN_or_ZIP = $row['PIN_or_ZIP'];
   $Spouse_ID =$row['Spouse_ID'];
   $Father_ID =$row['Father_ID'];
   $Mother_ID =$row['Mother_ID'];
   $MEMBER_TYPE = $row['MEMBER_TYPE'];

   $row1 = GetMemberPrivelege ($link, $MemberID);
   $Smartapurohit = $row1['Smarta_Purohit'];
   $Vedapandit = $row1['Veda_Pandit'];
   
  $row2 = GetPravaraDetail ($link, $Gotram_ID);
  $gotram1 = $row2['Gotra'].'-'.$row2['Risheya'].'-'.$row2['Pravara'];

  $row3 = GetNakshatraDetail ($link, $Nakshatra);
  $Nakshatra1 = $row3['All_S_English'].'/'.$row3['All_S_Telugu'];



} else { header("Location:../ssLogout.php");}

?>

<div class="row">
<form name="frmUser" method="post" action="">
<div class="col-md-10 mx-auto">
<div><?php if(isset($message)) { echo $message; } ?>  </div>
 

<div class="form-group row">
<label class="control-label">Member ID</label>
    <div class="input-group">
       <?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
          <input type="text" class="form-control" name="MemberID"  value="<?php echo $MemberID; ?>" />
       <?php } else { ?>
          <input type="text" class="form-control" name="MemberID" value="<?php echo $MemberID; ?>" readonly />
       <?php }  ?>
       <label class="control-label"> </label>
       <input type="submit" name="Check" value="Check" class="btn btn-primary" onclick="checkclicked()" />
    </div>
</div>

<div class="form-group row">
 <div class="col-sm-6">
  <label class="control-label">Member ID(below details belong to)</label>
      <input type="text" name="Member" class="form-control" value="<?php echo $Member; ?>" readonly>
 </div>
 <div class="col-sm-6">
  <label class="control-label">Phone number</label>
       <?php if ($_SESSION["id"] == 1636){ ?>
         <input type="text" name="Phone_Num" class="form-control" value="<?php echo $row['Phone_Num']; ?>" >
       <?php } else { ?>
         <input type="text" name="Phone_Num" class="form-control" value="<?php echo $row['Phone_Num']; ?>" readonly>
       <?php }  ?>
       
 </div>
</div>

<div class="form-group row">
  <div class="col-sm-3">
    <label class="control-label">First Name</label>
    <input type="text" name="Name" class="form-control" value="<?php echo $row['Name']; ?>" >
  </div>
  <div class="col-sm-3">
    <label class="control-label">Last Name</label>
       <?php if ($_SESSION["id"] == 1636){ ?>
         <input type="text" name="Surname" class="form-control" value="<?php echo $row['Surname']; ?>" >
       <?php } else { ?>
         <input type="text" name="Surname" class="form-control" value="<?php echo $row['Surname']; ?>" readonly>
       <?php }  ?>
  </div>
  <div class="col-sm-3">
    <label class="control-label">Year of Birth</label>
    <input type="text" name="YOB" class="form-control" value="<?php echo $row['Year_Of_Birth']; ?>" >
  </div>
  <div class="col-sm-3">
    <label class="control-label">Status</label>
       <?php if ($_SESSION["id"] == 1636){ ?>
         <input type="text" name="Status" class="form-control" value="<?php echo $row['Status']; ?>" >
       <?php } else { ?>
         <input type="text" name="Status" class="form-control" value="<?php echo $row['Status']; ?>" readonly>
       <?php }  ?>
  </div>
</div>

<div class="form-group row">
 <div class="col-sm-3">
  <label class="control-label">Father ID</label>
  <input type="number" name="Father_ID" value="<?php if($Father_ID)echo $Father_ID; else echo "0"; ?>" class="form-control">
 </div>
 <div class="col-sm-3">
  <label class="control-label">Mother ID</label>
  <input type="number" name="Mother_ID" value="<?php if($Mother_ID)echo $Mother_ID; else echo "0"; ?>" class="form-control">
 </div>
 <div class="col-sm-3">
  <label class="control-label">Spouse ID</label>
  <input type="number" name="Spouse_ID" value="<?php if($Spouse_ID)echo $Spouse_ID; else echo "0";?>" class="form-control">
 </div>
 <div class="col-sm-3">
    <label class="control-label">DupStatus</label>
       <?php if ($_SESSION["id"] == 1636){ ?>
         <input type="text" name="DupIndicator" class="form-control" value="<?php echo $row['DupIndicator']; ?>" >
       <?php } else { ?>
         <input type="text" name="DupIndicator" class="form-control" value="<?php echo $row['DupIndicator']; ?>" readonly>
       <?php }  ?>
  </div>
</div>

<div class="form-group row">
 <div class="col-sm-5">
  <label class="control-label">Gotram</label>
<!--input type="text" name="Gotram" class="txtField" value=""-->
  <select name="GotramID" class="form-control">
     <option value="<?php echo $Gotram_ID; ?>"><?php echo $gotram1; ?></option>
     <?php  $Gotras = GetAllGotras($link);
            if (!empty($Gotras)){
              foreach ($Gotras as $row){
              $gotramlst = $row['Gotra'].'-'.$row['Risheya'].'-'.$row['Pravara'];   
     ?>
        <option value="<?php echo $row['PG_ID']; ?>"><?php echo $gotramlst; ?></option>
     <?php    }}   ?>
  </select>
 </div>

 <div class="col-sm-5">
  <label class="control-label">Nakshatra</label>
  <select name="Nakshatra" class="form-control">
    <option value="<?php echo $Nakshatra; ?>"><?php echo $Nakshatra1; ?></option>
    <?php  $Stars = GetAllNakshatras($link);
      if (!empty($Stars)){
        foreach ($Stars as $row){
        $nakshatralst = $row['All_S_English'].'/'.$row['All_S_Telugu'];   
    ?>
    <option value="<?php echo $row['NID']; ?>"><?php echo $nakshatralst; ?></option>
    <?php }}  ?>
  </select>
 </div>
 <div class="col-sm-2">
  <label class="control-label">Pada</label>
    <select name="Pada" class="form-control">
        <option value="<?php echo $Pada; ?>"><?php echo $Pada; ?></option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>
 </div>
</div>

<div class="form-group row">
 <div class="col-sm-3">
  <label class="control-label">Smartha Purohit</label> 
  <input type="text" name="SmartaPurohit" class="form-control" value="<?php echo $Smartapurohit; ?>" />
 </div>
 <div class="col-sm-3">
  <label class="control-label">Veda Pandit</label>
  <input type="text" name="VedaPandit" class="form-control" value="<?php echo $Vedapandit; ?>" />
 </div>
 <div class="col-sm-2">
  <label class="control-label">Referrer ID</label>
  <input type="text" name="ReferrerID" class="form-control" value="<?php echo $Referrer_ID; ?>" readonly/>
 </div>
 <div class="col-sm-4">
    <label class="control-label">Email:</label>
    <input type="text" name="Email_ID" class="form-control" value="<?php echo $Email_ID; ?>">
</div>
</div>

<div class="form-group row">
 <div class="col-md-6">
  <label class="control-label">Current Address</label> 
  <input type="text" name="CurAddress" class="form-control" value="<?php echo $Address1. " - " .$Address2. " - " .$City. " - " .$State. " - " .$Country. " - " .$PIN_or_ZIP ; ?>" readonly/>
 </div>
 <div class="col-md-6">
    <label class="control-label">Notes</label>
       <?php if ($_SESSION["id"] == 1636){ ?>
         <input type="text" name="Notes" class="form-control" value="<?php echo $row['Notes']; ?>" >
       <?php } else { ?>
         <input type="text" name="Notes" class="form-control" value="<?php echo $row['Notes']; ?>" readonly>
       <?php }  ?>
  </div>
 </div>

<div class="form-group">
    <div class="col-sm-16">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" onkeyup="showSuggestions()">
                <div id="suggestions" class="autocomplete-suggestions"></div>
    </div>
    
    <div class="form-group row">
    <div class="col-sm-6">
                <label for="Address1">Address1:</label>
                <input type="text" id="address_line1" class="form-control" name="Address1" placeholder="Address1">
    </div>            
    <div class="col-sm-6">
                <label for="Address2">Address2:</label>
                <input type="text" id="address_line2" class="form-control" name="Address2" placeholder="Address2">
    </div>
    </div>
    
    <div class="form-group row">
    <div class="col-sm-3">
                <label for="City">City:</label>
                <input type="text" id="city" class="form-control" name="City" placeholder="City" readonly>
    </div>
    <div class="col-sm-3">
                <label for="State">State:</label>
                <input type="text" id="state" class="form-control" name="State" placeholder="State" readonly>
    </div>
        <div class="col-sm-3">
                <label for="Country">Country:</label>
                <input type="text" id="country" class="form-control" name="Country" placeholder="Country" readonly>
    </div>
    <div class="col-sm-3">
                <label for="Pincode">PinCode:</label>
                <input type="text" id="postcode" class="form-control" name="PinCode" placeholder="PinCode" readonly>
    </div>
    </div>
</div>

<div class="form-group row">
<div class="col-sm-6">   
<input type="submit" name="select" value="Update" onclick="updMemberDetails($link)" class="btn btn-primary"/>  
</div>
</div>
</div>
</form>








</div>
</body>
</html>