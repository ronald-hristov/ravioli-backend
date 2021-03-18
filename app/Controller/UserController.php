<?php declare(strict_types=1);


namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Slim\Http\Request;
use Slim\Http\Response;
use SlimSession\Helper;

class UserController extends AbstractController
{
    public function postAction(Request $request, Response $response)
    {
        $post = $request->getParsedBody();
        $user = new User($post['name'], $post['email']);
        $password = $post['password'];
        $options = ['cost' => 10];
        $hash = password_hash($password, PASSWORD_BCRYPT, $options);
        $user->setPassword($hash);

        /** @var EntityManager $em */
        $em = $this->__get('em');
        $em->persist($user);
        $em->flush();

        // TODO in separate service
        /** @var Helper $session */
        $session = $this->__get('session');
        $session->set('userId', $user->getId());

        $response->getBody()->write(json_encode(['user' => $user]));
        $response = $response->withStatus(201);
        return $response;
    }

    public function authenticateAction(Request $request, Response $response)
    {
        /** @var EntityManager $em */
        $em = $this->__get('em');

        $post = $request->getParsedBody();

        $email = $post['email'];
        $password = $post['password'];

        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            throw new \RuntimeException('User not found');
        }

        // check if password is correct
        $hash = $user->getPassword();
        $isValid = password_verify($password, $hash);

        if (!$isValid) {
            throw new \RuntimeException('Incorrect password');
        }

        /** @var Helper $session */
        $session = $this->__get('session');
        $session->set('userId', $user->getId());

        return $response->getBody()->write(json_encode(['user' => $user]));
    }

    public function logoutAction(Request $request, Response $response)
    {
        /** @var Helper $session */
        $session = $this->__get('session');
        $session->clear();
        $session::destroy();

        return $response;
    }
}