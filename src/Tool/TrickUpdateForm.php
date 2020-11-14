<?php

namespace App\Tool;

use App\Entity\Image;
use App\Entity\Video;
use App\Entity\Trick;
use App\Tool\{FileUploader, DeleteFile};
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class TrickUpdateForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    private $deleteFile;
    /** FileUploader */
    private $fileUploader;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
    
        TokenStorageInterface $tokenStorage, 
        EntityManagerInterface $entityManager, 
        RequestStack $request,
        DeleteFile $deleteFile,
        FileUploader $fileUploader,
        SessionInterface $session
    
    ){

        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->deleteFile = $deleteFile;
        $this->tokenStorage = $tokenStorage;
        $this->fileUploader = $fileUploader;
        $this->session = $session;
    }

    public function form(Trick $trick, FormInterface $form){

        $form->handleRequest($this->request->getCurrentRequest());
        
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form);
            $images = $form->get('picture')->getData();
            
            if ($images == null) {
                //$images = ;
                $trick->setPicture($trick->getPicture()->getBasename());
            }
            else {
                if ($trick->getPicture()->getBasename() != "default.jpg"){
                    
                    $this->deleteFile->delete($trick->getPicture()->getBasename());
                }
                $newImage = $this->fileUploader->upload($images);
                $trick->setPicture($newImage);
                           
           }
            //$images = $form->get('picture')->getData();
            //if ($form->get('picture')->getData() !== null) {
                /*
            if ($images === null) {
                $images = 'default.jpg';
                $trick->setImage($images);
            }
            else {
                foreach($images as $image){

                    $newImage = $this->fileUploader->upload($image);
                    
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
                
                    $trick->setPicture($newImage);
                    $img = new Image();
                    $img->setName($newImage);
                    $img->setTrickId($trick);
                    $this->entityManager->persist($img);
                }                
            }
            else {
                foreach($images as $image){

                    $newImage = $this->fileUploader->upload($image);
                    
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
                    
                    
                }                
            }
            $videos = $form->get('video')->getData();

            if ($videos !== null) {
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videos, $match)) {
                    $video = new Video();
                    $video_id = $match[1];
                    $video->setUrl('https://www.youtube.com/embed/' . $video_id);
                    $video->setTrickId($trick);

                    $this->entityManager->persist($video);
                }
            }
            $trick->setUser($this->tokenStorage->getToken()->getUser());
            */
            //$trick->addVideo($video);
            //dd($trick->addVideo($video));
            //$this->entityManager->persist($trick);
            $this->entityManager->flush();

            $this->session->getFlashBag()->add(
                'success',
                'Trick modifié!'
            );
            return true;
        }
        return false;
    }
}