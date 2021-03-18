<?php declare(strict_types=1);


namespace App\Controller;


use Slim\Http\Request;
use Slim\Http\Response;

class ArticleController extends AbstractController
{
    public function getAction(Request $request, Response $response)
    {
        
    }

    public function postAction(Request $request, Response $response)
    {
        
    }

    public function deleteAction(Request $request, Response $response)
    {
        // TODO check only admin can delete
    }
}