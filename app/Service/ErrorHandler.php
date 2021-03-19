<?php


namespace App\Service;


use Monolog\Logger;
use Psr\Container\ContainerInterface;

class ErrorHandler
{

    /**
     * @var ContainerInterface
     */
    protected $c;

    /**
     * ErrorHandler constructor.
     * @param ContainerInterface $c
     */
    public function __construct(ContainerInterface $c)
    {
      $this->c = $c;

    }

    public function __invoke(\Slim\Http\Request $request, \Slim\Http\Response $response, \Exception $e)
    {
        $c = $this->c;
        /** @var Logger $logger */
        $logger = $c->get('logger');
        $logger->error($e);
        $isProduction = !$c->get('settings')['displayErrorDetails'];
        if (!$isProduction) {
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'text/html')
                ->write("<pre>Error: \n {$e}</pre>");
        }

        $message = 'An error occurred';
        if ($e instanceof \RuntimeException) {
            $message = $e->getMessage();
        }

        $response->getBody()->write(json_encode(['message' => $message]));
        $response = $response->withStatus($e->getCode());
        return $response;
    }
}