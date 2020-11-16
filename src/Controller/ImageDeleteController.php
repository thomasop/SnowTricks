<?php

namespace App\Controller;

use App\Entity\{Image, Trick};
use App\Repository\{ImageRepository, TrickRepository};
use App\Tool\DeleteFile;
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

    public function __construct(
        SessionInterface $session,
        DeleteFile $deleteFile,
        TokenStorageInterface $tokenStorage
    ) {
        $this->session = $session;
        $this->deleteFile = $deleteFile;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/delete_image/{id}/{trickid}", name="delete_image")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, $trickid)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($trickid);
        $image = $this->getDoctrine()
            ->getRepository(Image::class)
            ->find($id);
        if ($currentId == $trick->getUser()) {
            $this->deleteFile->delete($image->getName());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->Remove($image);
            $entityManager->flush();

            $this->session->getFlashBag()->add(
                'success',
                'Image supprimé!'
            );
            return $this->redirectToRoute('comment', ['id' => $trickid, 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
