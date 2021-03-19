<?php declare(strict_types=1);


namespace App\Factory;


use App\Middleware\CanEditMiddleware;
use App\Middleware\UserAwareRequestMiddleware;
use Doctrine\ORM\EntityManager;

class CanEditMiddlewareFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $c
     * @return CanEditMiddleware
     */
    public static function create(\Psr\Container\ContainerInterface $c): CanEditMiddleware
    {
        /** @var EntityManager $em */
        $em = $c->get('em');

        return new CanEditMiddleware($em);
    }
}