<?php

namespace App\Tool;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordForm {

    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    private $session;
    private $passwordEncoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        SessionInterface $session,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->passwordEncoder = $passwordEncoder;
        $this->session = $session;
    }

    public function form(User $user, FormInterface $form) {
        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $user->setToken(null);
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();            
            $this->session->getFlashBag()->add(
                'success',
                'Mot de passe modifié !'
            );
            return true;
        }
        return false;
    }

    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}