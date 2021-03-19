<?php declare(strict_types=1);


namespace App\Middleware;


use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;

class CanEditMiddleware implements MiddlewareInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * CanEditMiddleware constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function __invoke(Request $request, Response $response, callable $next): ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute('user');
        if (!$user) {
            throw new \RuntimeException('User not logged in', 401);
        }

        /** @var Route $route */
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');

        if (!$id) {
            throw new \RuntimeException('Post id missing', 400);
        }

        /** @var Post $post */
        $post = $this->em->getRepository(Post::class)->find($id);
        if (!$post) {
            throw new \RuntimeException('Post not found', 404);
        }

        if ($user->getId() !== $post->getUser()->getId()) {
            throw new \RuntimeException('Post user mismatch', 401);
        }


        $response = $next($request, $response);
        return $response;
    }
}