<?php
/* Check if an email is spam/trash using M3O, Jeez I love them 

* FREE => https://m3o.com/spam 

*/

require __DIR__ . 'vendor/autoload.php';

use Curl\Curl;

/* Check Email Quality */
$emailSpamKey = "Your-Key";
$from = "picks@bataboom.bet";
$to = "contact@opensports.bet";
$subject = "Welcome Aboard!";
$textBody = "Hi there,\n\nWelcome to BataBoomBet.\n\nThanks\BBB team";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $emailSpamKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/spam/Classify', [
    'from'=>$from,
    'subject'=>$subject,
    'text_body'=>$textBody,
    'to'=>$to

]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchScore = $curl->response;
        $encodeScore = json_encode($fetchScore, true);
        $fetchScore = json_decode($encodeScore, true);
        print_r($fetchScore);
        $curl->close();

}
