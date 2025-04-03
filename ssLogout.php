<?php
   session_start();
   unset($_SESSION["username"]);
   unset($_SESSION["password"]);
   
   echo 'You have cleaned session';
   header('Refresh: 1; URL = ssLogin.php');
//     header('Refresh: 2; URL = SelfService/ssLogin.php');
?>