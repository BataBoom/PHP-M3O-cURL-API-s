/* Registration Functions */
function create_userID(){
    $bytes = random_bytes(6);
    return bin2hex($bytes);
}
/* Auth & Login Functions */
function encrypt_decrypt($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    $secret_key = 'naPvJ_5}85M=@)t!';
    $secret_iv = 'eYD;2[(2n[GcVX-_';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a w>
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
    }

function fetchUser($uid) {
    global $userAPItoken;
$curl = new Curl();
    $curl->setBasicAuthentication('Authorization', $userAPItoken);
    $curl->setHeader('Content-Type', 'application/json');
    $curl->get('https://api.m3o.com/v1/user/Read', [
    'id' => $uid

]);
    if ($curl->error) {
     //echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
     LogMeOut();
    
} else {

    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabSesh = json_decode($jsonEncoded, true);
    $_SESSION['email'] = $grabSesh['account']['email'];
    $_SESSION['username'] = $grabSesh['account']['username'];
    $_SESSION['created'] = $grabSesh['account']['created'];
    $_SESSION['updated'] = $grabSesh['account']['updated'];
    $_SESSION['verified'] = $grabSesh['account']['verified'];
    return $grabSesh['account'];
    }
    $curl->close();
}

function getIP() {
        $result = null;

        //for proxy servers
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $result = end(array_filter(array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))));
        }
        else {
            $result = $_SERVER['REMOTE_ADDR'];
        }

        return $result;
    }


/* Log out */

function LogMeOut(){
// Unset all of the session variables.
$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session.
session_destroy();
header('Location:../index.php');
}


function killSesh(){
    //global $decrypted_sesh;
    global $userAPItoken;
    $decrypted_sesh = encrypt_decrypt('decrypt', $_SESSION['token']);
    $curl = new Curl();
    $curl->setBasicAuthentication('Authorization', $userAPItoken);
    $curl->setHeader('Content-Type', 'application/json');
    $curl->get('https://api.m3o.com/v1/user/Logout', [
    'sessionId' => $decrypted_sesh
]);
   
    $curl->close();
}

function checkSession() {
    global $userAPItoken;
    //global $decrypted_sesh;
    $decrypted_sesh = encrypt_decrypt('decrypt', $_SESSION['token']);
    $curl = new Curl();
    $curl->setBasicAuthentication('Authorization', $userAPItoken);
    $curl->setHeader('Content-Type', 'application/json');
    $curl->get('https://api.m3o.com/v1/user/ReadSession', [
    'sessionId' => $decrypted_sesh
]);
    if ($curl->error) {
     //echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
     LogMeOut();
    
} else {

    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabSesh = json_decode($jsonEncoded, true);
    $curl->close();
}

if ($grabSesh['session']['id'] === $decrypted_sesh){
$_SESSION['valid'] = TRUE;
} else {
LogMeOut();
}
$curl->close();
}

function changeEmail($uid, $newEmail){
global $userAPItoken;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $userAPItoken);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/user/Update', [
    'email' => "$newEmail",
    'id' => $uid
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo "Success";
}
$curl->close();
}

function listUsers(){
global $userAPItoken;
global $results;
$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $userAPItoken);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/user/List', [
    'limit'=>100,
    'offset'=>0
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabLogin = json_decode($jsonEncoded, true);
    $results = array();
    $results = $grabLogin['users'];
}
$curl->close();
return $results;
}

function fetchAUser($uid) {
    global $userAPItoken;
$curl = new Curl();
    $curl->setBasicAuthentication('Authorization', $userAPItoken);
    $curl->setHeader('Content-Type', 'application/json');
    $curl->get('https://api.m3o.com/v1/user/Read', [
    'id' => $uid

]);
    if ($curl->error) {
     //echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
     LogMeOut();
    
} else {

    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabSesh = json_decode($jsonEncoded, true);
    $results = $grabSesh;
    return $results;
    
    }
    $curl->close();
}
