<?php
/*
* @Author: Jeffry Susanto
* @Date  : 14/12/21
*/
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\HttpCache\Cache;

require __DIR__ . '/../vendor/autoload.php';

//External URL JSON - Use the hackers news api to randomly pick an article about medicine
define("SERVICE_URL", "http://hn.algolia.com/api/v1/search?query=medicine&tags=story");
define("SPLASH_ACCESS_KEY", "CUVuiHGTg6_srEup4NaPqfZW-wr7yQvSrDX0FO-BAz4");
define("SPLASH_SECRET_KEY", "9WSLekG72VGHOd0BhY_3oH8uJm97AQk0z4tN0MAKYCo");
define("SPLASH_KEYWORD", "medicine");
define("SPLASH_PAGE", 1);
define("SPLASH_PER_PAGE", 20);
define("SPLASH_ORIENTATION", "landscape");

//Initialise Unsplash API
Unsplash\HttpClient::init([
    'applicationId' => SPLASH_ACCESS_KEY,
    'secret' => SPLASH_SECRET_KEY,
    //'callbackUrl' => 'https://your-application.com/oauth/callback',
    'callbackUrl' =>'http://13.239.14.130/',
    'utmSource' => 'Medibank API Challenge'
]);

/*****************************************************************/
//Add per spec: For the duration of 1 hour return the same JSON content
// Register service provider with the container

/*****************************************************************/

// Instantiate App
$app = AppFactory::create();

// Register the http cache middleware.
$app->add(new Cache('public', 3600));

// Create the cache provider.
$cacheProvider = new \Slim\HttpCache\CacheProvider();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add routes
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write('<h1><a href="/api/article">/api/article</a></h1>');
    return $response;
});

$app->get('/api/article', function (Request $request, Response $response, $args) use ($cacheProvider): Response {
    //Get Splash Image 
    $usplashApiResult = Unsplash\Search::photos(SPLASH_KEYWORD, SPLASH_PAGE, SPLASH_PER_PAGE, SPLASH_ORIENTATION);

    //Get JSON data
    $arrResponseResult = array();
    $data = file_get_contents(SERVICE_URL);
    $data = json_decode($data, true);
    $index = 0;
    //Filter on title, url, author
    foreach($data["hits"] as $index => $val){
        array_push($arrResponseResult, array("title" => $val["title"], "url" => $val["url"], 
                                             "author" => $val["author"], 
                                             "imageUrl" => $usplashApiResult[$index]["urls"]["full"]));
        $index++;
    }
    header_remove("Cache-Control");
    $response = $response->withHeader('Cache-Control', 'public, max-stale=3600')->withJson($arrResponseResult);
    $response = $cacheProvider->withEtag($response, 'medicache');
    return $response;
});

$app->run();