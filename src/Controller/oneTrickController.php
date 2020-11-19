<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class oneTrickController extends AbstractController
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
    * @Route("/one_trick/{slug}", name="one_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function oneTrick($slug)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);
        if ($currentId == $trick->getUser()) {
            return $this->render('trick/trick.html.twig', [
                'trick' => $trick
                ]);
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
