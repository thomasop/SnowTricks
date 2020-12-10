<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ResetPasswordType;
use App\Tool\EmailService;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\ResetPasswordForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;
    private $emailService;
    private $resetPasswordForm;
    private $session;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        EmailService $emailService,
        ResetPasswordForm $resetPasswordForm,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->emailService = $emailService;
        $this->session = $session;
        $this->resetPasswordForm = $resetPasswordForm;
    }

    /**
    * @Route("/forgot-password", name="forgot_password")
    */
    public function forgotPassword(Request $request, UserRepository $userRepository)
    {
        $user = new User();
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $email]);
            if ($user) {
                $user->setToken($this->generateToken());

                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $this->session->getFlashBag()->add(
                    'success',
                    'Adresse mail trouvé, veuillez verifier votre email pour changer votre mot de passe!'
                );
                $this->emailService->mail($user->getEmail(), $user->getToken(), 'security/emailreset.html.twig');
                return $this->redirectToRoute('app_login');
            }
            $this->session->getFlashBag()->add(
                'success',
                'Adresse mail pas trouvé, rentrez une adresse mail valide!!'
            );
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
        if ($this->resetPasswordForm->form($user, $form) === true) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('form/formresetpassword.html.twig', [
                'form' => $form->createView()
            ]);
    }
}
