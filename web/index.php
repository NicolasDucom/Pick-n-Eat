<?php
use Symfony\Component\HttpFoundation\Request;
require_once __DIR__.'/../vendor/autoload.php';
require_once ('../vendor/banderon1/social-api/yelp/lib/OAuth.php');

class Application extends Silex\Application
{
    use Silex\Application\TwigTrait;
}

$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    // 'twig.path' => realpath("view"),
    'twig.path' => realpath("../view"),
));
$app['asset_path'] = $app->share(function () {
    return realpath("js");
});

$app->get('/', function() use ($app){
    return $app['twig']->render('index.html.twig');
})
->bind('home');

$app->get('/random', function() use ($app){
    return $app['twig']->render('random.html.twig');
})
->bind('random');

$app->get('/choose', function() use ($app){
    return $app['twig']->render('choose.html.twig');
})
->bind('choose');


$app->get('/randomrestaurant', function (Request $request) use ($app) {
    $longitude = $request->query->get('longitude');
    $latitude = $request->query->get('latitude');
    $consumer_key = "uky7EX89JrjhWMdf_kOAAw";
    $consumer_secret = "dKheSzFpdantR4eDVr0QUsmlTT8";
    $token = "liNJWPnFJdu74A5UzMzeWICRbejfQpWB";
    $token_secret = "uQefdhMJHbstMP9pl_Vbj5pLX0I";
    $unsigned_url = "http://api.yelp.com/v2/search?ll=$latitude,$longitude";
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
    $randomRestaurant = $response->businesses[rand(0,count($response->businesses)-1)];
    return json_encode($randomRestaurant);
})
->bind('getRandom');

$app->run();

