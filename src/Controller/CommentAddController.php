<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrickAddController extends AbstractController
{
        // creates a task object and initializes some data for this example
    /** @var EntityManagerInterface */
    private $entityManager;
    //private $request;

    
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
        //Request $request
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;;
        //$this->request = $request;
    }


    /**
    * @Route("/add_comment/{id}", name="add_trick")
    */
    public function commentAdd(Request $request, SluggerInterface $slugger)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $comment->setUser($this->tokenStorage->getToken()->getUser());
            //$trick->addVideo($video);
            //dd($trick->addVideo($video));
            $this->entityManager->persist($trick);
            $this->entityManager->flush();
            
            //$this->session->getFlashBag()->add("success", "Trick créé !");
            
         
    
            
            return $this->redirectToRoute('home');
        }
        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView()
            ]);
        
    } 
    
}