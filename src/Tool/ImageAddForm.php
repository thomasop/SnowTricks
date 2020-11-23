<?php

namespace App\Tool;

use App\Entity\Image;
use App\Entity\Trick;
use App\Tool\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ImageAddForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var FileUploader */
    private $fileUploader;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        FileUploader $fileUploader,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->fileUploader = $fileUploader;
        $this->session = $session;
    }

    public function form(Image $image, Trick $trick, FormInterface $form)
    {
        $form->handleRequest($this->request->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('name')->getData();
            $newAvatar = $this->fileUploader->upload($picture);
            $image->setName($newAvatar);
            $image->setTrickId($trick);

            $this->entityManager->persist($image);
            $this->entityManager->flush();
            $this->session->getFlashBag()->add(
                'success',
                'Image ajouté!'
            );
            return true;
        }
        return false;
    }
}
