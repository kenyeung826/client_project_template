<?php

namespace Core\Http;

class Request extends \Slim\Http\Request
{
    public function getOrigin()
    {
        return $this->getHeader('Origin') ?: $this->getServerParam('HTTP_ORIGIN');
    }

    public function getReferer()
    {
        return $this->getHeader('Referer') ?: $this->getServerParam('HTTP_REFERER');
    }

}