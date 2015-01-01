<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once ('../vendor/banderon1/social-api/yelp/lib/OAuth.php');

class Application extends Silex\Application
{
    use Silex\Application\TwigTrait;
}

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    // 'twig.path' => realpath("view"),
    'twig.path' => realpath("../view"),
));

$app->get('/', function() use ($app){
    return $app['twig']->render('index.html');
});

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello ' . $app->escape($name);
});

$app->get('/restaurants/{latitude}/{longitude}', function ($latitude,$longitude) use ($app) {
    
    $unsigned_url = "http://api.yelp.com/v2/search?ll=$latitude,$longitude";

    $consumer_key = "uky7EX89JrjhWMdf_kOAAw";
    $consumer_secret = "dKheSzFpdantR4eDVr0QUsmlTT8";
    $token = "liNJWPnFJdu74A5UzMzeWICRbejfQpWB";
    $token_secret = "uQefdhMJHbstMP9pl_Vbj5pLX0I";

    $token = new OAuthToken($token, $token_secret);

    $consumer = new OAuthConsumer($consumer_key, $consumer_secret);

    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
    
    $oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $unsigned_url);

    $oauthrequest->sign_request($signature_method, $consumer, $token);

    $signed_url = $oauthrequest->to_url();

    $ch = curl_init($signed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch); 
    curl_close($ch);

    $response = json_decode($data);

    print_r(var_dump($response));
    return "true";
});

$app->run();

