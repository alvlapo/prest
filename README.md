# Phalcon REST API Library

A library focused on simplifying the creation of RESTful API's.

## About this repo

This is a separately developed **fork** which is independent from the [redound's Phalcon REST](https://github.com/redound/phalcon-rest) project.

# Todo list

* Add annotation validation for model
* Add nested resources

# Requirements

* PHP 7.1 or newer
* Phalcon 3.0 or newer
* SQL database (optional)

# Concepts

## Config files

The Phalcon REST application needs a default configuration file app/configs/default.php and a environment based config file like app/configs/server.develop.php.

### Default template

Default template app/configs/default.template.php for all your standard configuration. Rename this file to default.php and adjust it to your needs.

**default.template.php**

```php
return [
    'application' => [
        'title' => 'Phalcon REST Application',
        'description' => 'This repository provides an boilerplate application with all of the classes of Phalcon REST library implemented.',
        'baseUri' => '/',
        'viewsDir' => __DIR__ . '/../views/',
    ],
    'authentication' => [
        'secret' => 'this_should_be_changed',
        'expirationTime' => 86400 * 7, // One week till token expires
    ]
];
```

### Server template

Server template app/configs/server.template.php for all your environment specific configuration. Copy this file to server.develop.php and adjust it to your needs.

**server.development.php**

```php
return [

    'debug' => true,
    'hostName' => 'http://example.com',
    'clientHostName' => 'http://example.com',
    'database' => [

        // Change to your own configuration
        'adapter' => 'Mysql',
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'dbname' => 'phalcon_rest_app',
    ],
    'cors' => [
        'allowedOrigins' => ['*']
    ]
];
```

## Environment variable

The application will either load server.develop.php, server.staging.php or server.production.php based on their respective APPLICATION_ENV values (develop, staging, production). If no environment variable has been set it defaults to develop.

### apache configuration

```text
<VirtualHost *:80>
    SetEnv APPLICATION_ENV "development"
</VirtualHost>
```

## Collections

### Introduction

Collections are standard Phalcon Collections enriched with Access Control and advanced Endpoint Configuration. Collections can be used to register default endpoints, for more complex implementations look at Resources.

```php
use Prest\Api;
use Prest\Collection;
use Prest\Endpoint;

$api = new Api;

$api->collection(Collection::factory('/export')
    ->name('Export')
    ->handler(ExportController::class)
    ->allow(\UserRoles::USER)
    ->endpoint(Endpoint::get('/documentation.json', 'documentation'))
    ->endpoint(Endpoint::get('/postman.json', 'postman'));
);
```

### Extend

Extend from ```ExportCollection``` from ```Prest\Api\Collection```

```php
use Prest\Api\Collection;

class ExportCollection extends Collection
{
    protected function initialize()
    {
        $this
            ->name('Export')
            ->handler(ExportController::class)
            ->allow(\UserRoles::USER)
            ->endpoint(Endpoint::get('/documentation.json', 'documentation'))
            ->endpoint(Endpoint::get('/postman.json', 'postman'));
    }
}
```

Register collection on $api

```php
$api = new \Prest\Api;

$api->collection(new ExportCollection('/export'));
```

### Method Listing

* name()
* getName()
* description()
* getDescription()
* getEndpoints()
* getEndpoint()
* postedDataMethod()
* getPostedDataMethod()
* expectsPostData()
* expectsJsonData()
* allow()
* deny()
* getAllowedRoles()
* getAclRoles()

#### name()

Name will be used as a quick description for documenting this collection. See more on Documentation Generation.

#### getName()

Returns the name that has been set.

#### description()

Description will be used as a detailed description for documenting this collection.
getDescription()

Returns the description that has been set.

#### getEndpoints()

Returns all mounted endpoints on this collection.

#### getEndpoint()

Returns endpoints by it's name if does exists.

#### postedDataMethod()

Set the postedDataMethod, defaults to PostedDataMethods::AUTO

#### getPostedDataMethod()

Returns the postedDataMethod.

#### expectsPostData()

Sets the posted data method to PostedDataMethods::POST

#### expectsJsonData()

Sets the posted data method to PostedDataMethods::JSON_BODY

#### allow()

Allow one or multiple roles for this collection.

#### deny()

Deny one or multiple roles for this collection.

#### getAllowedRoles()

Returns the roles that have been allowed.

#### getDeniedRoles()

Returns the roles that have been denied.

#### getAclRules()

Return build aclRules based on the roles that have been allowed and denied by its respective methods allow() and deny().

## Endpoints

### Introduction

Endpoints represent your path relative to the Collection or Resource it's attached to. Allowed roles applied to it's Collection or Resource as well as the expected POST data format can be overriden.

```php
use Prest\Api;
use Prest\Api\Collection;
use Prest\Api\Endpoint;

$api = new \Prest\Api;

$api->collection(Collection::factory('auth')
  ->handler(AuthController::class)
  ->allow(\UserRoles::UNAUTHORIZED)
  ->endpoint(Endpoint::post('/login', 'login'))
);
```

> Provides you with a POST endpoint with /auth/login as path. The login method on AuthController will be used to handle this endpoint.

### Method Listing

* handlerMethod()
* name()
* getName()
* description()
* getDescription()
* getHttpMethod()
* exampleResponse()
* getExampleResponse()
* getPath()
* postedDataMethod()
* getPostedDataMethod()
* expectsPostData()
* expectsJsonData()
* allow()
* deny()
* getAllowedRoles()
* getDeniedRoles()

#### handlerMethod()

Name of controller-method to be called for the endpoint

#### name()

Set name for the endpoint (will be used to generate documentation)

#### getName()

Returns name for the endpoint

#### description()

Set description for the endpoint (will be used to generate documentation)

#### getDescription()

Returns description for the endpoint

#### getHttpMethod()

Returns the HTTP method for the endpoint

#### exampleResponse()

Set a example response that will be rendered in the documentation

#### getExampleResponse()

Returns the example response

#### getPath()

Returns the path of the endpoint, relative to the collection/resource

#### postedDataMethod()

Set one of the method constants defined in Prest\Constants\PostedDataMethods

#### getPostedDataMethod()

Returns the PostedDataMethod being used

#### expectsPostData()

Will set the PostedDataMethod to PostedDataMethods::POST

#### expectsJsonData()

Will set the PostedDataMethod to PostedDataMethods::JSON_BODY

#### allow()

Allows access to this endpoint for the roles with the given names. These will override those applied on the Collection or Resource.

#### deny()

Denies access to this endpoint for the roles with the given names. These will override those applied on the Collection or Resource.

#### getAllowedRoles()

Returns the allowed roles.

#### getDeniedRoles()

Returns the denied roles.

### Factory Methods

* Standard
* CRUD

### Standard

* factory()
* get()
* post()
* put()
* delete()
* head()
* options()
* patch()

#### factory()

```php
Endpoint::factory('/books');
```

Returns a default endpoint.

#### get()
```php
\Prest\Api\Endpoint::get('/books');
```
Returns pre-configured GET endpoint

#### post()

```php
Endpoint::post('/books');
```

Returns pre-configured POST endpoint

#### put()
```php
Endpoint::put('/books');
```
Returns pre-configured PUT endpoint

#### delete()

```php
Endpoint::delete('/books');
```
Returns pre-configured DELETE endpoint

#### head()

```php
Endpoint::head('/books')
```
Returns pre-configured HEAD endpoint

#### options()

```php
Endpoint::options('/books');
```
Returns pre-configured OPTIONS endpoint

#### patch()

```php
Endpoint::patch('/books');
```
Returns pre-configured PATCH endpoint

### CRUD

* all()
* find()
* create()
* update()
* remove()

These pre-configured endpoints are by default part of CRUD Resources that have either a ```Prest\Mvc\Controllers\CrudResourceController``` attached or a handler of your own.

#### all()

```php
Endpoint::all();
```
Using this factory will create a GET endpoint with / as path.

> Returns all items

#### find()

```php
Endpoint::find();
```

Using this factory will create a GET endpoint with /{id} as path.

> Returns the item identified by {id}

#### create()

```php
Endpoint::create();
```

Using this factory will create a POST endpoint with / as path.

> Creates a new item using the posted data

#### update()

```php
Endpoint::update();
```

Using this factory will create a PUT endpoint with /{id} as path.

> Updates an existing item identified by {id}, using the posted data

#### remove()

```php
Endpoint::remove();
```

Using this factory will create a DELETE endpoint with /{id} as path.

> Removes the item identified by {id}

## Resources

### Introduction

A Resource reduces the amount of boilerplate code needed to output standard data responses.

```php
use Prest\Api;
use Prest\Api\Resource;
use Prest\Api\Endpoint;
use App\Controllers\BookController;

$api = new Api;

$api->resource(Resource::factory('/books')
    ->name('Book')
    ->handler(BookController::class)
    ->model(Book::class)
    ->expectsJsonData()
    ->transformer(BookTransformer::class)
    ->singleKey('book')
    ->multipleKey('books')
    ->deny(AclRoles::UNAUTHORIZED)
    ->endpoint(Endpoint::get('/featured', 'featured'))
    ->endpoint(Endpoint::get('/sold', 'sold'));
);
```

### Extend

Extend from BookResource from ```Prest\Api\Resource```

```php
use Prest\Api\Resource;

class BookResource extends Resource
{
    protected function initialize()
    {
        $this
            ->name('Book')
            ->handler(BookController::class)
            ->model(Book::class)
            ->expectsJsonData()
            ->transformer(BookTransformer::class)
            ->singleKey('book')
            ->multipleKey('books')
            ->deny(AclRoles::UNAUTHORIZED)
            ->endpoint(Endpoint::get('/featured', 'featured'))
            ->endpoint(Endpoint::get('/sold', 'sold'));
    }
}
```

Register resource on $api

```php
$api = new \Prest\Api;

$api->resource(BookResource::factory('/books'));
```

### Out of the Box CRUD

The Resource::crud factory method will create an instance of the resource with the endpoints all, find, create, update and remove attached to it. This makes CRUD operations work out of the box.
```php
$api = new \Prest\Api;

$api->resource(Resource::crud('/albums', 'Album')
    ->model(Album::class)
    ->expectsJsonData()
    ->transformer(AlbumTransformer::class)
    ->singleKey('album')
    ->multipleKey('albums')
    ->deny(AclRoles::UNAUTHORIZED)
)
```
### Method Listing

* All methods of Collection
* model()
* transformer()
* itemKey()
* collectionKey()

#### model()

Set model for the resource.

#### transformer()

Set transformer for the resource.

#### itemKey()

The key under which single item responses will be generated.
```php
// itemKey set to `book`

{
  "book": {
      //
  }
}
```

#### collectionKey()

The key under which collection responses will be generated.

```php
// collectionKey set to `books`

{
  "books": [{
      //
  }, {
      //
  }]
}
```

## Authentication

### Configure AuthManager

```php
<?php

use Prest\Constants\Services;
use Prest\Auth\Manager as AuthManager;
use App\Auth\UsernameAccountType;

$di->setShared(Services::AUTH_MANAGER, function () use ($config) {

    $authManager = new AuthManager($config->authentication->expirationTime);

    $authManager->registerAccountType(UsernameAccountType::NAME, new UsernameAccountType());

    return $authManager;

});
```

### Add account types

Different account types may be added by creating a class that implements Prest\Auth\AccountType. This interface requires you to provide a login and an authenticate method.

```php
<?php

namespace App\Auth;

use App\Constants\Services;
use Phalcon\Di;
use Prest\Auth\Manager;

class UsernameAccountType implements \Prest\Auth\AccountType
{
    const NAME = "username";

    public function login($data)
    {
        /** @var \Phalcon\Security $security */
        $security = Di::getDefault()->get(Services::SECURITY);

        $username = $data[Manager::LOGIN_DATA_USERNAME];
        $password = $data[Manager::LOGIN_DATA_PASSWORD];

        /** @var \User $user */
        $user = \User::findFirst([
            'conditions' => 'username = :username:',
            'bind' => ['username' => $username]
        ]);

        if(!$user){
            return null;
        }

        if(!$security->checkHash($password, $user->password)){
            return null;
        }

        return (string)$user->id;
    }

    public function authenticate($identity)
    {
        return \User::existsById((int)$identity);
    }
}
```

### Middleware

The middleware can be instantiated as follows:

```php
<?php

use Prest\Middleware\AuthenticationMiddleware;

$eventsManager->attach('micro', new AuthenticationMiddleware);
```

### Authentication Flow

#### Step 1 - Authenticate using a account type

For this purpose we’ve created the Prest/Auth/Manager which will store the session information for the duration of the request.
```php
<?php

use App\Auth\UsernameAccountType;

/**
 * Authenticate user using username and password (Username Account)
 */

/** @var \Prest\Auth\Manager $authManager */
$session = $authManager->loginWithUsernamePassword(UsernameAccountType::NAME, $username, $password);
```

> On failure it will throw an exception (ErrorCodes::AUTH_BADLOGIN)

#### Step 2 - Receive session

After we’ve successfully authenticated we retrieve the Prest/Auth/Session which we then use to create a response.

```php
<?php

/** @var \Prest\Auth\Session $session */

$session = $this->authManager->getSession();

$response = [
    'token' => $session->getToken(),
    'expires' => $session->getExpirationTime()
];
```

#### Step 3 - Store session

Because REST applications are supposed to be stateless, the session needs to be stored on the client.

#### Step 4 - Make subsequent request using the stored session

We can now authenticate any further requests using the received token from the initial authentication. authenticateToken() will throw a new new Exception(ErrorCodes::AUTH_BADTOKEN) when an invalid token has been given or a new Exception(ErrorCodes::AUTH_EXPIRED) when the session token has been expired.
```php
<?php

/** @var \Prest\Http\Request $request */
$token = $this->request->getToken();

if ($token) {
    $this->authManager->authenticateToken($token);
}
```
> We’ve already incorporated the logic above in it’s own piece of middleware. Read more on our middleware.

### AuthManager

#### loggedIn()

The loggedIn methods checks if there has been any session set.

```php
<?php

if ($this->authManager->loggedIn()) {

    // Do something

}
```

#### getSession()

The getSession() method returns the current session.

```php
<?php

if ($this->authManager->loggedIn()) {

    $session = $this->authManager->getSession();

}
```

### Session

#### getIdentity()

```php
<?php

if ($this->authManager->loggedIn()) {

    $session = $this->authManager->getSession();

    $userId = $session->getIdentity(); // For example; 1

    $user = \Users::findFirstById($userId);

}
```

> The identity has been set after successful authentication by the given account type. In this case it’s the user’s id.


## Exceptions

By using exceptions we catch all unintended requests and handle them in a consistent way.
ErrorCodes

Error codes are defined as constants in ```\Prest\Constants\ErrorCodes```

| Code  | Constant                  | Status | Code Message |
| ------|---------------------------|--------|--------------|
| 1010  | GENERAL_SYSTEM            | 500 | General: System Error |
| 1020  | GENERAL_NOT_IMPLEMENTED   | 500 | General: Not Implemented |
| 1030  | GENERAL_NOT_FOUND         | 404 | General: Not Found |
| 2010  | AUTH_INVALID_ACCOUNT_TYPE | 400 | Authentication: Invalid Account Type |
| 2020  | AUTH_LOGIN_FAILED         | 401 | Authentication: Login Failed |
| 2030  | AUTH_TOKEN_INVALID        | 401 | Authentication: Token Invalid |
| 2040  | AUTH_SESSION_EXPIRED      | 401 | Authentication: Session Expired |
| 2050  | AUTH_SESSION_INVALID      | 401 | Authentication: Session Invalid |
| 3010  | ACCESS_DENIED             | 403 | Access: Denied |
| 4010  | DATA_FAILED               | 500 | Data: Failed |
| 4020  | DATA_NOT_FOUND            | 404 | Data: Not Found |

### Outputting Errors

```php
<?php

try {

    // Handle application

} catch (Exception $e) {

    $response = $di->get(Services::RESPONSE);

    $response->setErrorContent($e, true); // true enables debugMode (more error information)

}
```

## Responses

Phalcon REST is designed to work with Fractal’s Transformer concept to handle complex data output. Extending your custom controller from ```Prest\Mvc\Controllers\FractalController``` provides you with several methods for creating different responses.

### FractalController

#### createResponse()

By default createResponse takes $response as argument and returns it. Overriding this method allows you to modify the response before it will be send.
```php
<?php

use Prest\Mvc\Controllers\FractalController;

class CustomController extends FractalController
{
    public function createResponse($response) {

        // For example
        if ($this->responseValid($response)) {
            return $response;
        }

        return null;
    }
}
```

#### createArrayResponse()

Let’s say you want to return just an array with data, for instance an array with your session token.

```php
<?php

use Prest\Mvc\Controllers\FractalController;

class CustomController extends FractalController
{
    public function authenticate() {

        // Authentication code here

        // We've successfully created a session
        return $this->createArrayResponse([
            'token' => $session->getToken(),
            'expires' => $session->getExpirationTime()
        ]);
    }
}
```

Response:

```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1c2VybmFtZSIsInN1YiI6IjEiLCJpYXQiOjE0NDk3NTY4MDAsImV4cCI6MjkwMDExODQwMH0.FE8VYcuh68fxqH17AjILWgcIrOkDp9Q6fBPneD_W4Rc",
    "expires": 1450361600
}
```

#### createOkResponse()

You might want to send just an OK response, for instance when you’ve successfully deleted something.

```php
<?php

use Prest\Mvc\Controllers\FractalController;

class CustomController extends FractalController
{
    public function delete($id) {

        // Remove object code here
        return $this->createOkResponse();
    }
}
```

Response:

```json
{
    "result": "OK"
}
```

#### createItemOkResponse()

Send ok along with item response

```php
<?php

use Prest\Mvc\Controllers\FractalController;

class CustomController extends FractalController
{
    public function find($id) {

        return $this->createItemOkResponse($item, new ItemTransformer, 'item');
    }
}
```

Response:

```php
{
    "result": "OK",
    "item": {
        // ..
    }
}
```

#### createItemResponse()

Creates a Fractal Item response

```php
<?php

use Prest\Mvc\Controllers\FractalController;

class CustomController extends FractalController
{
    public function find($id) {

        return $this->createItemResponse($item, new ItemTransformer, 'item');
    }
}
```

Response:

```json
{
    "item": {
        // ..
    }
}
```

#### createCollectionResponse()

Creates a Fractal Collection response

```php
<?php

use Prest\Mvc\Controllers\FractalController;

class CustomController extends FractalController
{
    public function all() {

        return $this->createCollectionResponse($items, new ItemTransformer, 'items');
    }
}
```

Response:

```json
{
    "items": [{
        // ..
    }, {
        // ..
    }]
}
```

## Services

Resources are a quick way to define endpoint collections.

* Services as Constants
* Factory Default DI
* Custom Services
* Request
* Response
* Authentication Manager
* Fractal Manager
* Token Parser
* API Service
* Query
* PHQL Query Parser
* URL Query Parser

### Services as Constants

Each name of the service that gets registered in the Factory Default DI of Phalcon REST is defined as a constant. This keeps your code organized and provides autocompletion.

Throughout the library we use it as follows:

```php
use Prest\Constants\Services;
```

#### Factory Default DI

Like Phalcon we provide you with a Factory Default DI with all services registered.

```php
$di = new \Prest\Di\FactoryDefault;
```

#### Custom Services

Note that you can always extend from these services and register your own version using the same name. This way Phalcon REST will use your version.

#### Request

```php
$request = $di->get(Services::REQUEST);
```

This service adds some convenience methods. Like getToken() to get the authentication token from either a query parameter or Authorization header.

#### Response

```php
$response = $di->get(Services::RESPONSE);
```

This service is mainly responsible for outputting json formatted errors and data responses in a consistent way.

#### Authentication Manager

```php
$authenticationManager = $di->get(Services::AUTH_MANAGER);
```

This service allows to authenticate using different account types or using a token and getting session info.

#### Fractal Manager

```php
$fractalManager = $di->get(Services::FRACTAL_MANAGER);
```

This service is from a third-party library called Fractal, it provides us with a transformation layer for complex data output.

#### Token Parser

```php
$tokenParser = $di->get(Services::TOKEN_PARSER);
```

This service is responsible for parsing session tokens. At the moment we provide a Json Web Token Parser (\Prest\Auth\TokenParser\JWT). You are free to replace this with your own token parser.

####Query

```php
$query = $di->get(Services::QUERY);
```

This is a global instance of \Prest\Data\Query. It gets configured on each request by \Prest\Middleware\UrlQuery. It's purpose is to provide a layer between the URL Query Syntax and another Query Syntax you prefer. This object can for instance be parsed to a PHQL query.

#### PHQL Query Parser

```php
$phqlQueryParser = $di->get(Services::PHQL_QUERY_PARSER);
```

This service provides a way to apply all options in a \Prest\Data\Query instance to a \Phalcon\Mvc\Model\Query\Builder instance.

#### URL Query Parser

```php
$urlQueryParser = $di->get(AppServices::URL_QUERY_PARSER);
```

This service is used internally to parse GET parameters to the \Prest\Data\Query instance which is stored globally.

## Middleware

Middleware either does configuration work or blocks the request when certain conditions aren't met. Requests are blocked by exceptions. By using exceptions we catch all unintended requests and handle them in a consistent way. Read more on exceptions.

* NotFoundMiddlware
* AuthenticationMiddleware
* AuthorizationMiddleware (Acl)
* FractalMiddleware
* Cross-origin resource sharing (CORS)
    * CorsMiddleware
    * OptionsResponseMiddleware
* UrlQueryMiddleware

### NotFoundMiddleware

Throws a new Exception(ErrorCodes::GEN_NOTFOUND) when an endpoint does not exist (on Phalcon's beforeNotFound event).

```php
use Prest\Middleware\NotFoundMiddleware;

$api->attach(new NotFoundMiddleware());
```

### AuthenticationMiddleware

Authenticates a session token that either has been passed as a query parameter ?token or as an Authorization header with prefixed by Bearer Throws a new Exception(ErrorCodes::AUTH_BADTOKEN) when an invalid token has been passed. Throws a new UserException(ErrorCodes::AUTH_EXPIRED) when an expired token has been passed.

```php
use Prest\Middleware\AuthenticationMiddleware;

$api->attach(new AuthenticationMiddleware());
```

### AuthorizationMiddleware

Throws a new Exception(ErrorCodes::AUTH_FORBIDDEN) when the endpoint is not authorized (ex. excluded for this particular user). Throws a new Exception(ErrorCodes::AUTH_UNAUTHORIZED) when the request is not authorized.

```php
use Prest\Middleware\AuthorizationMiddleware;

$api->attach(new AuthorizationMiddleware());
```

### FractalMiddleware

Configures which includes need to be included in responses managed by the Fractal Manager service.

```php
use Prest\Middleware\FractalMiddleware;

$api->attach(new FractalMiddleware());
```

### Cors

Preflight

> Source: https://en.wikipedia.org/wiki/Cross-origin_resource_sharing

#### CorsMiddleware

Allows all origins provided to make CORS (Cross-origin resource sharing) requests.

```php
use Prest\Middleware\CorsMiddleware;

$api->attach(new CorsMiddleware([
  'frontend-app.dev'
]);
```

Wildcard can also be used

```php
use Prest\Middleware\CorsMiddleware;

$api->attach(new CorsMiddleware(['*']);
```

#### OptionsResponseMiddleware

Responds to all OPTION (preflight) requests with a 200 OK response.

```php
use Prest\Middleware\OptionsResponseMiddleware;

$api->attach(new OptionsResponseMiddleware());
```

### UrlQueryMiddleware

Updates the global query by parsing url query syntax. Read more on URL Query Syntax

```php
use Prest\Middleware\UrlQueryMiddleware;

$api->attach(new UrlQueryMiddleware());
```

## URL Query Syntax

* Query Parameters
    * fields
    * offset
    * limit
    * having
    * where
    * or
    * in
    * sort
* Using parsed Query

### Query Parameters

#### fields

The fields parameter allows you to include just the fields you need.

```title,author```

> http://dev.snab2b.ru.dev/items?fields=title,author

#### offset

The offset parameter allows you to exclude a given number of the first objects returned from a query. this is commonly used for paging, along with limit.

```10```

> http://dev.snab2b.ru.dev/items?offset=10

#### limit

The limit parameter allows you to limit the amount of objects that are returned from a query. This is commonly used for paging, along with offset.

```100```

> http://dev.snab2b.ru.dev/items?limit=10

#### having

author Is Equal 'Jake' AND likes Is Equal 10

```json
{
    "author": "Jake",
    "likes": 10
}
```

> http://dev.snab2b.ru.dev/items?having={"author":"Jake","likes":10}

#### where

The where parameter lets you use conditionals

author Is Like Jake

```json
{
    "author": {
        "l": "Jake"
    }
}
```

> http://dev.snab2b.ru.dev/items?where={"author":{"l":"Jake"}}

author Is Equal Jake

```json
{
    "author": {
        "e": "Jake"
    }
}
```

> http://dev.snab2b.ru.dev/items?where={"author":{"e":"Jake"}}

author Is Not Equal Jake

```json
{
    "author": {
        "ne": "Jake"
    }
}
```

> http://dev.snab2b.ru.dev/items?where={"author":{"ne":"Jake"}}

likes Is Greater Than 10

```json
{
    "likes": {
        "gt": 10
    }
}
```

> http://dev.snab2b.ru.dev/items?where={"likes":{"gt":10}}

likes Is Less Than 10

```json
{
    "likes": {
        "lt": 10
    }
}
```

> http://dev.snab2b.ru.dev/items?where={"likes":{"lt":10}}

likes Is Greater Than Or Equal To 10

```json
{
    "likes": {
        "gte": 10
    }
}
```

> http://dev.snab2b.ru.dev/items?where={"likes":{"gte":10}}

likes Is Less Than Or Equal To 10

```json
{
    "likes": {
        "lte": 10
    }
}
```

> http://dev.snab2b.ru.dev/items?where={"likes":{"lte":10}}

#### or

The or parameter allows you to specify multiple queries for an object to match in an array.

```json
[
    {
        "author": {
            "e": "Jake"
        }
    }, {
        "author": {
            "e": "Alex"
        }
    }
]
```

> http://dev.snab2b.ru.dev/items?or=[{"author":{"e":"Jake"}},{author:{"e":"Alex"}}]

#### in

The in parameter allows you to specify an array of possible matches.

```json
{
    "author": [
        "Jake", 
        "Billy"
    ]
}
```

> http://dev.snab2b.ru.dev/items?in={"author":["Jake","Billy"]}

#### sort

The sort parameter allows you to order your results by the value of a property. The value can be 1 for ascending sort (lowest first; A-Z, 0-10) or -1 for descending (highest first; Z-A, 10-0).

Descending

```json
{
    "likes": -1
}
```

> http://dev.snab2b.ru.dev/items?sort={"likes":-1}

Ascending

```json
{
    "likes": 1
}
```

> http://dev.snab2b.ru.dev/items?sort={"likes":1}

### Using parsed Query

```query``` is a global object that contains a formatted query based on the urls query params.
Use the phqlQueryParser to automatically apply all the url query commands to a new phqlBuilder instance.

```php
<?php

/** @var \Prest\Data\Query $query */
$query = $this->get(Services::QUERY);

/** @var \Prest\Data\Query\Parser\Phql $phqlQueryParser */
$phqlQueryParser = $this->get(Services::PHQL_QUERY_PARSER);

/** @var \Phalcon\Mvc\Model\Query\Builder $phqlBuilder */
$phqlBuilder = $phqlQueryParser->fromQuery($query);

// Resultset
$results = $phqlBuilder->getQuery()->execute();
```

## Access Control

### Create Access Control List

```php
<?php

namespace App\Acl\Adapter;

use Prest\Acl\MountingEnabledAdapterInterface;
use Phalcon\Acl\Adapter\Memory as MemoryAdapter;

class Memory extends MemoryAdapter implements MountingEnabledAdapterInterface
{
    use \AclAdapterMountTrait;
}
```

### Create roles

```php
<?php

/** @var \Prest\Acl\MountingEnabledAdapterInterface $acl */
$acl = $di->get(Services::ACL);


// These are our main roles
$unauthorizedRole = new Acl\Role(AclRoles::UNAUTHORIZED);

$authorizedRole = new Acl\Role(AclRoles::AUTHORIZED);


// We register them on the acl
$acl->addRole($unauthorizedRole);

$acl->addRole($authorizedRole);


// All the following roles extend either from the authorizedRole or the
// unauthorized role.
$acl->addRole(new Acl\Role(AclRoles::ADMINISTRATOR), $authorizedRole);

$acl->addRole(new Acl\Role(AclRoles::MANAGER), $authorizedRole);

$acl->addRole(new Acl\Role(AclRoles::USER), $authorizedRole);


// Because the acl we use implements the `MountingEnabledAdapterInterface`
// we are allowed to mount our Resources on it.
$acl->mountMany($api->getResources());
```

### Restrict access on Resources

```php
<?php

$api->resource(Resource::crud('/users', 'User')

    // Here we restrict access to all endpoints
    // on this Resource. The `User` role is not allowed
    // to access all endpoints by default.
    ->deny(AclRoles::UNAUTHORIZED, AclRoles::USER)

    // Because access can be overridden,
    // we specifically allow access for
    // the `User` role on this endpoint.
    ->endpoint(Endpoint::get('/me', 'me')
        ->allow(AclRoles::USER)
        // .. more endpoint setup
    )

    // When a user has already been authenticated, it doesn't
    // make sense to let them gain access on this endpoint.
    ->endpoint(Endpoint::post('/authenticate', 'authenticate')
        ->allow(AclRoles::UNAUTHORIZED)
        ->deny(AclRoles::AUTHORIZED)
        // .. more endpoint setup
    )

    // .. more resource setup
);
```

## License

Prest is open source software licensed under the MIT License.
See the [`LICENSE.txt`](LICENSE.txt) file for more.

© 2019 Alexandr Polyakov<br>
© 2015-2016 Olivier Andriessen<br>
All rights reserved.