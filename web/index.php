<?php


declare(strict_types=1);

use Application\Api\V2\Common\AttributeMiddleware;
use Application\Api\V2\Common\MyMiddleware;
use Application\Api\V2\Common\SentryMiddleware;
use Application\Api\V2\User\Controller\UserController;
use DI\Container;

use Application\Api\V2\Messagebox\MessageBoxMiddleware;
use Application\Api\V2\Messagebox\MessageValidationMiddleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\BodyParsingMiddleware;
use function OpenApi\scan;

require_once(dirname(__DIR__) . '/vendor/autoload.php');

error_reporting(-1);
ini_set('display_errors', 'On');

$container = new Container();
$app = AppFactory::createFromContainer($container);
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(SentryMiddleware::class);

/**
 * @OA\Info(title="My First API", version="0.1")
 */

/**
 * @OA\Get(
 *     path="/api/v2/middleware",
 *     tags={"documentation"},
 *     summary="OpenAPI JSON File that describes the API",
 *     @OA\Response(response="200", description=""),
 * )
 */
$app->get('/api/v2/middleware', MyMiddleware::class)
    ->add(AttributeMiddleware::class);

/**
 * @OA\Get(
 *     path="/api/v2/doc",
 *     tags={"documentation"},
 *     summary="OpenAPI JSON File that describes the API",
 *     @OA\Response(response="200", description="OpenAPI Description File"),
 * )
 */
$app->get(
    '/api/v2/doc',
    function (Request $request, Response $response, array $args): Response {
        $swagger = scan(dirname(__DIR__ . '/../../../../../web/api.php'));
        $response->getBody()->write(json_encode($swagger));
        return $response->withHeader('Content-Type', 'application/json');
    }
);

/**
 * @OA\Get(
 *     path="/api/v2/users[/{id:.*}]",
 *     tags={"users"},
 *     @OA\Response(response="200", description=""),
 * )
 */
$app->get(
    '/api/v2/users[/{id:.*}]',
    UserController::class
);

/**
 * @OA\Post(
 *     path="/api/v2/messagebox",
 *     @OA\Response(response="200", description=""),
 * )
 */
$app->post(
    '/api/v2/messagebox',
    MessageBoxMiddleware::class
)
    ->add(BodyParsingMiddleware::class)
    ->add(MessageValidationMiddleware::class);

$app->run();
