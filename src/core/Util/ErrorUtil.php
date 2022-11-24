<?php
/**
 * User: kenyeung
 * Date: 11/21/2022
 * Time: 6:44 PM
 */

namespace Core\Util;


class ErrorUtil
{
    public static function normalize($e) {
        if (!($e instanceof \Exception) && !($e instanceof \Throwable)) {
            return '';
        }

        $stack = [
            sprintf("%s: %s in %s:%d\nStack trace:", get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()),
        ];

        // format: stack index - filename - line - call
        $format = '#%d %s%s: %s';
        foreach ($e->getTrace() as $i => $trace) {
            $file = isset($trace['file']) ? $trace['file'] : '[internal function]';
            $line = isset($trace['line']) ? sprintf('(%s)', $trace['line']) : '';

            if (isset($trace['class'])) {
                $call = $trace['class'].$trace['type'].$trace['function'];
            } else {
                $call = $trace['function'];
            }

            $stack[] = sprintf(
                $format,
                $i,
                $file,
                $line,
                $call
            );
        }

        return implode("\n", $stack);
    }
}