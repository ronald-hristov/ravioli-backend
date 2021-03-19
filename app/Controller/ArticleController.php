<?php declare(strict_types=1);


namespace App\Controller;


use App\Entity\Post;
use App\Service\FileService;
use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

class ArticleController extends AbstractController
{
    public function getAction(Request $request, Response $response, $args)
    {
        /** @var EntityManager $em */
        $em = $this->__get('em');
        $criteria = [];
        if (isset($args['id'])) {
            $criteria = ['id' => $args['id']];
        }
        $data = $em->getRepository(Post::class)->findBy($criteria, ['dateCreated' => 'DESC']);

        if ($criteria) {
            $data = reset($data);
        }

        return $response->getBody()->write(json_encode($data));
    }

    public function postAction(Request $request, Response $response)
    {
        /** @var UploadedFile $file */
        $file = $request->getUploadedFiles()['file'];
        /** @var FileService $fileService */
        $fileService = $this->__get(FileService::class);
        $post = $request->getParsedBody();
        $imgPath = $fileService->moveImgToArticleFolder($file);
        $article = new Post($post['title'], $post['content']);
        $article->setImage($imgPath);
        $article->setUser($request->getAttribute('user'));
        /** @var EntityManager $em */
        $em = $this->__get('em');
        $em->persist($article);
        $em->flush();

        $response->getBody()->write(json_encode($article));
        $response = $response->withStatus(201);

        return $response;
    }

    public function editAction(Request $request, Response $response, $args)
    {
        /** @var EntityManager $em */
        $em = $this->__get('em');
        /** @var Post $article */
        $article = $em->getRepository(Post::class)->find($args['id']);
        if (!$article) {
            throw new \RuntimeException('Article not found');
        }

        $post = $request->getParsedBody();
        $article->setTitle($post['title']);
        $article->setContent($post['content']);

        /** @var UploadedFile $file */
        $file = $request->getUploadedFiles()['file'];
        if ($file) {
            /** @var FileService $fileService */
            $fileService = $this->__get(FileService::class);
            $imgPath = $fileService->moveImgToArticleFolder($file);
            $article->setImage($imgPath);
        }

        $em->persist($article);
        $em->flush();

        $response->getBody()->write(json_encode($article));
        $response = $response->withStatus(200);

        return $response;
    }

    public function deleteAction(Request $request, Response $response, $args)
    {
        /** @var EntityManager $em */
        $em = $this->__get('em');
        $article = $em->getRepository(Post::class)->findOneBy(['id' => $args['id']]);
        if (!$article) {
            throw new \RuntimeException('Article not found');
        }

        $em->remove($article);
        $em->flush();


        return $response;
    }
}