<?php
/* Create and Manage Lists using M3O, Jeez I love them 

* FREE => https://m3o.com/lists 

*/

require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;

/* Create a List */
$listKey = "Your-Key";
$list = array('1) Ghost Train Haze', '2) Golden Goat', '3) Lee Roy');
//$list = "my first list";
$title = "Favorite Strains";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $listKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/lists/Create', [
    'items'=>$list 
	],
	[
    'name'=>$title
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchList = $curl->response;
        $encodeList = json_encode($fetchList, true);
        $fetchList = json_decode($encodeList, true);
        $getList = array($fetchList["list"]);
        $curl->close();
        print_r($getList);
}

/* Subscribe to a List */
$id = "d217f969-9b23-11ec-aa45-865fc3b50c8e";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $listKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/lists/Events', [
    'id'=>$id 
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchList = $curl->response;
        $encodeList = json_encode($fetchList, true);
        $fetchList = json_decode($encodeList, true);
        $getList = array($fetchList["event"], $fetchList["list"]);
        $curl->close();
        print_r($getList);
}


/* List all Lists */
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $listKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/lists/List');
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchLists = $curl->response;
        $encodeLists = json_encode($fetchLists, true);
        $fetchLists = json_decode($encodeLists, true);
        print_r($fetchLists);
        $curl->close();
}


/* Read a List */
$id = "d217f969-9b23-11ec-aa45-865fc3b50c8e";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $listKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/lists/Read', [
    'id'=>$id 
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchList = $curl->response;
        $encodeList = json_encode($fetchList, true);
        $fetchList = json_decode($encodeList, true);
        print_r($fetchList);
        $curl->close();
       
}

/* Update a List */
$id = "d217f969-9b23-11ec-aa45-865fc3b50c8e";
$updateList = array('new text', 'new title');
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $listKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/lists/Update', [
    'id'=>$id,
    'text'=>$updateList[0],
    'title'=> $updateList[1]
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchUpdatedList = $curl->response;
        $encodeUpdatedList = json_encode($fetchUpdatedList, true);
        $fetchUpdatedList = json_decode($encodeUpdatedList, true);
        print_r($fetchUpdatedList);
        $curl->close();
       
}

/* Delete a List */
$id = "d217f969-9b23-11ec-aa45-865fc3b50c8e";
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $listKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->post('https://api.m3o.com/v1/lists/Delete', [
    'id'=>$id 
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
        $fetchList = $curl->response;
        $encodeList = json_encode($fetchList, true);
        $fetchList = json_decode($encodeList, true);
        print_r($fetchList);
        $curl->close();
       
}
