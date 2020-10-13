<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickAddController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    //private $entityManager;

    /**
    * @Route("/add_trick", name="add_trick")
    */
    public function new(Request $request)
    {
        // creates a task object and initializes some data for this example
        $trick = new Trick();
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));


        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $trick = $form->getData();

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->persist($trick);
         $entityManager->flush();

        return $this->redirectToRoute('home');
    }
        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView()
            ]);
        // ...
    }
}