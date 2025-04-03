 
<?php 
 require_once 'ssdbconfig.php';
 session_start();

function viewMemberLoginInfo($link){
   $row = GetPravaraDetail ($link, $_SESSION['Gotram_ID']);
   $_SESSION['Gotram'] = $row['Gotra'];
   $gotram1 = $row['Gotra'].'-'.$row['Risheya'].'-'.$row['Pravara']; 

   echo "<table border='1' style='border-collapse: collapse'>";  
   echo "<th>MEMBER_ID</th><th>Gotram</th><th>Location</th><th>Referrer Id</th>";  
   echo "<tr><td>".$_SESSION['id']."</td><td>".$gotram1."</td><td>".$_SESSION['Location']."</td><td>".$_SESSION['Referrer_ID']."</td></tr>";
   echo "</table>";
 
   echo " Your IP address - ".$_SESSION["ipaddress"]; 

}

?> 

<!-- Initial lines of HTML screen with Head in the below file with Bootstrap classes  -->
<?php include 'Bootstraplink.php' ?>

<body>
<?php include 'CommonNavigationBar.php' ?>
<br><br>

<!-- 1966 - Modali Sarada, 1286 - Kalaga Kamesh, 1405 - MSN, 1960 - Ushasree, 8719 KuchiMadhavi, 1955 Bhramaramba -->
<?php if (( $_SESSION["id"] == 1636) or ( $_SESSION["id"] == 1116) or ( $_SESSION["id"] == 1177) or ( $_SESSION["id"] == 1503) or ( $_SESSION["id"] == 1960))  { ?>
  <b>Administrative tasks</b><br>
  <input type="button" value="CrPayee-NonMember" class="eventsbutton" id="btnNewPayee"  onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/ssNewPayee.php';" />
  <input type="button" value="CrPayee-Member" class="eventsbutton" id="btnRdImg" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/ssCreatePayee.php';" />  
  <input type="button" value="Payee Account" class="eventsbutton" id="btnRdImg" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/ssPayeeAddress.php';" />  
  <input type="button" value="JPRegn" class="eventsbutton" id="btnJP" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/jpRegistration.php';" />

<?php } ?>
<!-- Specific for 2 people - 1123 Daggubati 1402 MLN -->
<?php if (( $_SESSION["id"] == 1116) or ( $_SESSION["id"] == 1636) or
          ( $_SESSION["id"] == 1123) or ( $_SESSION["id"] == 1402)) { ?>
   <input type="button" value="VerifyContribution" class="eventsbutton" id="btnVerCtr" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/ssVerifyContribution.php';" />  
<?php } ?>
<!-- End Specific screens for just 2 people  -->
  <br><br>

<!-- start Specific screens for prasad for testing images  -->
<?php if (( $_SESSION["id"] == 1636) or ( $_SESSION["id"] == 1503) or ( $_SESSION["id"] == 1116) or ( $_SESSION["id"] == 1966) or ( $_SESSION["id"] == 1933) or ( $_SESSION["id"] == 1969) ) { ?>
  <b>Views for Prasad</b><br>
  <input type="button" value="SIBCDat" class="eventsbutton" id="SIBCDat" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/ssSIBCollectiondata.php';" />  
  <input type="button" value="Receipt Link Update" class="eventsbutton" id="SIBCDat" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/receiptlinkupdate.php';" />  
  <input type="button" value="Payment Conf Details Update" class="eventsbutton" id="Pconf" onClick="Javascript:window.location.href = 'http://www.bspd.in/SelfService/AdminTasks/utrpaymentconfirmationupdate.php';" />  
<?php } ?>
<br><br>
<?php
 if($_SESSION["name"]) { viewMemberLoginInfo($link); }
 else { header("Location:ssLogout.php");}
?>

<form name="frmUser" method="post" action="">
<div><?php if(isset($message)) { echo $message; } ?>
</div>

<br> 
<?php 
  echo "<br>Email : ".$_SESSION["email"];
  ?>
<br><br>
<b>Your Gotram, Nakshatram and Padam for sankalpam:</b><br>
<?php
   $row = GetNakshatraDetail ($link, $_SESSION["Nakshatra"]);
   echo $_SESSION["Gotram"].", ".$row["All_S_English"]."/".$row["All_S_Telugu"].", Padam ".$_SESSION["Pada"]."<br>";
    
   $raasi=0;
   $npada = (($_SESSION["Nakshatra"]-1)*4)+$_SESSION["Pada"];
   $raasi = ceil($npada/9);

   $row = GetRaasiDetail ($link, $raasi); 
   echo "Raasi : ".$row["Raasi_S_English"]."/".$row["Raasi_S_Telugu"]."<br><br>";
?>
<b>Your address for BSPD communication:</b><br>
<!--Address label styling -->
<div style="border:solid; display: inline-block; color:#0000FF; background-color:#FFFACD">
<?php
   echo  $_SESSION["Alias"]."<br>"
        .$_SESSION["Address1"].", "
        .$_SESSION["Address2"]. "<br>"
        .$_SESSION["city_name"]. ", "
        .$_SESSION["State"].", "
        .$_SESSION["Country"]." - "
        .$_SESSION["PIN_or_ZIP"]."<br>Phone : "
        .$_SESSION["Phone_Num"];
?>

<br>
</div>
</form>
</body>
</html>