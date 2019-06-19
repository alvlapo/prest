<?php

namespace Prest\Middleware;

use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Prest\Mvc\Plugin;

class OptionsResponseMiddleware extends Plugin implements MiddlewareInterface
{
    public function beforeHandleRoute()
    {
        // OPTIONS request, just send the headers and respond OK
        if ($this->request->isOptions()) {

            $this->response->setJsonContent([
                'result' => 'OK',
            ]);

            $this->response->send();

            return false;
        }
    }

    public function call(Micro $api)
    {
        return true;
    }
}