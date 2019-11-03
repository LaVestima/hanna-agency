<?php

namespace App\Controller\Account;

use App\Controller\Infrastructure\BaseController;
use App\Entity\Token;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @Route("/account")
 */
class AccountController extends BaseController
{
    /**
     * @Route("/activate/{tokenText}", name="account_activate")
     */
    public function activate(string $tokenText)
    {
        dump($tokenText);

        /** @var Token|null $token */
        $token = $this->entityManager->getRepository(Token::class)
            ->findOneBy([
                'token' => $tokenText
            ]);

        if (!$token) {
            $this->addFlash('error', 'Invalid token');
            dump('INVALID');

            return $this->redirectToRoute('ho');
        }

        if ($token->getDateExpired() < (new \DateTime())) {
            $this->addFlash('error', 'Token expired');
            dump('EXPIRED');
            // TODO: redirect
        }

        $user = $token->getUser();

        if (!$user) {
            throw new \LogicException('Token must be assigned to a user!');
        }

        $user->setIsActive(true);
        $token->setDateExpired(new \DateTime());

        $this->entityManager->flush();

        $securityToken = new UsernamePasswordToken($user, null, 'main', $user->getRoles());

        $this->container->get('security.token_storage')->setToken($securityToken);

        $this->addFlash('success', 'Account activated');

        return $this->redirectToRoute('settings_index');
    }
}
