<?php


use \Core\Application\Application;
use \Core\Database\Connection;

$app = Application::getInstance();
$app->get('/', function(\Core\Http\Request $request, \Core\Http\Response $response) use ($app){
    //$response->getBody()->write('testung+');

});

$app->group('/home', \App\Controller\HomeController::class);

