<?php


use \Core\Application\Application;
use \Core\Database\Connection;

$app = Application::getInstance();
$app->get('/', function(\Core\Http\Request $request, \Core\Http\Response $response) use ($app){
    //$response->getBody()->write('testung+');

    $container = $app->getContainer();
    /**
     * @var \Monolog\Logger $logger
     */
    $logger = $container->get('logger');
    $logger->info("test");

    /**
     * @var Connection $db
     */
    /**
     * @var Connection $db
     */
    $db = $container->get('db_main');

    $sql = "SELECT * FROM category where id = :id";
    $result = $db->query($sql, ['id' => 1]);
    $responseWithJson = $response->withJson($result->toArray());
    return $responseWithJson;
});
