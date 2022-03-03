<?php
/* Integrate Comments using M3O, Jeez I love them 

=> https://m3o.com/comments/api

*/


require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;
$commentKey = "Your-Key";


$createComment = array('subject'=>'we love testing','text'=>'yah we do fam');
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $commentKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/comments/Create', [
    'text'=> $createComment["subject"],
    'title' => $createComment["text"],

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


$deleteComment = "4bf49c88-9b0b-11ec-8cb7-163829b934db";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $commentKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/comments/Create', [
    'id'=> $deleteComment,

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



//List alll comments
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $commentKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/comments/List');
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabLogin = json_decode($jsonEncoded, true);
        var_dump($curl->response);

        $curl->close();
}

//read a single Comment
$readComment = "f819796d-9b0c-11ec-8cb7-163829b934db";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $commentKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/comments/Read', [
    'id'=> $readComment,

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

//Update a Comment
$updateComment = array('id' => $readComment, 'subject' => "Update Comment", 'text' => "nah fam am jkk");
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $commentKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/comments/Update', [
    'comment' => $updateComment,

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


$readComment = "f819796d-9b0c-11ec-8cb7-163829b934db";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $commentKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/comments/Read', [
    'id' => $readComment,

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
