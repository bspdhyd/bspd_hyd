 
<?php 
      require_once '../ssdbconfig.php';
      //require_once 'sandbox_dbconfig.php';
      require_once 'PHPExcelReader/excel_reader.php';
      session_start();

    function UploadBulkTrn($link)
    {       // Begin Function UploadBulkTrn
        $targetDir = "../uploads/ReceiptLinks/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
        $AcceptedType = "xls";
    //Code to ensure the file can be uploaded
        if ($fileType == $AcceptedType) {}
        Else { echo "<p style='color:#C5221E'> <strong> Wrong file Type... It should be only XLS. </strong> </p>" ;  return;}
    //Code to ensure the file can be uploaded    
        
        //echo $targetFilePath;
        $statusMsg = " ";
       //array_map('unlink', array_filter((array) glob("../uploads/NBVInfo/NBVtest.xls")));
        array_map('unlink', array_filter((array) glob(".$targetFilePath.")));

        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath))  
        {
            $statusMsg = "<p style='color:DarkGreen'> <strong>File Uploaded Successfully. </strong> </p>";
        } else  
        {
         
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
        echo $statusMsg;
        //upload of file completed
        //Excel data read   
        $excel = new PhpExcelReader; // creates object instance of the class
        //echo 'testing1';
        //echo $fileName;

        $excel->read($targetFilePath); // reads and stores the excel file data
        //echo 'testing2';

        // Test to see the excel data stored in $sheets property
        //echo '<pre>';
        //var_export($excel->sheets);
        //echo '</pre>';
        //echo 'testing3';
        //function sheetData($sheet) {
        function sheetData($link, $sheet) 
        {   // Begin sheetData function 
            $re = '<table>'; // starts html table
            $x = 2;
            while($x <= $sheet['numRows']) 
            {
            $re .= "<tr>\n";
            $y = 1;
                while($y <= $sheet['numCols']) 
                {
                $cell = isset($sheet['cells'][$x][$y]) ? $sheet['cells'][$x][$y] : '';
                $re .= " <td>$cell</td>\n"; 
                //Code added by Prasad
                if ($y == "1")  {  $trn_id = $cell ;    }
                if ($y == "2")  {  $utr = $cell;      }
                if ($y == "3")  {  $p_confirmation = $cell;      }
                if ($y == "4")  {  $pay_date = $cell;      }

 //               echo "C" .$x. "D" .$cell;
                //Code add complete by Prasad
                $y++;
                } 
        
            $re .= "</tr>\n";
            //Code add  by Prasad
            
            $sqlins = "UPDATE `bspdhyd_wp1`.`BSPD_Expenses` SET `UTR_Number` = '$utr', Payment_Confirmation_ID = '$p_confirmation', Payment_Date = '$pay_date', Payment_Status = 'paid' WHERE (`TRN_ID` = '$trn_id');";                     
              if(mysqli_query($link, $sqlins)){  echo "Payment confirmation details updated in record successfully" .$trn_id." ".$utr." ".$p_confirmation." ".$pay_date."\n" ; echo "<br>" ; } 
           //   if(mysqli_query($link, $sqlins)){ } 
              else { echo "ERROR: Could not able to execute $sqlins. " . mysqli_error($link); echo "<br>" ;} 
            
 
            //Code add complete by Prasad
            $x++;
            }

        return $re .'</table>'; // ends and returns the html table
        }  // End of sheetData function 


        $nr_sheets = count($excel->sheets); // gets the number of worksheets
        //code by Prasad
        $nr_sheets = 1 ; //Modified by Prasad to ensure only one sheet is read
        //code end by Prasad
        $excel_data = ''; // to store the the html tables with data of each sheet

        // traverses the number of sheets and sets html table with each sheet data in $excel_data
        for($i=0; $i<$nr_sheets; $i++) 
        {    // Begin  for loop for the sheets - May be we should stop with just 1 sheet?  - Madhu & Prasad
        //$excel_data .= '<h4>Sheet '. ($i + 1) .' (<em>'. $excel->boundsheets[$i]['name'] .'</em>)</h4>'. sheetData($excel->sheets[$i]) .'<br/>'; 
        $excel_data .= '<h4>Sheet '. ($i + 1) .' (<em>'. $excel->boundsheets[$i]['name'] .'</em>)</h4>'. sheetData($link, $excel->sheets[$i]) .'<br/>'; 
        }  // End for loop for the sheets 

        //echo $excel_data; // outputs HTML tables with excel file data

        //Excel Data read end

    }   // End Function UploadBulkTrn

?> 

<html>
<head>
<title>Payment confirmation File Upload</title>
</head>
<body>

<?php 
//getting login credentials;
  echo '<p><h2>Welcome  ';
  echo  $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"] ;
  echo '</h2> </p>';
 
if (isset($_POST['btnBack'])){header("Location:../selfservice.php"); }

?>

<form name="frmUser" method="post" action="" enctype ="multipart/form-data" >
<div><?php if(isset($message)) { echo $message; } ?> </div>
<br> 
Payment confirmation File Upload:
column 1: TRN_ID<br>
column 2: UTR Number<br>
column 3: Payment confirmation ID<br>
column 4: Date of payment(yyyymmdd in text format)<br><br>
 <input type="file" name="file">
    <input type="submit" name="submit" value="Upload">

<br><br>
 <input type='submit' value='  Back  ' class='backbutton' name='btnBack' />
 
</form>

<?php
//if($_SESSION["name"]) {
   if(count($_POST)>0) { 
    UploadBulkTrn($link);  
    }/*
} 
else { echo "<h1>Please login first .</h1>";
       header("Location:../ssLogout.php");}*/
?>

</body>
</html>