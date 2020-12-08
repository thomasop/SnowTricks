<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class oneTrickController extends AbstractController
{
    /** @var SessionInterface */
    private $session;
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/one_trick/{slug}", name="one_trick", requirements={"slug"="[a-z0-9-]+"})
    * @ParamConverter("trick", options={"mapping": {"slug": "slug"}})
    * @IsGranted("ROLE_ADMIN")
    */
    public function oneTrick(Trick $trick)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
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
