<?php
require_once '../ssdbconfig.php';
session_start();
include '../KeyFunctions/CommonFunctions.php';
 
function retrieve_members($link, $carray) {

  echo "<table border='1' style='border-collapse: collapse'>";  
  echo "<th>Alias</th><th>Email</th><th>Phone</th><th>Gotra</th><th>YOB</th>";
  
  foreach($carray as $x)
  {
    $row = GetMemberData ($link, $x);
    $row1 = GetPravaraDetail ($link, $row['Gotram_ID']);
    $Mphone = maskPhoneNumber($row['Phone_Num']);
    $Memail = maskEmail($row['Email_ID']);
    if($row)
      { echo "<tr><td>".$row["Alias"]."</td><td>".$Memail."</td><td>".$Mphone."</td><td>".$row1["Gotra"]."</td><td>".$row["Year_Of_Birth"]."</td></tr>";}
  }
  echo "</table>";
} 
 
?>

<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

    <form method="post" action "" align="center">
        <h3>Please use this page to verify member data, contributions and recognitions</h3>
        <table align="center">
            <tr><td><input type="text" name="memberid" placeholder="insert comma or space separated member ids here" size="50" <?php echo $flag; ?>></td>
            <td><input type="submit" name="submit" value="Verify"></td>
            </tr>
        </table>
    </form>
<?php

if(isset($_POST["submit"]))
{
    $string = $_POST["memberid"];
    $string = str_replace(" , "," ",$string);
    $string = str_replace("  "," ",$string);
    $string = str_replace(" ",",",$string);
    $string = str_replace(",,",",",$string);
    $carray = explode(",", $string);
    retrieve_members($link, $carray);
    retrieve_recognition($link, $carray);
}


function retrieve_recognition($link, $carray)
{
    foreach($carray as $x)
    {
        $result3=mysqli_query($link,"SELECT Alias FROM BSPD_Member where MEMBER_ID=".$x.";");
        $row3=mysqli_fetch_array($result3);

        echo "<br><table align='center' border='1' class='table table-bordered' cellspacing='0'>";
        echo '<tr><th colspan="5" style="background-color:yellow;">' .$row3["Alias"]. '</th></tr>';
        echo "<tr><th>Contribution Date</th><th>Event ID</th><th>Receipt #</th><th>Reference Details</th><th>Amount</th></tr>";

        $result2=mysqli_query($link,"SELECT 
            Contribution_Date AS CntDate,
            Transaction_Code AS Recpt,
            EVENT_ID,
            Reference_Details,
            Amount
            FROM
            bspdhyd_wp1.BSPD_View_Contribution_Report
            WHERE
            MEMBER_ID =".$x."
            ORDER BY CntDate;");
        while($row2=mysqli_fetch_array($result2))
        {
            if($row2)
            {
            echo "<tr>";
            echo "<td>".$row2["CntDate"]."</td><td>".$row2["EVENT_ID"]."</td><td>".$row2["Recpt"]."</td><td>".$row2["Reference_Details"]."</td><td align='right'>".$row2["Amount"]."</td>";
            echo "</tr>";
            }
            else
            echo "<tr><td colspan='5'>No data</td></tr>";
        }
        
       echo '<tr style="background-color:powderblue;"><th>Event Date</th><th>Event ID</th><th>Sno</th><th>SubCat Notes</th><th>Created By</th></tr>' ;
        $result1=mysqli_query($link,"SELECT 
            SNo, R.EVENT_ID, concat(Sub_Category_ID,' ',Notes) as Notes, CreatedBy, E.Event_date as Event_date
            FROM
            bspdhyd_wp1.BSPD_Event_Recognition R,
            bspdhyd_wp1.BSPD_Event E
            WHERE
            R.EVENT_ID=E.EVENT_ID and
            BSPD_Member_ID = ".$x."
            ORDER BY E.Event_date;");
        
        while($row1=mysqli_fetch_array($result1))
        {
            if($row1)
            {
            echo "<tr>";
            echo "<td>".$row1["Event_date"]."</td><td>".$row1["EVENT_ID"]."</td><td>".$row1["SNo"]."</td><td>".$row1["Notes"]."</td><td align='right'>".$row1["CreatedBy"]."</td>";
            echo "</tr>";
            }
            else
            echo "<tr><td colspan='5'>No data</td></tr>";
            
        }
        echo "</table><br><br>";
        
    }
}
?>
</body>
</html>