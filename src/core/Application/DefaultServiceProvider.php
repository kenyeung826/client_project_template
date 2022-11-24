<?php

namespace Core\Application;


use Core\Http\Request;
use Core\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\CallableResolver;
use Slim\Handlers\Error;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\PhpError;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Router;

class DefaultServiceProvider extends \Slim\DefaultServicesProvider
{
    /**
     * @param Container $container
     */
    public function register($container) {

        $container['environment'] = function () {
            return new Environment($_SERVER);
        };
        /**
         * @param Container $container
         *
         * @return ServerRequestInterface
         */
        $container['request'] = function ($container) {
            return  Request::createFromEnvironment($container->get('environment'));
        };

        /**
         * @param Container $container
         *
         * @return ResponseInterface
         */
        $container['response'] = function ($container) {
            $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
            $response = new Response(200, $headers);
            return $response->withProtocolVersion($container->get('settings')['httpVersion']);
        };


        parent::register($container);
    }

}