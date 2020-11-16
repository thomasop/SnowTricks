<?php

namespace App\Tool;

use App\Entity\{Image, Trick, Video};
use App\Tool\{DeleteFile, FileUploader};
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TrickUpdateForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var DeleteFile */
    private $deleteFile;
    /** @var FileUploader */
    private $fileUploader;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        DeleteFile $deleteFile,
        FileUploader $fileUploader,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->deleteFile = $deleteFile;
        $this->fileUploader = $fileUploader;
        $this->session = $session;
    }

    public function form(Trick $trick, FormInterface $form)
    {
        $form->handleRequest($this->request->getCurrentRequest());
        
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('picture')->getData();
            
            if ($images == null) {
                $trick->setPicture($trick->getPicture()->getBasename());
            } else {
                if ($trick->getPicture()->getBasename() != "default.jpg") {
                    $this->deleteFile->delete($trick->getPicture()->getBasename());
                }
                $newImage = $this->fileUploader->upload($images);
                $trick->setPicture($newImage);
            }
            
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
