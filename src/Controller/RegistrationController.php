<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationType;
use App\Tool\RegistrationForm;
use App\Repository\UserRepository;
use App\Responders\RegistrationResponder;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Mailer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class RegistrationController extends AbstractController
{

    public function __construct(
        
        RegistrationForm $registrationForm,
        UserRepository $userRepository,
        SessionInterface $session
    
    ){
        $this->registrationForm = $registrationForm;
        $this->userRepository = $userRepository;
        $this->session = $session;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function new(Request $request, \Swift_Mailer $mailer)
    {
        // just setup a fresh $task object (remove the example data)
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        if ($this->registrationForm->form($user, $form) === true) {
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('thomasdasilva010@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
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
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->userRepository
        ->findOneBy(["token" => $token]);
        // On valide l'email
        
        if($user) {
            $entityManager = $this->getDoctrine()->getManager();
            //$this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
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
                'compte non valide!'
            );
            return $this->redirectToRoute('app_register');
        }

        //$this->addFlash('success', 'Your email address has been verified.');

        
    }


    
}