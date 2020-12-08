<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Image;
use App\Form\ImageType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Tool\ImageAddForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ImageAddController extends AbstractController
{
    /** @var ImageAddForm */
    private $imageAddForm;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var SessionInterface */
    private $session;
    
    public function __construct(
        ImageAddForm $imageAddForm,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->imageAddForm = $imageAddForm;
    }

    /**
    * @Route("/add_image/{slug}", name="add_image", requirements={"slug"="[a-z0-9-]+"})
    * @ParamConverter("trick", options={"mapping": {"slug": "slug"}})
    * @IsGranted("ROLE_ADMIN")
    */
    public function imageAdd(Trick $trick)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $picture = new Image();
        if ($currentId == $trick->getUser()) {
            $form = $this->createForm(ImageType::class, $picture);
            if ($this->imageAddForm->form($picture, $trick, $form) === true) {
                return $this->redirectToRoute('comment', ['slug' => $trick->getSlug(), 'page' => '1']);
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
