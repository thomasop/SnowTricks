<?php

namespace App\Controller;

use App\Entity\Trick;
//use App\Entity\Image;
//use App\Entity\Video;
//use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\TrickRepository;
//use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\String\Slugger\SluggerInterface;
//use App\Tool\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class oneTrickController extends AbstractController
{
        // creates a task object and initializes some data for this example
    /** @var EntityManagerInterface */
    private $entityManager;
    //private $fileUploader;

    
    public function __construct(
        EntityManagerInterface $entityManager
        //TokenStorageInterface $tokenStorage,
       
    ) {
        $this->entityManager = $entityManager;
        //$this->tokenStorage = $tokenStorage;
        //$this->FileUploader = $fileUploader;
    }


    /**
    * @Route("/one_trick/{id}", name="one_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function oneTrick($id, Request $request, TrickRepository $trickRepository)
    { 
        //echo 'ok';
        $trick = new Trick();
        $trick = $trickRepository
        ->findOneBy(['id' => $id]);
        
       
        

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick
            ]);
    }
}