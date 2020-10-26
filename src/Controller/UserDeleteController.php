<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    //private $entityManager;

    /**
    * @Route("/delete_user/{id}", name="user_delete")
    */
    public function delete($id, UserRepository $userRepository)
    {
        // creates a task object and initializes some data for this example
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));
        //var_dump($id);
        
        $user = $userRepository
        ->find($id);

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->Remove($user);
         $entityManager->flush();

        
        
        return $this->redirectToRoute('home');
    }
}