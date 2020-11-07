<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class VideoDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $session;

    public function __construct(
        SessionInterface $session
    
    ){
        $this->session = $session;
    }

    /**
    * @Route("/delete_video/{id}", name="delete_video")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, VideoRepository $videoRepository)
    {
        // creates a task object and initializes some data for this example
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));
        //var_dump($id);
        
        $video = $videoRepository
        ->find($id);

        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        //dd($video);
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->Remove($video);
         $entityManager->flush();

         $this->session->getFlashBag()->add(
            'success',
            'Video supprimé!'
        );
        
        return $this->redirectToRoute('home');
    }
}