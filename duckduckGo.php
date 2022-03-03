<?php
/* Query DuckDuckGo using M3O, Jeez I love them 

* FREE => https://m3o.com/answer 

*/

require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

/* Query the world wide search */
$duckduckGoKey = "Your-Key";
$question = "how many world series championships do the Dodgers have?";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $duckduckGoKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/answer/Question', [
    'query'=>$question
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
		$fetchResponse = $curl->response;
    	$getAnswer = json_encode($fetchResponse, true);
    	$fetchAnswer = json_decode($getAnswer, true);
    	$yourAnswer = array('answer'=>$fetchAnswer["answer"], 'url'=>$fetchAnswer["url"],'image'=>$fetchAnswer["image"]);
        print_r($yourAnswer);
    	$curl->close();
}

