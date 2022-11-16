<?php
namespace Core\Application;



use Core\Util\ArrayUtil;
use Core\Util\LoggerUtil;
use Core\Database\Connection;

class ApplicationServiceProviders {
    
    public function register($container){
        $container['logger'] = $this->getLogger($container);
        $this->loadDB($container);
    }

    /**
     * @param Container $container
     * @return \Closure
     */
    public function getLogger($container) {
        $config = $container->get('config');
        $loggerFunc = function(Container $container) use ($config){
            return LoggerUtil::getLogger("default", $config);
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

}