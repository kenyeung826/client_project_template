<?php

namespace Core\Application;

use Core\Config\Config;
use Core\Exception\CoreException;
use Slim\App;
use Symfony\Component\Dotenv\Dotenv;

class Application extends App {

    protected static $instance = null;

    protected $basePath;

    protected $env;
    /**
     * @var Dotenv
     */
    public static $dotEnv;

    public function __construct(array $config = []) {
        $basePath = $config['basePath'] ?: realpath(__DIR__.'/../../') ;
        static::$instance = $this;
        $this->setBasePath($basePath);
        $this->setEnv();
        $userSetting = $this->createConfig($config);
        $container = new Container($userSetting);
        parent::__construct($container);
    }

    public function setBasePath($basePath) {

        $this->basePath = $basePath;
    }

    public function setEnv() {
        $env = getenv('ENV', true) ?: 'dev';
        define('ENV', $env);
    }

    public function createConfig(array $appConfig = []){
        $configPath = $this->getBasePath().'/config';

        $appConfig['config'] = $appConfig['config'] ?? [];

        $appConfig['settings'] = $appConfig['settings'] ?? [];

        $this->loadConfiguration($configPath, $appConfig);

        $globalConfigPath = $configPath . "/env" ;

        $this->loadConfiguration($configPath, $appConfig);

        $envConfigPath = $globalConfigPath . '/' . ENV;

        $this->loadConfiguration($envConfigPath, $appConfig);

        $config = $appConfig['config'];
        return [
            'settings' => isset($appConfig['settings']) ? $appConfig['settings'] : [],
            'config' => function () use ($config) {
                return new Config($config);
            }
        ];
    }

    public function loadConfiguration($configPath, &$appConfig) {
        $config = &$appConfig['config'];
        $settings = &$appConfig['settings'];

        if (file_exists($configPath)) {
            $files = scandir($configPath);
            foreach ($files as $file) {
                $filePath = $configPath . "/" . $file;
                $pointer = &$config;
                if (strpos($file, "settings") !== false ) {
                    $pointer = &$settings;
                }
                if (is_file($filePath)) {
                    $ext = pathinfo($filePath, PATHINFO_EXTENSION);
                    switch ($ext) {
                        case "php":
                            $pointer = array_merge($pointer, require $filePath);
                            break;
                        case "json":
                            $encodedJson = file_get_contents($filePath);
                            $pointer = array_merge($pointer, json_decode($encodedJson, true));
                            break;
                    }
                }
            }
        }
    }

    public function getBasePath(){
        return $this->basePath;
    }

    public static function loadDotEnv($basePath) {
        if (empty(static::$dotEnv)) {
            static::$dotEnv = new Dotenv();
            static::$dotEnv->load($basePath."/.env");
        }
    }

    public static function defineBasePath($basePath) {
        define("BASE_PATH", $basePath);
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public static function getInstance() {
        if (static::$instance) {
            return static::$instance;
        } else {
            throw new CoreException("Not init Application");
        }

    }

}