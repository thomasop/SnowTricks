<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CommentUpdateController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    //private $entityManager;

    /**
    * @Route("/update_comment/{id}/{trickid}", name="update_comment")
    * @IsGranted("ROLE_ADMIN")
    */
    public function new($id, $trickid, Request $request, EntityManagerInterface $em)
    {
        // creates a task object and initializes some data for this example
        //$entityManager = $this->getDoctrine()->getManager();
        //$product = $entityManager->getRepository(Trick::class)->find($id);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

           //$trick = $form->getData();
            //var_dump($product);
            $em->flush();
            return $this->redirectToRoute('comment', ['id' => $trickid]);
        }
        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView()
            ]);
        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));

        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been update

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
    }
}