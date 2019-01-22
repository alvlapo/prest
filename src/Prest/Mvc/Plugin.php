<?php

namespace Prest\Mvc;

/**
 * Prest\Mvc\Plugin
 * This class allows to access services in the services container by just only accessing a public property
 * with the same name of a registered service
 *
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
 * @property \Prest\Data\Query\QueryParsers\UrlQueryParser $urlQueryParser
 * @property \Prest\Api $application
 * @property \Prest\Data\Query\QueryParsers\PhqlQueryParser $phqlQueryParser
 */

class Plugin extends \Phalcon\Mvc\User\Plugin
{

}
