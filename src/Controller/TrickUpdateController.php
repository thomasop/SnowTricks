<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickUpdateType;
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
    * @Route("/update_trick/{slug}", name="update_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function new($slug, Trick $trick)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);
        if ($currentId == $trick->getUser()) {
            $file = new File($this->getParameter('pictures_directory').'/'.$trick->getPicture());
            $trick->setPicture($file);
            $form = $this->createForm(TrickUpdateType::class, $trick, ['method' => 'PUT']);
            if ($this->trickUpdateForm->form($trick, $form) === true) {
                return $this->redirectToRoute('comment', ['slug' => $slug, 'page' => '1']);
            }
            return $this->render('form/formupdatetrick.html.twig', [
                'form' => $form->createView(),
                'trick' => $file->getBasename()
                ]);
        }
        return $this->redirectToRoute('home');
    }
}
