<?php

namespace App\Controller;

use App\Entity\{Image, Trick};
use App\Repository\{ImageRepository, TrickRepository};
use App\Tool\{DeleteFile, Remove};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ImageDeleteController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $session;
    private $deleteFile;
    private $tokenStorage;
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
    * @Route("/delete_image/{id}", name="delete_image")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $image = $this->getDoctrine()
            ->getRepository(Image::class)
            ->findOneBy(['id' => $id]);
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
