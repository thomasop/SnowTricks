<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Image;
use App\Entity\Video;
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Handlers\TrickAddHandler;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickAddController extends AbstractController
{
        // creates a task object and initializes some data for this example
    /** @var EntityManagerInterface */
    private $entityManager;
    //private $request;

    
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
        //Request $request
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;;
        //$this->request = $request;
    }


    /**
    * @Route("/add_trick", name="add_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function trickAdd(Request $request, SluggerInterface $slugger)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $image = $form->get('picture')->getData();

            if ($image === null) {
                $image = 'default.jpg';
                $trick->setImage($image);
                //dd($image);
            }
            else {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                }
    
                $trick->setPicture($newFilename);
               // $img = new Image();
                //$img->setName($newFilename);
                //dd($img);
                
                //$trick->addImage($img);
                
            }

            // On récupère les images transmises
            
            $images = $form->get('images')->getData();            
            //dd($images);
            // On boucle sur les images
            foreach($images as $image){
                
                //$newFilename = $this->fileUploader->upload($image);
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
                try {
                    $image->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                }
                // On crée l'image dans la base de données
                $img = new Image();
                $img->setName($newFilename);
                $img->setTrickId($trick);
                $this->entityManager->persist($img);
                //dd($img);
                //$trick->addImage($img);

            }
            
            $videos = $form->get('video')->getData();

            if ($videos !== null) {
                //if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videos, $match)) {
                    $video = new Video();
                    //$video_id = $match[1];
                    $video->setUrl('https://www.youtube.com/watch?v=_WS5ZNl043s');
                    $video->setTrickId($trick);

                    $this->entityManager->persist($video);
                    //$trick->addVideo($video);
                    //dd(addVideo($video));
                //}
            }
            $trick->setUser($this->tokenStorage->getToken()->getUser());
            //$trick->addVideo($video);
            //dd($trick->addVideo($video));
            $this->entityManager->persist($trick);
            $this->entityManager->flush();
            
            //$this->session->getFlashBag()->add("success", "Trick créé !");
            
         
    
            
            return $this->redirectToRoute('home');
        }
        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView()
            ]);
        //return $this->responder->response($form);
    }
        /*
        $trick = new Trick();
        //$task->setTask('Write a blog post');
        //$task->setDueDate(new \DateTime('tomorrow'));


        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
    // $form->getData() holds the submitted values
    // but, the original `$task` variable has also been updated
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();
                try {
                    $pictureFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                }
                $trick->setPicture($newFilename);
            }
    
    
            //$trick = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pictureFile);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView()
            ]);
        // ...
        */
        
    
}