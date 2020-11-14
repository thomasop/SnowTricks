<?php

namespace App\Tool;

use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Trick;
use App\Tool\FileUploader;
use App\Tool\VideoFactory;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class TrickAddForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** FileUploader */
    private $fileUploader;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
    
        TokenStorageInterface $tokenStorage, 
        EntityManagerInterface $entityManager, 
        RequestStack $request, 
        FileUploader $fileUploader,
        SessionInterface $session
    
    ){

        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
        $this->fileUploader = $fileUploader;
        $this->session = $session;
    }

    public function form(Trick $trick, FormInterface $form){

        $form->handleRequest($this->request->getCurrentRequest());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('picture')->getData();
            //dd($images);
            if ($images === []) {
                $images = 'default.jpg';
                //dd($images);
                $trick->setPicture($images);
            }
            else {
                //dd('ok');
                foreach($images as $image){
                    
                    $newImage = $this->fileUploader->upload($image);
                    /*
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
                    */
                    $trick->setPicture($newImage);
                    $img = new Image();
                    $img->setName($newImage);
                    $img->setTrickId($trick);
                    $this->entityManager->persist($img);
                }                
            }
            //$videos = $form->get('videos')->getData();
             
            
            //$videos = $trick->getVideos();
            $trock = $form->getData();
            $videosCollection = $form->getData()->getVideos()->toArray();
            //dd($videosCollection = $form->getData()->getVideos()->toArray());
            VideoFactory::set($videosCollection, $trock);
            
                //$video = new Video();
                /*
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videos, $match)) {
                        $video = new Video();
                        $video_id = $match[1];
                        dd($video_id);

                        $video->setTrickId($trick);
                        $video->setUrl('https://www.youtube.com/embed/' . $video_id);
                        $this->entityManager->persist($video);
                */
                //
                //$video->setUrl($videos);
                //$video->setTrickId($trick);
                
               // $this->entityManager->persist($video);
                //dd($video);
              //  }
            //}

            $trick->setUser($this->tokenStorage->getToken()->getUser());
            //$trick->addVideo($video);
            //dd($trick->addVideo($video));
            //dd($videos);
            $this->entityManager->persist($trick);
            
            $this->entityManager->flush();
            if($form->getData()->getVideos()[0]->getUrl() == null){
                $this->entityManager->Remove($form->getData()->getVideos()[0]);
                $this->entityManager->flush();
            }
            $this->session->getFlashBag()->add(
                'success',
                'Trick ajouté!'
            );
            return true;
        }
        return false;
    }
}