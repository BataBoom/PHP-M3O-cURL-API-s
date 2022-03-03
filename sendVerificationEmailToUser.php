<?php
require '/vendor/autoload.php';

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
	
