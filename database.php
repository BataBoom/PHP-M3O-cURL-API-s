require __DIR__ . '/vendor/autoload.php';

use Curl\Curl;
/* Database Operations */
function listTables(){
    global $dbKey;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/ListTables');

    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $listTables = json_decode($jsonEncoded, true);
    print_r($listTables);
    $curl->close();
}



function readDB($sort, $query, $table, $orderBy){
global $dbKey;
//column name, search premise, table
$q = "'";
$order_id = $sort . " == " . $q . $query . $q;
$t = $q . $table . $q;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/Read', [
    'query' => "$order_id",
    'table' => $table,
    'orderBy'=>$orderBy,
    'limit'=>1000,
    'order'=>"desc"
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabLogin = json_decode($jsonEncoded, true);
    print_r($grabLogin['records']);
}
$curl->close();
}


function updateDB($table, $column, $data, $id){
global $dbKey;
global $n;
//table, column name, updated Data, data ID
$record = array($column=>$data,'id'=>$id);
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/Update', [
    'table' => $table,
    'record' => $record
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo "Success";
}
$curl->close();
}

function deleteDB($table, $id){
global $dbKey;
//table, data ID
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/Delete', [
    'id' => $id,
    'table' => $table
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo "Success";
}
$curl->close();
}

function countDB($table){
global $dbKey;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/Count', [
    'table' => $table
    
]);
  $fetchResponse = $curl->response;
  $jsonEncoded = json_encode($fetchResponse);
  $grabLogin = json_decode($jsonEncoded, true);
    print_r($grabLogin['count']);
$curl->close();
}

function renameTable($from, $to){
global $dbKey;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/RenameTable', [
    'from' => $from,
    'to'=> $to
    
]);
var_dump($curl->response);
$curl->close();
}

function trunkDB($table){
global $dbKey;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/Truncate', [
    'table' => $table
    
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo "Success";
}
$curl->close();
}

function dropDB($table){
global $dbKey;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $dbKey);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/db/DropTable', [
    'table' => $table
    
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo "Success";
}
$curl->close();
}
