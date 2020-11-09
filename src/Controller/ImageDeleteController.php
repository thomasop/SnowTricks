<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Tool\DeleteFile;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class ImageDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $session;
    private $deleteFile;


    public function __construct(
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        DeleteFile $deleteFile
    ) {
        $this->tokenStorage = $tokenStorage;;
        $this->session = $session;
        $this->deleteFile = $deleteFile;
    }

    /**
    * @Route("/delete_image/{id}", name="delete_image")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, ImageRepository $imageRepository)
    {
        // creates a task object and initializes some data for this example
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));
        //var_dump($id);
        
        $image = $imageRepository
        ->find($id);
        $this->deleteFile->delete($image->getName());
        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->Remove($image);
         $entityManager->flush();

         $this->session->getFlashBag()->add(
            'success',
            'Image supprimé!'
        );
        //$trickid = $comment->getTrick();
        return $this->redirectToRoute('home');
    }
}