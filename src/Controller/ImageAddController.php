<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Form\ImageType;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Tool\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\String\Slugger\SluggerInterface;
//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;



class ImageAddController extends AbstractController
{
        // creates a task object and initializes some data for this example
    /** @var EntityManagerInterface */
    private $entityManager;
    //private $request;
    private $fileUploader;
    private $session;
    
    public function __construct(
        FileUploader $fileUploader,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    
    ){
        $this->fileUploader = $fileUploader;
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    /**
    * @Route("/add_image/{id}", name="add_image")
    * @IsGranted("ROLE_ADMIN")
    */
    public function commentAdd($id, Request $request)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

       
        $trick = new Trick();
        $picture = new Image();
        $trick = $this->getDoctrine()
        ->getRepository(Trick::class)
        ->find($id);
        $form = $this->createForm(ImageType::class, $picture);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           // $user = $form->getData();
            $image = $form->get('name')->getData();

            $newAvatar = $this->fileUploader->upload($image);

            $picture->setName($newAvatar);
            $picture->setTrickId($trick);
            
            $this->entityManager->persist($picture);
            //dd($picture);
            $this->entityManager->flush();
            
            //$this->session->getFlashBag()->add("success", "Trick créé !");
            
         
    
            
            return $this->redirectToRoute('home');
        }
        return $this->render('form/formimage.html.twig', [
            'form' => $form->createView()
            ]);
        
    } 
    
}