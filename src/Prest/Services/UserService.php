<?php

namespace Prest\Services;

use Prest\Constants\ErrorCodes;
use Prest\Exception;
use Prest\Mvc\Plugin;

class UserService extends Plugin
{
    /**
     * Returns details for the current user, e.g. a User model
     *
     * @return mixed
     * @throws Exception
     */
    public function getDetails()
    {
        $details = null;

        $session = $this->authManager->getSession();
        if ($session) {

            $identity = $session->getIdentity();
            $details = $this->getDetailsForIdentity($identity);
        }

        return $details;
    }

    /**
     * This method should return details for the provided identity. Override this method with your own implementation.
     *
     * @param mixed $identity Identity to get the details from
     *
     * @return mixed The details for the provided identity
     * @throws Exception
     */
    protected function getDetailsForIdentity($identity)
    {
        throw new Exception(ErrorCodes::GENERAL_NOT_IMPLEMENTED, null,
            'Unable to get details for identity, method getDetailsForIdentity in user service not implemented. ' .
            'Make a subclass of \Prest\Services\UserService with an implementation for this method, and register it in your DI.');
    }

    /**
     * Returns the identity for the current user, e.g. the user ID
     *
     * @return mixed
     */
    public function getIdentity()
    {
        $session = $this->authManager->getSession();
        if ($session) {
            return $session->getIdentity();
        }

        return null;
    }

    /**
     * This method should return the role for the current user
     *
     * @return string Name of the role for the current user
     * @throws Exception
     */
    public function getRole()
    {
        throw new Exception(ErrorCodes::GENERAL_NOT_IMPLEMENTED, null,
            'Unable to get role for identity, method getRole in user service not implemented. ' .
            'Make a subclass of \Prest\Services\UserService with an implementation for this method, and register it in your DI.');
    }
}

