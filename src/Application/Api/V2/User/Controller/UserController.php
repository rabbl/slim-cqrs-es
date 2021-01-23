<?php

declare(strict_types=1);

namespace Application\Api\V2\User\Controller;

use Faker\Factory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Routing\RouteContext;

final class UserController
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();

        $id = (int)$route->getArgument('id');
        if (null === $id) {
            $response->getBody()->write(json_encode($this->users()));

            return $response;
        }

        if ($id > count($this->users())) {
            $response = (new ResponseFactory())->createResponse(404);
            $response->getBody()->write('404: Not Found!');

            return $response;
        }

        $response->getBody()->write(json_encode($this->users()[$id - 1]));

        return $response;
    }

    private function users(): array
    {
        $faker = Factory::create();
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $users[] = [
                'id' => $i + 1,
                'username' => $faker->userName,
                'email' => $faker->email,
                'name' => sprintf("%s %s", $faker->firstName, $faker->lastName),
            ];
        }

        return $users;
    }
}
