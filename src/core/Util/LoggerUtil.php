<?php
/**
 * User: kenyeung
 * Date: 11/14/2022
 * Time: 11:03 AM
 */

namespace Core\Util;

use Core\Application\Application;
use Core\Config\Config;
use Core\Exception\CoreException;
use Core\Util\FileUtil;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;

class LoggerUtil
{

    private static $logger = [];

    /**
     * @param $loggerName
     * @param Config $config
     * @return mixed
     */
    public static function getLogger($loggerName, Config $config = null) {
        if (!array_key_exists($loggerName, static::$logger)) {
            static::$logger[$loggerName] = static::createLogger($loggerName, $config);
        }
        return static::$logger[$loggerName];
    }



    public static function createLogger($loggerName, Config $config = null) {
        $basePath = Application::getInstance()->getBasePath();
        $logger = new Logger($loggerName);
        $logger->pushProcessor(new UidProcessor(7));
        $logger->pushProcessor(new IntrospectionProcessor());
        $logger->pushProcessor(new PsrLogMessageProcessor());
        $logger->pushProcessor(new WebProcessor());

        $output = "%datetime% [uid:%extra.uid%] [ip:%extra.ip%] [url:%extra.url%] [method:%extra.http_method%] %channel%.%level_name%: - %extra.class%::%extra.function%:%extra.line% - %message% %context%\n";
        $formatter = new LineFormatter($output);
        $formatter->allowInlineLineBreaks();
        $formatter->includeStacktraces();
        $path = null;
        if ($config && $config->has('logger.path')) {
            $path = $config->get('logger.path');
        } else {
            $path = $basePath;
        }
        $name = "default";
        if ($config && $config->has('logger.name')) {
            $name = $config->get('logger.name');
        }

        $path  = FileUtil::mkdir($path);

        if (substr($path, -1) == '/') {
            $path = substr($path, 0, strlen($path) - 1);
        }

        $filenameFormat = '%s.%s.log';

        $loggerPath = $path . '/' . sprintf($filenameFormat, strtolower($name), date('Y-m-d'));
        $handler = new StreamHandler(
            $loggerPath,
            Logger::DEBUG,
            true
        );
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        return $logger;
    }

}