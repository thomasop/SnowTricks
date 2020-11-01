<?php

namespace App\Tool;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Tool\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class CommentAddForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** FileUploader */
    private $fileUploader;
    
    public function __construct(
    
        TokenStorageInterface $tokenStorage, 
        EntityManagerInterface $entityManager, 
        RequestStack $request,
        UserPasswordEncoderInterface $passwordEncoder,
        FileUploader $fileUploader
    
    ){
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
        $this->fileUploader = $fileUploader;
    }

    public function form(Comment $comment, Trick $trick, FormInterface $form){

        $form->handleRequest($this->request->getCurrentRequest());
        //$trick = new Trick;
        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setUserId($this->tokenStorage->getToken()->getUser());
            $comment->setTrick($trick);
            $comment->setDate(new \DateTime('now'));
            //$trick->addVideo($video);
            //dd($trick->addVideo($video));
            //dd($comment);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
        return true;
        }
        return false;
    }
}