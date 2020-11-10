<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Tool\CommentAddForm;
use App\Tool\Paging;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
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
        //TokenStorageInterface $tokenStorage
        CommentAddForm $commentAddForm
        //Request $request
    ) {
        $this->entityManager = $entityManager;
        //$this->tokenStorage = $tokenStorage;;
        $this->commentAddForm = $commentAddForm;
        //$this->request = $request;
    }

    /**
     * @Route("/comment/{id}/{page}", name="comment")
     */
    public function comment($id, $page, ImageRepository $imageRepository, VideoRepository $videoRepository, CommentRepository $commentRepository, Request $request, SluggerInterface $slugger)
    {
        $pagination = new Paging();
        //$trick = new Trick();
        $comment = new Comment();
        $image = new Image();
        $form = $this->createForm(CommentType::class, $comment);
        $trick = $this->getDoctrine()
        ->getRepository(Trick::class)
        ->find($id);
        $commentt = $commentRepository
        ->findByTrickAndPaginate($trick, $page, 5 );

        
        
        $image = $imageRepository
        ->findBy(['trickId' => $id]);

        $video = $videoRepository
        ->findBy(['trickId' => $id]);
        //$trick = $this->getDoctrine()
        //->getRepository(Trick::class)
        //->find($id);
        //$form->handleRequest($request);
        
        if ($this->commentAddForm->form($comment, $trick, $form) === true) {
            
            return $this->redirectToRoute('comment', ['id' => $id, 'page' => $page]);
        }
         
    
            
            //return $this->redirectToRoute('comment', ['id' => $id]);
        //}
        //return $this->render('form/formcomment.html.twig', [
         //   'form' => $form->createView()
          //  ]);
        return $this->render('comment/comment.html.twig', ['pagination' =>  $pagination->pagingComments($page, $commentt),'image' => $image, 'video' => $video, 'trick' => $trick, 'comment' => $commentt, 'form' => $form->createView()]);
    }
}