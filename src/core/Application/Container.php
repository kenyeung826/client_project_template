<?php

namespace Core\Application;

use Core\Collection\Collection;
use Psr\Container\ContainerInterface;

class Container extends \Pimple\Container implements ContainerInterface {

    /**
     * Default settings
     *
     * @var array
     */
    protected $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        'addContentLengthHeader' => true,
        'routerCacheFile' => false,
    ];

    public function __construct(array $config = []) {
        parent::__construct($config);
        $userSettings = isset($config['settings']) ? $config['settings'] : [];
        $this->registerDefaultServiceProviders($userSettings);
        $this->registerApplicationProviders();

    }

    public function registerDefaultServiceProviders(array $userSettings) {
        $defaultSettings = $this->defaultSettings;

        /**
         * This service MUST return an array or an
         * instance of \ArrayAccess.
         *
         * @return array|\ArrayAccess
         */
        $this['settings'] = function () use ($userSettings, $defaultSettings) {
            return new Collection(array_merge($defaultSettings, $userSettings));
        };
        $defaultProvider = new DefaultServiceProvider();
        $defaultProvider->register($this);
    }

    public function registerApplicationProviders() {
        $applicationServiceProviders = new ApplicationServiceProviders();
        $applicationServiceProviders->register($this);
    }

    public function has($offset)
    {
        return $this->offsetExists($offset);
    }

    public function get($offset) {
        if (!$this->offsetExists($offset)) {
            throw new \Exception(sprintf("The key %s is not defined.", $offset));
        }
        return $this->offsetGet($offset);
    }

    public function set($offset, $value) {
        $this->offsetSet($offset, $value);
    }

}