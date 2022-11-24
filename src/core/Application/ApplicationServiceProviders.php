<?php
namespace Core\Application;



use Core\Hook\Emitter;
use Core\Hook\Payload;
use Core\Permission\Acl;
use Core\Util\ArrayUtil;
use Core\Util\ErrorUtil;
use Core\Util\LoggerUtil;
use Core\Database\Connection;

class ApplicationServiceProviders {
    
    public function register($container){
        $container['logger'] = $this->getLogger();
        $this->loadDB($container);
        $container['hook_emitter'] = $this->getEmitter();
    }

    /**
     * @return \Closure
     */
    public function getLogger() {
        $loggerFunc = function(Container $container){
            try {
                $config = $container->get('settings');

                return LoggerUtil::getLogger("default", $config);
            } catch (\Exception $e) {
                print_r($e->getTraceAsString());
            }

        };
        return $loggerFunc;
    }
    /**
     * @param Container $container
     * @return \Closure
     */
    public function loadDB($container) {
        $dbs = $container->get('settings')['database'];
        foreach ($dbs as $name => $dbConfig) {
            $container['db_'.$name] = $this->getDB($dbConfig);
        }
    }


    public function getDB($dbConfig){

        $dbFunc = function(Container $container) use ($dbConfig){
            $charset = ArrayUtil::get($dbConfig, 'charset', 'utf8mb4');
            $defaultConfig = [
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                \PDO::MYSQL_ATTR_INIT_COMMAND => sprintf('SET NAMES "%s"', $charset)
            ];

            $parameters = array_merge($defaultConfig, $dbConfig, [
                'driver' => 'pdo_mysql',
                'charset' => $charset,
            ]);

            try {
                $db = new Connection($parameters);
                $db->connect();
                return $db;
            }catch (\Exception $e ) {
                throw $e;
            }
        };
        return $dbFunc;

    }

    public function getEmitter() {
        return function (Container $container) {
            $emitter = new Emitter();
            $emitter->addAction('application.error', function($e) use ($container) {
                $logger = $container->get('logger');
                $logger->error(ErrorUtil::normalize($e));
            });

            $emitter->addFilter('response', function (Payload $payload) use ($container) {
                /** @var Acl $acl */
                $acl = $container->get('acl');
                if ($acl->isPublic() || !$acl->getUserId()) {
                    $payload->set('public', true);
                }
                return $payload;
            });
            return $emitter;
        };
    }

}