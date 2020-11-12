<?php

namespace App\Controller;

use App\Entity\{Trick, Video, Image};
use App\Repository\{TrickRepository, ImageRepository, VideoRepository};
use Symfony\Component\HttpFoundation\Response;
use App\Tool\DeleteFile;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrickDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $session;
    private $deleteFile;
    private $tokenStorage;

    public function __construct(
        SessionInterface $session,
        DeleteFile $deleteFile,
        TokenStorageInterface $tokenStorage
    ){
        $this->session = $session;
        $this->deleteFile = $deleteFile;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/delete_trick/{id}", name="delete_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id)
    {
        $ok = $this->tokenStorage->getToken()->getUser();
        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findOneBy(['trickId' => $id]);
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($id);
        $img = $this->getDoctrine()
            ->getRepository(Image::class)
            ->findBy(['trickId' => $id]);
        if ($ok == $trick->getUser()) {
            $this->deleteFile->delete($trick->getPicture());
            foreach ($img as $image){
                $filename = "../public/uploads/pictures/";
                if (file_exists($filename . $image->getName())) {
                    echo "Le fichier" . $image->getName() . "existe.";
                    //($image->getName());
                    unlink($filename . $image->getName());
                } else {
                    echo "Le fichier" . $image->getName() .  "n'existe pas.";
                }
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->Remove($video);
            $entityManager->Remove($trick);
            $entityManager->flush();

            $this->session->getFlashBag()->add(
                'success',
                'Trick supprimé!'
            );
            
            return $this->redirectToRoute('home');
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}