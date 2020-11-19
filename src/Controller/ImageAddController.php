<?php

namespace App\Controller;

use App\Entity\{Trick, Image};
use App\Form\ImageType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Tool\ImageAddForm;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ImageAddController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;
    private $imageAddForm;
    private $tokenStorage;
    private $session;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        ImageAddForm $imageAddForm,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->imageAddForm = $imageAddForm;
    }

    /**
    * @Route("/add_image/{slug}", name="add_image")
    * @IsGranted("ROLE_ADMIN")
    */
    public function commentAdd($slug)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $picture = new Image();
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);
        if ($currentId == $trick->getUser()) {
            $form = $this->createForm(ImageType::class, $picture);
            
            if ($this->imageAddForm->form($picture, $trick, $form) === true) {
                return $this->redirectToRoute('comment', ['slug' => $slug, 'page' => '1']);
            }
            return $this->render('form/formimage.html.twig', [
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
