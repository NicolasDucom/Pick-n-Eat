<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once ('../vendor/banderon1/social-api/yelp/lib/OAuth.php');

$app = new Silex\Application();

$app->get('/hello', function() {
    return 'Hello!';
});

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello ' . $app->escape($name);
});

$app->get('/hello2', function () use ($app) {
    
    // Enter the path that the oauth library is in relation to the php file
    // For example, request business with id 'the-waterboy-sacramento'
    //$unsigned_url = "http://api.yelp.com/v2/business/the-waterboy-sacramento";
    $unsigned_url = "http://api.yelp.com/v2/search?ll=50.6321870,3.0472770";
    // For examaple, search for 'tacos' in 'sf'
    //$unsigned_url = "http://api.yelp.com/v2/search?term=tacos&location=sf";
    // Set your keys here
    $consumer_key = "uky7EX89JrjhWMdf_kOAAw";
    $consumer_secret = "dKheSzFpdantR4eDVr0QUsmlTT8";
    $token = "liNJWPnFJdu74A5UzMzeWICRbejfQpWB";
    $token_secret = "uQefdhMJHbstMP9pl_Vbj5pLX0I";
    // Token object built using the OAuth library
    $token = new OAuthToken($token, $token_secret);
    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
    // Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
    $oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    // Send Yelp API Call
    $ch = curl_init($signed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch); // Yelp response
    curl_close($ch);
    // Handle Yelp response data
    $response = json_decode($data);
    // Print it for debugging

    //return $response;
    print_r(var_dump($response));
    return "true";
});

$app->run();

