<?php


namespace App\Middleware;


use App\Entity\User;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UserAwareRequestMiddleware implements MiddlewareInterface
{
    /**
     * @var \App\Entity\User|null
     */
    protected $user;

    /**
     * PermissionsMiddleware constructor.
     * @param User|null $user
     */
    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface
    {
        $request = $request->withAttribute('user', $this->user);
        $response = $next($request, $response);
        return $response;
    }
}