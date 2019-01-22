<?php

namespace Prest\Transformers;

use Phalcon\Di;
use Prest\Exception;

/**
 * @property \Prest\Api $application
 * @property \Prest\Http\Request $request
 * @property \Prest\Http\Response $response
 * @property \Phalcon\Acl\AdapterInterface $acl
 * @property \Prest\Auth\Manager $authManager
 * @property \Prest\User\Service $userService
 * @property \Prest\Helpers\ErrorHelper $errorHelper
 * @property \Prest\Helpers\FormatHelper $formatHelper
 * @property \Prest\Auth\TokenParserInterface $tokenParser
 * @property \Prest\Data\Query $query
 * @property \Prest\QueryParsers\PhqlQueryParser $phqlQueryParser
 * @property \Prest\Data\Query\QueryParsers\UrlQueryParser $urlQueryParser
 *
 * @property \Phalcon\Mvc\Dispatcher|\Phalcon\Mvc\DispatcherInterface $dispatcher;
 * @property \Phalcon\Mvc\Router|\Phalcon\Mvc\RouterInterface $router
 * @property \Phalcon\Mvc\Url|\Phalcon\Mvc\UrlInterface $url
 * @property \Phalcon\Http\Response\Cookies|\Phalcon\Http\Response\CookiesInterface $cookies
 * @property \Phalcon\Filter|\Phalcon\FilterInterface $filter
 * @property \Phalcon\Flash\Direct $flash
 * @property \Phalcon\Flash\Session $flashSession
 * @property \Phalcon\Session\Adapter\Files|\Phalcon\Session\Adapter|\Phalcon\Session\AdapterInterface $session
 * @property \Phalcon\Events\Manager $eventsManager
 * @property \Phalcon\Db\AdapterInterface $db
 * @property \Phalcon\Security $security
 * @property \Phalcon\Crypt $crypt
 * @property \Phalcon\Tag $tag
 * @property \Phalcon\Escaper|\Phalcon\EscaperInterface $escaper
 * @property \Phalcon\Annotations\Adapter\Memory|\Phalcon\Annotations\Adapter $annotations
 * @property \Phalcon\Mvc\Model\Manager|\Phalcon\Mvc\Model\ManagerInterface $modelsManager
 * @property \Phalcon\Cache\BackendInterface $modelsCache
 * @property \Phalcon\Mvc\Model\MetaData\Memory|\Phalcon\Mvc\Model\MetadataInterface $modelsMetadata
 * @property \Phalcon\Mvc\Model\Transaction\Manager $transactionManager
 * @property \Phalcon\Assets\Manager $assets
 * @property \Phalcon\DI|\Phalcon\DiInterface $di
 * @property \Phalcon\Session\Bag $persistent
 * @property \Phalcon\Mvc\View|\Phalcon\Mvc\ViewInterface $view
 */
class Transformer extends \League\Fractal\TransformerAbstract
{
    /**
     * Dependency Injector
     *
     * @var \Phalcon\DiInterface
     */
    protected $_dependencyInjector;

    public function setDI(\Phalcon\DiInterface $dependencyInjector)
    {
        $this->_dependencyInjector = $dependencyInjector;
    }

    public function getDI()
    {
        $dependencyInjector = $this->_dependencyInjector;

        if (!$dependencyInjector) {
            $dependencyInjector = Di::getDefault();
        }

        return $dependencyInjector;
    }

    public function __get($propertyName)
    {
        $dependencyInjector = null;
        $service = null;
        $persistent = null;

        $dependencyInjector = $this->_dependencyInjector;

        if (!$dependencyInjector) {
            $dependencyInjector = Di::getDefault();
        }

        if (!$dependencyInjector) {
            throw new Exception("A dependency injection object is required to access the application services");
        }

        /**
         * Fallback to the PHP userland if the cache is not available
         */
        if ($dependencyInjector->has($propertyName)) {

            $service = $dependencyInjector->getShared($propertyName);
            $this->{$propertyName} = $service;

            return $service;
        }

        if ($propertyName == "di") {

            $this->{"di"} = $dependencyInjector;
            return $dependencyInjector;
        }

        /**
         * A notice is shown if the property is not defined and isn't a valid service
         */
        trigger_error("Access to undefined property " . $propertyName);
        return null;
    }


    /* Format helper shortcuts */

    public function int($value)
    {
        return $this->formatHelper->int($value);
    }

    public function float($value)
    {
        return $this->formatHelper->float($value);
    }

    public function double($value)
    {
        return $this->formatHelper->float($value);
    }

    public function bool($value)
    {
        return $this->formatHelper->bool($value);
    }

    public function date($value)
    {
        return $this->formatHelper->date($value);
    }
}
