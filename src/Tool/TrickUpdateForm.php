<?php

namespace App\Tool;

use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Tool\DeleteFile;
use App\Tool\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use App\Tool\SlugBuilder;
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
            } elseif ($trick->getPicture()->getBasename() != "default.jpg") {
                $this->deleteFile->delete($trick->getPicture()->getBasename());
                $newImage = $this->fileUploader->upload($images);
                $trick->setPicture($newImage);
            } elseif ($trick->getPicture()->getBasename() == "default.jpg"){
                $newImage = $this->fileUploader->upload($images);
                $trick->setPicture($newImage);
            }
            $slugBuilder = new SlugBuilder();
            $trick->setSlug($slugBuilder->buildSlug($trick->getName()));
            $trick->setUpdatedAt(new \DateTime('now'));
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
