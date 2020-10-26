<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationType;
use App\Form\RegistrationFormType;
use App\Handlers\RegistrationHandler;
use App\Responders\RegistrationResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        
        UserPasswordEncoderInterface $passwordEncoder
    
    ){

        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function new(Request $request)
    {
        // just setup a fresh $task object (remove the example data)
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

            //On encode le password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
                );
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $user->setRoles(['ROLE_USER']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('form/formuser.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}