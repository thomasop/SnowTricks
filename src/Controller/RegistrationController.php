<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Tool\RegistrationForm;
use App\Tool\{EmailService, VerifyUserEmail};
use App\Repository\UserRepository;
use App\Responders\RegistrationResponder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RegistrationController extends AbstractController
{
    private $emailService;
    private $verifyUserEmail;

    public function __construct(
        RegistrationForm $registrationForm,
        SessionInterface $session,
        EmailService $emailService,
        VerifyUserEmail $verifyUserEmail
    ) {
        $this->registrationForm = $registrationForm;
        $this->session = $session;
        $this->emailService = $emailService;
        $this->verifyUserEmail = $verifyUserEmail;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function new()
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        if ($this->registrationForm->form($user, $form) === true) {
            $this->emailService->mail($user->getEmail(), $user->getToken(), 'security/email.html.twig');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('form/formuser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify-email/{token}", name="app_verify_email")
     */
    public function verifyUserEmail($token)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(["token" => $token]);
        
        $this->verifyUserEmail->verifyEmail($user);
        return $this->redirectToRoute('app_login');
        
    }
}
