<?php

namespace App\Controller;

use App\Service\Epay\Client;
use App\Service\Epay\Service;
use Slim\Http\Request;
use Slim\Http\Response;

class IndexController extends AbstractController
{
    /**
     * Home page
     *
     * @param Request $request
     * @param Response $response
     * @return \Psr\Http\Message\ResponseInterface|Response
     */
    public function indexAction(Request $request, Response $response)
    {
        $response = $this->view->render($response, 'index/index.phtml');
        return $response;
    }

}