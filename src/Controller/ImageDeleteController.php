<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Tool\DeleteFile;
use App\Tool\Remove;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ImageDeleteController extends AbstractController
{
    /** @var SessionInterface */
    private $session;
    /** @var DeleteFile */
    private $deleteFile;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var Remove */
    private $remove;

    public function __construct(
        SessionInterface $session,
        DeleteFile $deleteFile,
        TokenStorageInterface $tokenStorage,
        Remove $remove
    ) {
        $this->session = $session;
        $this->deleteFile = $deleteFile;
        $this->tokenStorage = $tokenStorage;
        $this->remove = $remove;
    }

    /**
    * @Route("/delete_image/{id}", name="delete_image", requirements={"id"="\d+"})
    * @ParamConverter("image", options={"mapping": {"id": "id"}})
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete(Image $image)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        if ($currentId == $image->getTrickId()->getUser()) {
            $this->deleteFile->delete($image->getName());
            $this->remove->removeEntity($image);
            $this->session->getFlashBag()->add(
                'success',
                'Image supprimé!'
            );
            return $this->redirectToRoute('comment', ['slug' => $image->getTrickId()->getSlug(), 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
