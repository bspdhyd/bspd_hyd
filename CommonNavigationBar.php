
<nav class="navbar navbar-expand-lg navbar-light bg-info">
        <a class="navbar-brand" href="#">BSPD Self-Service</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="http://www.bspd.in/SelfService/selfservice.php">Home <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Reports and Views
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/MemberView/ssMemberSearch.php">MemberSearch </a>
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/MemberView/ssMemberReports.php">Member Based Reports</a>
                        <?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssMasterDataReport.php">MasterData</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssEventReports.php">EventbasedRpts</a>
                        <?php }?>
                    </div>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
               <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Update Screens
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/MemberView/ssEventRegn.php">EventRegistration</a>
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/MemberView/ssVANrequest.php">RequestVan</a>
                        <?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssNewMember.php">NewMember</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssEventAttendance.php">EventAttendance</a>
                        <?php }?>
                    </div>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
               <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Data Issues
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/MemberView/ssRefererDataQltyIssues.php">Referrer Based DataQuality report</a>
                        <?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssMasterDataIssues.php">MasterData Issues</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssMultMemberDetails.php">Multiple Member Details</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssMemDupIdentifier.php">Duplicate Identification</a>
                        <?php }?>
                    </div>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
               <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Test(R&D) screens
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <?php if ($_SESSION["MEMBER_TYPE"] == "ADMIN"){ ?>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/TestScreen/ssAutoaddressFill.php">Autoaddress</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/TestScreen/ssTestencryption.php">Encrypt-Decrypt</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/TestScreen/ssSendWhatsapp.php">Sending Whatsapp Msg</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/TestScreen/ssTestOTP.php">Sending OTP to email</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/TestScreen/ssTestQRcode.php">Generating QRcode</a>
                           <a class="dropdown-item" href="http://www.bspd.in/SelfService/TestScreen/ssUploadImage.php">Image(resize, compress) upload and read</a>
                           <?php }?>
                    </div>
                </li>
            </ul>
        </div>
       <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
               <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $_SESSION["First_Name"]."  ".$_SESSION["Last_Name"]; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssModifyMember.php">ModifyData</a>
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/T100View/ssPasswordChange.php">ChangePassword</a>
                        <a class="dropdown-item" href="http://www.bspd.in/SelfService/ssLogout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
</nav>

