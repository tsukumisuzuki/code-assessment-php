<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'mysql';
$config['db']['user']   = 'project';
$config['db']['pass']   = 'project';
$config['db']['dbname'] = 'project';



$app = new \Slim\App(['settings' => $config]);
$container = $app->getContainer();
$container['pdo'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
/** @var \PDO $pdo */
$pdo = $container->get('pdo');
$pdo->prepare("SHOW GLOBAL VARIABLES LIKE '%innodb_log%'");
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};


// auth routes
$app->group('/', function () {
    $this->get('', \Demo\Controller\ExampleController::class . ':getDefault')->setName('get-default');
});

$app->run();

