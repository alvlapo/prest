<?php

namespace Prest\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Prest\Mvc\Plugin;

class AuthenticationMiddleware extends Plugin implements MiddlewareInterface
{
    public function beforeExecuteRoute()
    {
        $token = $this->request->getToken();

        if ($token) {
            $this->authManager->authenticateToken($token);
        }
    }

    public function call(Micro $api)
    {
        return true;
    }
}