<?php

namespace App\Tool;

use App\Entity\{Image, Video, Trick};
use App\Tool\{FileUploader, VideoFactory, SlugBuilder};
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
    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
        $this->fileUploader = $fileUploader;
        $this->session = $session;
    }

    public function form(Trick $trick, FormInterface $form)
    {
        $form->handleRequest($this->request->getCurrentRequest());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('picture')->getData();
            if ($images === null) {
                $images = 'default.jpg';
                $trick->setPicture($images);
            }
            $newImage = $this->fileUploader->upload($images);
            $trick->setPicture($newImage);
            $picture = $form->get('images')->getData();
            foreach ($picture as $image) {
                $newImages = $this->fileUploader->upload($image);
                        
                $img = new Image();
                $img->setName($newImages);
                $img->setTrickId($trick);
                $this->entityManager->persist($img);
            }
            
            $trock = $form->getData();
            $videosCollection = $form->getData()->getVideos()->toArray();
            VideoFactory::set($videosCollection, $trock);
            $slugBuilder = new SlugBuilder();
            $trick->setUser($this->tokenStorage->getToken()->getUser());
            $trick->setSlug($slugBuilder->buildSlug($trick->getName()));
            $trick->setCreatedAt(new \DateTime('now'));
            $this->entityManager->persist($trick);
            $this->entityManager->flush();
            if ($form->getData()->getVideos()[0]->getUrl() == null) {
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
