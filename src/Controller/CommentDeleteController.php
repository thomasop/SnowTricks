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

class CommentDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    //private $entityManager;


    public function __construct(
        TokenStorageInterface $tokenStorage
        //Request $request
    ) {
        $this->tokenStorage = $tokenStorage;;
        //$this->request = $request;
    }

    /**
    * @Route("/delete_comment/{id}/{trickid}", name="delete_comment")
    */
    public function delete($id, $trickid, CommentRepository $commentRepository)
    {
        // creates a task object and initializes some data for this example
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));
        //var_dump($id);
        
        $comment = $commentRepository
        ->find($id);
        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->Remove($comment);
         $entityManager->flush();

        
        //$trickid = $comment->getTrick();
        return $this->redirectToRoute('comment', ['id' => $trickid]);
    }
}