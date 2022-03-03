<?php
/* Integrate SMS using M3O, Jeez I love them 

* $0.075/each => https://m3o.com/sms

*/


require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;
$smsKey = "Your-Key";


$sendSMS = array('from'=>"Think Green",'Message'=>"$100 ounces of HashBunny now available, while supplies last!", 'to' =>"+16666666666");
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $sendSMS);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/sms/Send', [
    'from'=> $sendSMS["from"],
    'message' => $sendSMS["Message"],
    'to' => $sendSMS["to"]

]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabLogin = json_decode($jsonEncoded, true);
        var_dump($curl->response);

        $curl->close();
}
