<?php

namespace App\Tool;

use App\Entity\User;
use App\Tool\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RegistrationForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** FileUploader */
    private $fileUploader;
    private $session;
    
    public function __construct(
    
        TokenStorageInterface $tokenStorage, 
        EntityManagerInterface $entityManager, 
        RequestStack $request,
        UserPasswordEncoderInterface $passwordEncoder,
        FileUploader $fileUploader,
        SessionInterface $session
    
    ){
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
        $this->fileUploader = $fileUploader;
        $this->session = $session;
    }

    public function form(User $user, FormInterface $form){

        $form->handleRequest($this->request->getCurrentRequest());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $image = $form->get('avatar')->getData();

            if ($image === null) {
                $image = 'default.jpg';
                $user->setAvatar($image);
            }
            else {
                $newAvatar = $this->fileUploader->upload($image);

                $user->setAvatar($newAvatar);
                
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
            $user->setToken($this->generateToken());
            $user->setRoles(['ROLE_ADMIN']);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add(
                'success',
                'Compte créé, veuillez verifier votre email!'
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