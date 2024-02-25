<?php
// Use sdk for API configuration and POST rest calls.
require("services/CustumApi.php");

// Recovery of an instance of the main class of the sdk: API + configuration of the data of the developer.
//$custumAPI = CustumAPI::getInstance(app_key, app_secret,app_id)
$custumApi = CustumAPI::getInstance( "b6yxpFI8fZkrlCGDR5hud8KbZNOdDe0s","Docv0Qaur8rDovGXD5wRq3yZ1PyHoDMI","cb0acab2-2c77-46f7-8948-7644279f4140");

// Developer authentication and recovery of the access token or update of it.
$acessTk = $custumApi->oauthAuthenticate();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $phoneNumber = $_POST["phoneNumber"];
    $message = $_POST["message"];

    // Display the submitted data
    echo "PhoneNumber: " . $phoneNumber . "<br>";
    echo "Message: " . $message . "<br>";
}

// Balance request 
$resBalance = $custumApi->requestGetSmsBalance();
echo "\n \n \n";
var_dump($resBalance);

// Send sms request to a list of mobile phone.
$resPayment = $custumApi->requestSimpleSms([$phoneNumber], $message);
echo "\n \n \n";
var_dump($resPayment);
?>
