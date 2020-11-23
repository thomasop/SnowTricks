<?php

namespace App\Tool;

use App\Entity\Comment;
use App\Entity\Trick;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentAddForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager,
        RequestStack $request,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    public function form(Comment $comment, Trick $trick, FormInterface $form)
    {
        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setName($this->tokenStorage->getToken()->getUser()->getPseudo());
            $comment->setUserId($this->tokenStorage->getToken()->getUser());
            $comment->setTrick($trick);
            $comment->setDate(new \DateTime('now'));
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add(
                'success',
                'Commentaire ajouté!'
            );
            return true;
        }
        return false;
    }
}
