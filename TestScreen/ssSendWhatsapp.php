<?php 

   require_once '../ssdbconfig.php';
   session_start();

   include '../KeyFunctions/CommonFunctions.php';

function pairwhatsappdevice($rapidApiKey){ 
 $curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://waspable1.p.rapidapi.com/otp/messages",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
        'to' => '919949870057',
        'content' => [
            'type' => 'text',
            'body' => 'Check out this link: https://www.example.com',
            'isPreviewUrl' => true
        ]
    ]),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "x-rapidapi-host: waspable1.p.rapidapi.com",
        "x-rapidapi-key: 394da5d199msh269b7f2fd36b66ep18803djsnbc07de4bc793"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
 
}


function sendWhatsAppMessage($senderNumber, $receiverNumber, $messageContent) {
    $curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://waspable1.p.rapidapi.com/otp/messages",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => json_encode([
		'to' => '919949870057',
		'content' => [
				'type' => 'text',
				'body' => 'hello world',
				'isPreviewUrl' => true
		]
	]),
	CURLOPT_HTTPHEADER => [
		"Content-Type: application/json",
		"x-rapidapi-host: waspable1.p.rapidapi.com",
		"x-rapidapi-key: 394da5d199msh269b7f2fd36b66ep18803djsnbc07de4bc793"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}

}


function testingfunction($link, $MEMBER_ID) {
            $to = $_POST["valu"];
            $from = $_SESSION["Phone_Num"];


// Example usage
            $senderNumber = "919949097105"; // Your WhatsApp number with country code
            $receiverNumber = "919949870057"; // Indian WhatsApp number with country code (91 for India)
            $messageContent = "Hello, this is a test message from PHP!";
            $rapidApiKey = "394da5d199msh269b7f2fd36b66ep18803djsnbc07de4bc793";
            $setid = pairwhatsappdevice($rapidApiKey);
            $response = sendWhatsAppMessage($senderNumber, $receiverNumber, $messageContent, $rapidApiKey);

echo $response;

            
            
            
            
            
    echo "Mail sent to" .$_SESSION["Name"];

}

?>
 
<!-- HTML Screen start -->
<?php include '../Bootstraplink.php' ?>
<body>
<?php include '../CommonNavigationBar.php' ?>

<?php 

echo 'This is still work in progress and not working yet' ;
if($_SESSION["name"]) {
    if(isset($_POST["register"])) {  testingfunction($link, $_SESSION["id"]); }
} else header("Location:../ssLogout.php");

?>

<form method="post">
<br><div class = "border">   
<label for="option1"><b>Email:</b></label><br>

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



