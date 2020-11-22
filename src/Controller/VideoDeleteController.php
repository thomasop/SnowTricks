<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Trick;
use App\Repository\VideoRepository;
use App\Tool\Remove;
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
    private $remove;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        Remove $remove
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->remove = $remove;
    }

    /**
    * @Route("/delete_video/{id}", name="delete_video")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findOneBy(['id' => $id]);
        if ($currentId == $video->getTrickId()->getUser()) {
            $this->remove->removeEntity($video);
            $this->session->getFlashBag()->add(
                'success',
                'Video supprimé!'
            );
            return $this->redirectToRoute('comment', ['slug' => $video->getTrickId()->getSlug(), 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
