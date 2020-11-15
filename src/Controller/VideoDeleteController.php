<?php

namespace App\Controller;

use App\Entity\{Video, Trick};
use App\Repository\{VideoRepository, TrickRepository};
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VideoDeleteController extends AbstractController
{
    private $session;
    private $tokenStorage;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    
    ){
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/delete_video/{id}/{trickid}", name="delete_video")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, $trickid)
    {
        $ok = $this->tokenStorage->getToken()->getUser();
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($trickid);
        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->find($id);
        if($ok == $trick->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->Remove($video);
            $entityManager->flush();

            $this->session->getFlashBag()->add(
                'success',
                'Video supprimé!'
            );
            return $this->redirectToRoute('comment', ['id' => $trickid, 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}