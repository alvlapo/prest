<?php

namespace Prest\Mvc\Controllers;

class CollectionController extends FractalController
{
    /** @var \Prest\Api\ApiCollection */
    protected $_collection;

    /** @var \Prest\Api\ApiEndpoint */
    protected $_endpoint;

    /**
     * @return \Prest\Api\ApiCollection
     */
    public function getCollection()
    {
        if (!$this->_collection) {
            $this->_collection = $this->application->getMatchedCollection();
        }

        return $this->_collection;
    }

    /**
     * @return \Prest\Api\ApiEndpoint
     */
    public function getEndpoint()
    {
        if (!$this->_endpoint) {
            $this->_endpoint = $this->application->getMatchedEndpoint();
        }

        return $this->_endpoint;
    }
}
