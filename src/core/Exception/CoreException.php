<?php
/**
 * User: kenyeung
 * Date: 11/15/2022
 * Time: 11:32 AM
 */

namespace Core\Exception;


class CoreException extends \Exception
{
    const ERROR_CODE = 0;

    protected $attributes = [];

    public function getErrorCode()
    {
        return static::ERROR_CODE;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getStatusCode() {
        return 500;
    }
}