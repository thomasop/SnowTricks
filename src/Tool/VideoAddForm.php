<?php

namespace App\Tool;

use App\Entity\{Trick, Video};
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class VideoAddForm
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var Request */
    private $request;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        SessionInterface $session
    ) {
        $this->entityManager = $entityManager;
        $this->request = $request;
        $this->session = $session;
    }

    public function form(Video $video, Trick $trick, FormInterface $form)
    {
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
