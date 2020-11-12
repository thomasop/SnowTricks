<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Tool\RegistrationForm;
use App\Repository\UserRepository;
use App\Responders\RegistrationResponder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RegistrationController extends AbstractController
{

    public function __construct(
        
        RegistrationForm $registrationForm,
        SessionInterface $session
    
    ){
        $this->registrationForm = $registrationForm;
        $this->session = $session;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function new(\Swift_Mailer $mailer)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        if ($this->registrationForm->form($user, $form) === true) {
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('thomasdasilva010@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'security/email.html.twig',
                    ['token' => $user->getToken()]
                ),
                'text/html'
            );
            $mailer->send($message);
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
        
        if($user) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setEnabled(true);
            $user->setToken(null);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->session->getFlashBag()->add(
                'success',
                'compte valide!'
            );
            return $this->redirectToRoute('app_login');
        } 
        else  {
            $this->session->getFlashBag()->add(
                'success',
                'Une erreur est survenu, veillez reessayez!'
            );
            return $this->redirectToRoute('app_register');
        }
    }
}