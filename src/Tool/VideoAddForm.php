<?php

namespace App\Tool;

use App\Entity\Video;
use App\Entity\Trick;
use App\Tool\{FileUploader, VideoFactory};
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class VideoAddForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** FileUploader */
    private $fileUploader;
    private $session;
    
    public function __construct(
    
        TokenStorageInterface $tokenStorage, 
        EntityManagerInterface $entityManager, 
        RequestStack $request,
        UserPasswordEncoderInterface $passwordEncoder,
        FileUploader $fileUploader,
        SessionInterface $session
    
    ){
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
        $this->fileUploader = $fileUploader;
        $this->session = $session;
    }

    public function form(Video $video, Trick $trick, FormInterface $form){

        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $video->setTrickId($trick);

            $videoIdExtractor = new VideoIdExtractor();
            $video->setUrl($videoIdExtractor->urlToId($video->getUrl()));
            $this->entityManager->persist($video);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add(
                'success',
                'Video ajouté!'
            );
        return true;
        }
        return false;
    }
}