<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Trick;
use App\Repository\VideoRepository;
use App\Repository\TrickRepository;
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
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/delete_video/{id}/{slug}", name="delete_video")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, $slug)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);
        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->find($id);
        if ($currentId == $trick->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->Remove($video);
            $entityManager->flush();

            $this->session->getFlashBag()->add(
                'success',
                'Video supprimé!'
            );
            return $this->redirectToRoute('comment', ['slug' => $slug, 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
