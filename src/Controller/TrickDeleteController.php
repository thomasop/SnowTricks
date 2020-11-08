<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use App\Repository\VideoRepository;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\DeleteFile;
use App\Responders\TrickAddResponder;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $session;
    private $deleteFile;

    public function __construct(
        SessionInterface $session,
        DeleteFile $deleteFile
    
    ){
        $this->session = $session;
        $this->deleteFile =$deleteFile;
    }

    /**
    * @Route("/delete_trick/{id}", name="delete_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, TrickRepository $trickRepository, VideoRepository $videoRepository, ImageRepository $imageRepository)
    {
        // creates a task object and initializes some data for this example
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));
        //var_dump($id);
        $video = $videoRepository
        ->findOneBy(['trickId' => $id]);
        $trick = $trickRepository
        ->find($id);
        $img = $imageRepository->findBy(['trickId' => $id]);
        //dd($trick->getPicture());
        $this->deleteFile->delete($trick->getPicture());
        foreach ($img as $image){
            $filename = "../public/uploads/pictures/";
            if (file_exists($filename . $image->getName())) {
                echo "Le fichier" . $image->getName() . "existe.";
                //($image->getName());
                unlink($filename . $image->getName());
            } else {
                echo "Le fichier" . $image->getName() .  "n'existe pas.";
            }
        }
        
        // ... perform some action, such as saving the task to the database
        // for example, if Task is a Doctrine entity, save it!
        
        
        //dd($img->getName());
        //$fileSystem->exists('%kernel.project_dir%/public/uploads/pictures/');
        //dd($fileSystem->exists(['public/uploads/pictures/']));
        
         $entityManager = $this->getDoctrine()->getManager();
         $entityManager->Remove($video);
         $entityManager->Remove($trick);
         $entityManager->flush();

         $this->session->getFlashBag()->add(
            'success',
            'Trick supprimé!'
        );
        
        return $this->redirectToRoute('home');
        
    }
}