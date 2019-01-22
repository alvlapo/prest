<?php

namespace Prest\Middleware;

use Phalcon\Events\Event;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\MiddlewareInterface;
use Prest\Mvc\Plugin;
use Prest\Api;
use Prest\Constants\ErrorCodes;
use Prest\Exception;

class AuthorizationMiddleware extends Plugin implements MiddlewareInterface
{
    public function beforeExecuteRoute(Event $event, Api $api)
    {
        $collection = $api->getMatchedCollection();
        $endpoint = $api->getMatchedEndpoint();

        if (!$collection || !$endpoint) {
            return;
        }

        $allowed = $this->acl->isAllowed($this->userService->getRole(), $collection->getIdentifier(),
            $endpoint->getIdentifier());

        if (!$allowed) {
            throw new Exception(ErrorCodes::ACCESS_DENIED);
        }
    }

    public function call(Micro $api)
    {
        return true;
    }
}