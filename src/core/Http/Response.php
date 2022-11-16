<?php
namespace Core\Http;

class Response extends \Slim\Http\Response
{
    const HTTP_NOT_CONTENT          = 204;
    const HTTP_NOT_FOUND            = 404;
    const HTTP_METHOD_NOT_ALLOWED   = 405;

    const HTTP_SERVICE_UNAVAILABLE  = 503;

    public function withHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->headers->set($name, $value);
        }

        return $this;
    }

    public function withScimJson($data, $status = null, $encodingOptions = 0)
    {
        $response = $this->withJson($data, $status, $encodingOptions);

        return $response->withHeader('Content-Type', 'application/scim+json;charset=utf-8');
    }

}