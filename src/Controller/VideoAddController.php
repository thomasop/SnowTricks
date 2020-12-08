<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Video;
use App\Form\VideoType;
use Doctrine\ORM\EntityManagerInterface;
use App\Tool\VideoAddForm;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class VideoAddController extends AbstractController
{
    private $entityManager;
    private $videoAddForm;
    private $tokenStorage;
    private $session;

    public function __construct(
        EntityManagerInterface $entityManager,
        VideoAddForm $videoAddForm,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->videoAddForm = $videoAddForm;
    }

    /**
    * @Route("/add_video/{slug}", name="add_video", requirements={"slug"="[a-z0-9-]+"})
    * @ParamConverter("trick", options={"mapping": {"slug": "slug"}})
    * @IsGranted("ROLE_ADMIN")
    */
    public function videoAdd(Trick $trick)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $video = new Video();
        if ($currentId == $trick->getUser()) {
            $form = $this->createForm(VideoType::class, $video);
            if ($this->videoAddForm->form($video, $trick, $form) === true) {
                return $this->redirectToRoute('comment', ['slug' => $trick->getSlug(), 'page' => '1']);
            }
            return $this->render('form/formvideo.html.twig', [
                'form' => $form->createView()
                ]);
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
