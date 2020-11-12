<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\TrickUpdateForm;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class TrickUpdateController extends AbstractController
{
    /** @var TrickAddHandler */
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $trickUpdateForm;
    private $tokenStorage;

    public function __construct(
       TrickUpdateForm $trickUpdateForm,
       TokenStorageInterface $tokenStorage
    ) {
        $this->trickUpdateForm = $trickUpdateForm;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/update_trick/{id}", name="update_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function new($id, Trick $trick)
    {
        $ok = $this->tokenStorage->getToken()->getUser();
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($id);
        if ($ok == $trick->getUser()) {
            $form = $this->createForm(TrickType::class, $trick, ['method' => 'PUT']);
            if ($this->trickUpdateForm->form($trick, $form) === true){

                return $this->redirectToRoute('home');
            }
            return $this->render('form/formtrick.html.twig', [
                'form' => $form->createView()
                ]);
        }
        return $this->redirectToRoute('home');
    }
}