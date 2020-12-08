<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\TrickUpdateForm;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrickUpdateController extends AbstractController
{
    /** @var TrickUpdateForm */
    private $trickUpdateForm;
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        TrickUpdateForm $trickUpdateForm,
        TokenStorageInterface $tokenStorage
    ) {
        $this->trickUpdateForm = $trickUpdateForm;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/update_trick/{slug}", name="update_trick", requirements={"slug"="[a-z0-9-]+"})
    * @ParamConverter("trick", options={"mapping": {"slug": "slug"}})
    * @IsGranted("ROLE_ADMIN")
    */
    public function new(Trick $trick)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        if ($currentId == $trick->getUser()) {
            $file = new File($this->getParameter('pictures_directory').'/'.$trick->getPicture());
            $trick->setPicture($file);
            $form = $this->createForm(TrickType::class, $trick, ['method' => 'PUT']);
            if ($this->trickUpdateForm->form($trick, $form) === true) {
                return $this->redirectToRoute('comment', ['slug' => $trick->getSlug(), 'page' => '1']);
            }
            return $this->render('form/formupdatetrick.html.twig', [
                'form' => $form->createView(),
                'trick' => $file->getBasename()
                ]);
        }
        return $this->redirectToRoute('home');
    }
}
