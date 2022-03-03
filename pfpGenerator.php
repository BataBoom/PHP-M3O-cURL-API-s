<?php
/* Create Profile Picture/Avatar using M3O, Jeez I love them 

* FREE => https://m3o.com/avatar 

*/

require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;
/* Generate PFP */
$pfpKey = "Your-Key";
$username = "BataBoom";
$format = array('jpeg','png','male','female');
$generatePFP = array('format'=>$format[1],'gender'=>$format[2],'upload'=>true,'username'=>$username);
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $pfpKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/avatar/Generate', [
    'format'=>$generatePFP["format"],
    'gender'=>$generatePFP["gender"],
    'upload'=>$generatePFP["upload"],
    'username'=>$generatePFP["username"]
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchPFP = $curl->response;
        $encodePFP = json_encode($fetchPFP, true);
        $fetchPFP = json_decode($encodePFP, true);
        $getPFP = array($fetchPFP["url"], $fetchPFP["base64"]);
        $curl->close();
        print_r($getPFP);
}


