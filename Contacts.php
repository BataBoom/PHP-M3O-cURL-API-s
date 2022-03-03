<?php
/* Integrate Contacts using M3O, Jeez I love them 

* FREE => https://m3o.com/contact 

*/

require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

/* Create Contact */
$contactKey = "Your-Key";
$readContact = array('birthday'=>"07-09-1969",'name'=>"SixtyNine", 'note' =>"Cool Guy");
$LinkContact = array('label'=>"OSB",'url'=>"opensports.bet");
$socialContact = array('label'=>"gab",'url'=>"@OSB", 'label'=>'Covers','url'=>"https://covers.com/");
$phoneContact = array('home'=>"+18087206969",'work'=>"+1123456789");
$addressContact = array('label'=>"trapHouse",'location'=>"123 Stoney Drive");
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $contactKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/contact/Create', [
    'birthday'=> $someContact["birthday"],
    'name' => $someContact["name"],
    'note' => $someContact["note"]], 
    [
    'links'=> $LinkContact ],
    [
    'social_medias' => $socialContact
    ],
    [
    'phones' => $phoneContact ],
    [
    'addresses'	=> $addressContact ]
);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {

        var_dump($curl->response);
        $curl->close();
}

/* Read Contact */
$readContact = "a14627dc-9b19-11ec-aa8a-666592cb3940";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $contactKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/contact/Read', [
    'id'=> $readContact
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        var_dump($curl->response);
        $curl->close();
}

/* List Contacts */
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $contactKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/contact/List', [
    'limit'=> "30"
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {

        var_dump($curl->response);
        $curl->close();

}

/* Delete Contact */
$readContact = "a14627dc-9b19-11ec-aa8a-666592cb3940";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $contactKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/contact/Delete', [
    'id'=> $readContact
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {

        var_dump($curl->response);
        $curl->close();
}

/* Update Contact */
$updateContact = array('birthday'=>"07-09-1969",'name'=>"SixtyNine", 'note' =>"Cool Guy");
$updateLinkContact = array('label'=>"OSB",'url'=>"opensports.bet");
$updatesocialContact = array('label'=>"gab",'url'=>"@OSB", 'label'=>'Covers','url'=>"https://covers.com/");
$updatephoneContact = array('home'=>"+18087206969",'work'=>"+1123456789");
$updateaddressContact = array('label'=>"trapHouse",'location'=>"123 Stoney Drive");
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $contactKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/contact/Create', [
	'id' => "a14627dc-9b19-11ec-aa8a-666592cb3940", 
    'birthday'=> $updateContact["birthday"],
    'name' => $updateContact["name"],
    'note' => $updateContact["note"] ],
    [
    'links'=> $updateLinkContact ],
    [
    'social_medias' => $updatesocialContact
    ],
    [
    'phones' => $updatephoneContact ],
    [
    'addresses'	=> $updateaddressContact
   
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {

        var_dump($curl->response);
        $curl->close();
}
