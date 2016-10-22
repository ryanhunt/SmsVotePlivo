#!/usr/local/bin/php

<?php 

require 'vendor/autoload.php';
include 'www/plivoauth.php';
include 'telstraauth.php';
use Plivo\RestAPI;





$token = getTelstraToken($telstra_ConsumerKey, $telstra_ConsumerSecret);

$response = sendTelstraSMS($token, $telstra_testNumber, "Hello world.");

print_r($response);

// Plivo code


/*

$p = new RestAPI($plivo_auth_id, $plivo_auth_token);

$params = array(
    'src' => $plivo_testNumber,
    'dst' => $plivo_testNumber,
    'text' => 'Hello, how are you?'
);
$response = $p->send_message($params);

*/

function getTelstraToken ($id, $secret) {

	/*
	Returns: 
	Array
	(
	    [access_token] => d1GBOeQpPQYyhF2AxhhKB5CGPAjf
	    [expires_in] => 3599
	)
	*/

	$url = "https://api.telstra.com/v1/oauth/token";

	$headers = [
	    'Content-Type: application/x-www-form-urlencoded',
	    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0'
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=" . $id . "&client_secret=" . $secret . "&grant_type=client_credentials&scope=SMS");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$token = json_decode(curl_exec ($ch), true);
	curl_close($ch);
	
	return $token['access_token'];
	
	
}

function sendTelstraSMS($token, $recipient, $message) {

	/*
	Returns:
	
	Array
	(
	    [messageId] => 2204DB8CB23CBA57C706A4C29FCB7E1F
	)
	
	*/

	$url = "https://api.telstra.com/v1/sms/messages";
	
	$headers = [
	    'Content-Type: application/json',
	    'Authorization: Bearer ' . $token,
	    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0'
	];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"to\":\"" . $recipient . "\", \"body\":\"" . $message . "\"}");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$response = json_decode(curl_exec ($ch), true);
	curl_close($ch);
	
	return $response['messageId'];
}


 ?>
