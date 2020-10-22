<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentAddController extends AbstractController
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
    * @Route("/add_comment/{id}", name="add_comment")
    */
    public function commentAdd($id, Request $request, SluggerInterface $slugger)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

       
        $trick = new Trick();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        
        $trick = $this->getDoctrine()
        ->getRepository(Trick::class)
        ->find($id);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $comment->setUserId($this->tokenStorage->getToken()->getUser());
            $comment->setTrick($trick);
            $comment->setDate(new \DateTime('now'));
            //$trick->addVideo($video);
            //dd($trick->addVideo($video));
            //dd($comment);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            
            //$this->session->getFlashBag()->add("success", "Trick créé !");
            
         
    
            
            return $this->redirectToRoute('home');
        }
        return $this->render('form/formcomment.html.twig', [
            'form' => $form->createView()
            ]);
        
    } 
    
}