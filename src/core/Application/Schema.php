<?php
/**
 * User: kenyeung
 * Date: 11/14/2022
 * Time: 6:04 PM
 */
namespace Core\Application;

use Core\Config\Schema\Group;
use Core\Config\Schema\Types;
use Core\Config\Schema\Value;

class Schema
{
    public static function get($basePath = ''){
        return new Group('core', [
            new Group('logger', [
                new Value('name', Types::STRING, 'default'),
                new Value('path', Types::STRING, $basePath.'/logs'),
            ]),
            New Group('database', [
                new Group('main', [
                    new Value('type', Types::STRING, 'mysql'),
                    new Value('host?', Types::STRING, 'localhost'),
                    new Value('port', Types::INTEGER, 3306),
                    new Value('database', Types::STRING, 'directus'),
                    new Value('username', Types::STRING, 'root'),
                    new Value('password', Types::STRING, 'toor'),
                    new Value('engine', Types::STRING, 'InnoDB'),
                    new Value('charset', Types::STRING, 'utf8mb4'),
                    new Value('unix_socket?', Types::STRING, ''),
                    new Value('driver_options?', Types::ARRAY, []),
                ])
            ]),
        ]);
    }
}