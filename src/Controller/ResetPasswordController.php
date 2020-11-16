<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ResetPasswordType;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\TrickUpdateForm;
use App\Repository\UserRepository;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
    * @Route("/forgot-password", name="forgot_password")
    */
    public function forgotPassword(Request $request, UserRepository $userRepository, \Swift_Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form);
            $email = $form->get('email')->getData();
            
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $email]);
            $user->setToken($this->generateToken());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $message = (new \Swift_Message('Hello Email'))
            ->setFrom('thomasdasilva010@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'security/emailreset.html.twig',
                    ['token' => $user->getToken()]
                ),
                'text/html'
            );
            $mailer->send($message);
            return $this->redirectToRoute('app_login');
        }
        return $this->render('form/formforgotpassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
    * @Route("/reset-password/{token}", name="reset_password")
    */
    public function resetPassword($token, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["token" => $token]);
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            if ($user) {
                $entityManager = $this->getDoctrine()->getManager();
                
                $user->setToken(null);
                $user->setPassword(
                    $this->passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
                $this->session->getFlashBag()->add(
                    'success',
                    'mot de passe modifié!'
                );
                return $this->redirectToRoute('app_login');
            } else {
                $this->session->getFlashBag()->add(
                    'success',
                    'mot de passe pas modifié!'
                );
            }
        }
        return $this->render('form/formresetpassword.html.twig', [
                'form' => $form->createView()
            ]);
    }
}
