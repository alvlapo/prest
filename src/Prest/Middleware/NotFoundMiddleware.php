<?php

namespace Prest\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Prest\Constants\ErrorCodes;
use Prest\Exception;
use Prest\Mvc\Plugin;

class NotFoundMiddleware extends Plugin implements MiddlewareInterface
{
    public function beforeNotFound()
    {
        throw new Exception(ErrorCodes::GENERAL_NOT_FOUND);
    }

    public function call(Micro $api) {

        return true;
    }
}
