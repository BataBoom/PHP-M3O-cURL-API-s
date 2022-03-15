<?php
//include requirements, keys, etc
require 'includes/config/config.php';
use Curl\Curl;
function QR($uid){
global $qr;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $qr);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/qr/Generate', [
    'size' => 300,
    'text' => "https://bataboom.bet/profile.php?uid=$uid"
    
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $fetchQR = json_decode($jsonEncoded, true);
    $grabQR = $fetchQR['qr'];
    $curl->close();
    //return $grabQR;
    $curl = new Curl();
    $curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
    $curl->download($grabQR, "tmp/qr/qr-$uid.png");
     //$curl->diagnose();
    $curl->close();
   
    }
}
