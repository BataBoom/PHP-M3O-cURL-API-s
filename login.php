<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
use Curl\Curl;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = filter_input(INPUT_POST, 'email');
	$passwd = filter_input(INPUT_POST, 'password');
	
	$curl = new Curl();
	$curl->setBasicAuthentication('Authorization', $dbKey);
	$curl->setHeader('Content-Type', 'application/json');
	$curl->get('https://api.m3o.com/v1/user/Login', [
    'email' => $email,
    'password' => $passwd
]);
	if ($curl->error) {
    		$_SESSION['login_failure'] = "Invalid user name or password";
			header('Location:index.php');
			
} else {

   
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabLogin = json_decode($jsonEncoded, true);
    $encrypted_sesh = encrypt_decrypt('encrypt', $grabLogin['session']['id']);
    $_SESSION['logged_in'] = TRUE;
    $_SESSION['login_expires'] = $grabLogin['session']['expires'];
    $_SESSION['uid'] = $grabLogin['session']['userId'];
    $uid = $grabLogin['session']['userId'];
    $_SESSION['token'] = $encrypted_sesh;
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    fetchUser($uid);
    $expire = $_SESSION['login_expires'];
    $arr_cookie_options = array (
//    			'value' => $encrypted_sesh,
                'expires' => $expire,
                'path' => '/',
                'domain' => '.you', 
                'secure' => true,     
                'httponly' => false,  
                'SameSite' => 'strict'
                );
setcookie('encrypted_sesh', $encrypted_sesh, $arr_cookie_options); 
setcookie('user', $uid, $arr_cookie_options); 

    $curl->close();
    //Authentication successfull redirect user
            header('Location:../index.php');
}
	
		
	}

?>
