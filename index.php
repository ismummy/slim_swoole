<?php

use App\Controller\HomeController;
use App\Middleware\IPLogMiddleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pachico\SlimSwoole\BridgeManager;
use Slim\App;


require 'vendor/autoload.php';
require 'app/Config/settings.php';

$app = new App(['settings' => $config]);

$container = $app->getContainer();

$container['logger'] = function ($c) {
    $loggerParam = $c['settings']['logger'];
    $logger = new Logger($loggerParam['name']);
    $file_handler = new StreamHandler($loggerParam['path']);
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['database'],
        $db['username'], $db['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};


$app->get('/', HomeController::class . ':home');

$app->add(new IPLogMiddleware($container->get('logger')));

$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));

//$app->run();

$bridgeManager = new BridgeManager($app);

/**
 * We start the Swoole server
 */
$http = new swoole_http_server("0.0.0.0", 8081);

/**
 * We register the on "start" event
 */
$http->on("start", function (swoole_http_server $server) {
    echo sprintf('Swoole http server is started at http://%s:%s', $server->host, $server->port), PHP_EOL;
});

/**
 * We register the on "request event, which will use the BridgeManager to transform request, process it
 * as a Slim request and merge back the response
 *
 */
$http->on(
    "request",
    function (swoole_http_request $swooleRequest, swoole_http_response $swooleResponse) use ($bridgeManager) {
        $bridgeManager->process($swooleRequest, $swooleResponse)->end();
    }
);

$http->start();
