<?php
/**
 * User: kenyeung
 * Date: 11/15/2022
 * Time: 11:32 AM
 */

namespace Core\Exception;


class CoreException extends \Exception
{

    public function getStatusCode() {
        return 500;
    }
}