<?php
/* Create Memes using M3O, Jeez I love them 

* FREE => https://m3o.com/memegen 

*/

require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;
/* MEMErator */
$memeKey = "Your-Key";
$generateMeme = array('bottom_text'=>"Huh?",'id'=>"444501",'top_text'=>"WTF");
$font = array('arial','impact','20px');
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $memeKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/memegen/Generate', [
    'bottom_text'=>$generateMeme["bottom_text"],
    'font'=>$font[0],
    'id'=>$generateMeme["id"],
    'max_font_size'=>$font[2],
    'top_text'=>$generateMeme["top_text"]
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchMeme = $curl->response;
        $encodeMeme = json_encode($fetchMeme, true);
        $fetchMeme = json_decode($encodeMeme, true);
        print_r($fetchMeme["url"]);
        $curl->close();
}


/* List Meme Template IDz */
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $memeKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/memegen/Templates');
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchMeme = $curl->response;
        $encodeMeme = json_encode($fetchMeme, true);
        $fetchMeme = json_decode($encodeMeme, true);
        print_r($fetchMeme["templates"]);
        $curl->close();
}
