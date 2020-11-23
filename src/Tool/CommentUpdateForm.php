<?php

namespace App\Tool;

use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommentUpdateForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->session = $session;
    }

    public function form(Comment $comment, FormInterface $form)
    {
        $form->handleRequest($this->request->getCurrentRequest());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->session->getFlashBag()->add(
                'success',
                'Commentaire modifié!'
            );
            return true;
        }
        return false;
    }
}
