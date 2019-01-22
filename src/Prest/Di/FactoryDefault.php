<?php

namespace Prest\Di;

use Prest\Acl\Adapter\Memory as Acl;
use Prest\Auth\Manager as AuthManager;
use Prest\Auth\TokenParsers\JWTTokenParser;
use Prest\Constants\Services;
use Prest\Data\Query;
use Prest\Data\Query\QueryParsers\UrlQueryParser;
use Prest\Helpers\ErrorHelper;
use Prest\Helpers\FormatHelper;
use Prest\Http\Request;
use Prest\Http\Response;
use Prest\Services\UserService;
use Prest\Constants\ErrorCodes;
use Prest\QueryParsers\PhqlQueryParser;
use Prest\Exception;

class FactoryDefault extends \Phalcon\Di\FactoryDefault
{
    public function __construct()
    {
        parent::__construct();

        $this->setShared(Services::REQUEST, new Request);

        $this->setShared(Services::RESPONSE, new Response);

        $this->setShared(Services::AUTH_MANAGER, new AuthManager);

        $this->setShared(Services::USER_SERVICE, new UserService);

        $this->setShared(Services::TOKEN_PARSER, function () {
            
            return new JWTTokenParser('this_should_be_changed');
        });

        $this->setShared(Services::QUERY, new Query);

        $this->setShared(Services::URL_QUERY_PARSER, new UrlQueryParser);

        $this->setShared(Services::ACL, new Acl);

        $this->setShared(Services::ERROR_HELPER, new ErrorHelper);

        $this->setShared(Services::FORMAT_HELPER, new FormatHelper);

        $this->setShared(Services::FRACTAL_MANAGER, function () {

            $className = '\League\Fractal\Manager';

            if (!class_exists($className)) {
                throw new Exception(ErrorCodes::GENERAL_SYSTEM, null,
                    '\League\Fractal\Manager was requested, but class could not be found');
            }

            return new $className();
        });

        $this->setShared(Services::PHQL_QUERY_PARSER, new PhqlQueryParser);
    }
}
