<?php

namespace App\Tool;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class VerifyUserEmail {

    private $entityManager;
    private $session;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session) {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function verifyEmail(Object $user) {
        if ($user) {
            $user->setEnabled(true);
            $user->setToken(null);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add(
                'success',
                'compte valide!'
            );
        } else {
            $this->session->getFlashBag()->add(
                'success',
                'Utilisateur non trouvé, veillez reessayez!'
            );
        }
    }
}