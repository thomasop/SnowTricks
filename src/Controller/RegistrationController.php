<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationType;
use App\Tool\RegistrationForm;
use App\Responders\RegistrationResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    public function __construct(
        
        RegistrationForm $registrationForm
    
    ){
        $this->registrationForm = $registrationForm;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the example data)
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        if ($this->registrationForm->form($user, $form) === true) {
            
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('form/formuser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}