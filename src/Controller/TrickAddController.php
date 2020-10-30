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

    
    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;;
    }


    /**
    * @Route("/add_trick", name="add_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function trickAdd(Request $request, SluggerInterface $slugger)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('picture')->getData();
            if ($images === null) {
                $images = 'default.jpg';
                $trick->setImage($images);
            }
            else {
                foreach($images as $image){

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
                    $img = new Image();
                    $img->setName($newFilename);
                    $img->setTrickId($trick);
                    $this->entityManager->persist($img);
                }                
            }
            $videos = $form->get('video')->getData();

            if ($videos !== null) {
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videos, $match)) {
                    $video = new Video();
                    $video_id = $match[1];
                    $video->setUrl('https://www.youtube.com/watch?v=_WS5ZNl043s' . $video_id);
                    $video->setTrickId($trick);

                    $this->entityManager->persist($video);
                }
            }
            $trick->setUser($this->tokenStorage->getToken()->getUser());
            //$trick->addVideo($video);
            //dd($trick->addVideo($video));
            $this->entityManager->persist($trick);
            $this->entityManager->flush();
            
            return $this->redirectToRoute('home');
        }
        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
            ]);
    }
}