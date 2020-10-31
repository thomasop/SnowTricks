<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserLogType;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{

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
     * @Route("/comment/{id}", name="comment")
     */
    public function comment($id, TrickRepository $trickRepository, CommentRepository $commentRepository, Request $request, SluggerInterface $slugger)
    {
        $trick = new Trick();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $commentt = $commentRepository
        ->findBy(['trick' => $id]);

        $trick = $trickRepository
        ->find($id);

        //$trick = $this->getDoctrine()
        //->getRepository(Trick::class)
        //->find($id);
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
            
         
    
            
            return $this->redirectToRoute('comment', ['id' => $id]);
        }
        //return $this->render('form/formcomment.html.twig', [
         //   'form' => $form->createView()
          //  ]);
        return $this->render('comment/comment.html.twig', ['trick' => $trick, 'comment' => $commentt, 'form' => $form->createView()]);
    }
}