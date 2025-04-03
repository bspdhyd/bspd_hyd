<?php
/* Database credentials. Assuming you are running MySQL server with default setting (user 'root' with no password) */
//define('DB_SERVER', 'localhost');  //define('DB_SERVER', '43.255.154.9');
define('DB_SERVER', '184.168.115.30');
define('DB_USERNAME', 'madhup');
define('DB_PASSWORD', 'madhup');
define('DB_NAME', 'bspdhyd_wp1');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
// Check connection

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


//*functions to interact with Database*
function GetMemberData ($link, $MemberID)
{
    $sql =  "select * from BSPD_Member WHERE MEMBER_ID='".$MemberID."' and Status= 'Active'";
    $result = $link->query($sql); 
//    if ($result->num_rows > 0) { return $result->fetch_all(MYSQLI_ASSOC); } else { return []; }
    if ($result->num_rows == 1) { return $result->fetch_assoc(); } else { return []; }
}

function GetMemberPrivelege ($link, $MemberID)
{
    $sql =  "SELECT * from BSPD_Member_Privileges WHERE MEMBER_ID= ".$MemberID;
    $result = $link->query($sql); 
//    if ($result->num_rows > 0) { return $result->fetch_all(MYSQLI_ASSOC); } else { return []; }
    if ($result->num_rows == 1) { return $result->fetch_assoc(); } else { return []; }
}


function GetPravaraDetail ($link, $GotramID)
{
    $sql = "select * from BSPD_Pravara_Gotra WHERE PG_ID = ".$GotramID;
    $result = $link->query($sql); 
    if ($result->num_rows ==1) { return $result->fetch_assoc(); } else { return []; }
}


function GetRaasiDetail ($link, $raasi)
{
    $sql = "SELECT * FROM BSPD_Raasi where Rasi_ID = ".$raasi;
    $result = $link->query($sql); 
    if ($result->num_rows ==1) { return $result->fetch_assoc(); } else { return []; }
}

function GetEventDetail ($link, $EventID)
{
    $sql = "SELECT * FROM BSPD_Event where EVENT_ID = '".$EventID."'";
    $result = $link->query($sql); 
    if ($result->num_rows ==1) { return $result->fetch_assoc(); } else { return []; }
}

function GetNakshatraDetail ($link, $NID)
{
    $sql = "SELECT * FROM BSPD_Nakshatra where NID = ".$NID;
    $result = $link->query($sql); 
    if ($result->num_rows ==1) { return $result->fetch_assoc(); } else { return []; }
}

function GetAllGotras ($link)
{
    $sql = "SELECT * from BSPD_Pravara_Gotra where PG_ID > 0 order by Gotra";
    $result = $link->query($sql); 
    if ($result->num_rows > 0) { return $result->fetch_all(MYSQLI_ASSOC); } else { return []; }
}

function GetAllLocations ($link)
{
    $sql = "SELECT * from BSPD_Zone_Location order by Ward";
    $result = $link->query($sql); 
    if ($result->num_rows > 0) { return $result->fetch_all(MYSQLI_ASSOC); } else { return []; }
}

function GetAllNakshatras ($link)
{
    $sql = "SELECT * FROM BSPD_Nakshatra";
    $result = $link->query($sql); 
    if ($result->num_rows > 0) { return $result->fetch_all(MYSQLI_ASSOC); } else { return []; }
}

function GetMemberAllContributions ($link, $MemberID)
{
    $sql = "Select * from BSPD_View_Contribution_Report where Member_ID = ".$MemberID. " order by Transaction_Code desc";
    $result = $link->query($sql); 
    if ($result->num_rows > 0) { return $result->fetch_all(MYSQLI_ASSOC); } else { return []; }
}

function GetMemberAllExpenses ($link, $MemberID)
{
    $sql = "Select * from BSPD_View_Expense_Report where MEMBER_ID = ".$MemberID. " order by TRN_ID desc";
    $result = $link->query($sql); 
    if ($result->num_rows > 0) { return $result->fetch_all(MYSQLI_ASSOC); } else { return []; }
}









?>