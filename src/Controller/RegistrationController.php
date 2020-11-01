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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\UserRepository;

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
    public function new(Request $request, SluggerInterface $slugger, \Swift_Mailer $mailer)
    {
        // just setup a fresh $task object (remove the example data)
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $image = $form->get('avatar')->getData();

            if ($image === null) {
                $image = 'default.jpg';
                $user->setAvatar($image);
                //dd($image);
            }
            else {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                }
    
                $user->setAvatar($newFilename);
               // $img = new Image();
                //$img->setName($newFilename);
                //dd($img);
                
                //$trick->addImage($img);
                
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            }

            //On encode le password
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
                );
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $user->setRoles(['ROLE_ADMIN']);
            $entityManager = $this->getDoctrine()->getManager();
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('thomasdasilva010@gmail.com')
            ->setTo('thomasdasilva010@gmail.com')
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'security/email.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            )

        // you can remove the following code if you don't define a text version for your emails
        
    ;

    $mailer->send($message);

    //return $this->render(...);
            //$entityManager->persist($user);
            //$entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('form/formuser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail($id, UserRepository $userRepository, Request $request): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $userRepository
        ->find($id);
        // On valide l'email
        
        try {
            $entityManager = $this->getDoctrine()->getManager();
            //$this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            $user->setStatus('1');

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (VerifyEmailExceptionInterface $exception) {
            return $this->redirectToRoute('app_register');
        }

        //$this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }


    
}