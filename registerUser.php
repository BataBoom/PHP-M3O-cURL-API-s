<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
use Curl\Curl;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = filter_input(INPUT_POST, 'email');
	$passwd = filter_input(INPUT_POST, 'password');
	$username = filter_input(INPUT_POST, 'username');

	$curl = new Curl();
	$curl->setBasicAuthentication('Authorization', $userAPItoken);
	$curl->setHeader('Content-Type', 'application/json');
	$curl->get('https://api.m3o.com/v1/user/Create', [
    'email' => $email,
    'password' => $passwd,
    'username' => $username,
    'id' => '', 
    'profile' => array('group'=>"zero", 'note'=>"N/A", 'site'=>"OSB")
    
]);
	if ($curl->error) {
    	 $_SESSION['login_failure'] = "Error!";
			 $_SESSION['logged_in'] = FALSE;
       $curl->close();
			 header('Location:register.php');
		  	exit();
			
} else {
  $_SESSION['valid_email'] = $email;
	$curl = new Curl();
	$curl->setBasicAuthentication('Authorization', $userAPItoken);
	$curl->setHeader('Content-Type', 'application/json');
	$curl->get('https://api.m3o.com/v1/user/SendVerificationEmail', [
    'email' => $email,
    'failureRedirectUrl' => 'https://you/login/failed.php',
    'fromName' => 'Open Sports Bet',
    'redirectUrl' => 'https://you/login',
    'subject' => 'Email verification',
    'textContent' => "Hi there,Please verify your email by clicking this link: $"."micro_verification_link"
]);

			//Registration successfull redirect user
			header("Location:https://you/login/iverify.php");

		}
	
