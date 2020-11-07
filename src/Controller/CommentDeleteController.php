<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class CommentDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $session;


    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->tokenStorage = $tokenStorage;;
        $this->session = $session;
    }

    /**
    * @Route("/delete_comment/{id}", name="delete_comment")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, CommentRepository $commentRepository)
    {
        // creates a task object and initializes some data for this example
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));
        //var_dump($id);
        
        $comment = $commentRepository
        ->find($id);
        //Sdd($comment);
        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        if (!$comment) {
            throw $this->createNotFoundException('No livre found for id '.$id);
        } 
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->Remove($comment);
         $entityManager->flush();

         $this->session->getFlashBag()->add(
            'success',
            'Commentaire supprimé!'
        );
        //$trickid = $comment->getTrick();
        return $this->redirectToRoute('home');
    }
}