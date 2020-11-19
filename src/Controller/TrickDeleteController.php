<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Image;
use App\Repository\TrickRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
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
    ) {
        $this->session = $session;
        $this->deleteFile = $deleteFile;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/delete_trick/{slug}", name="delete_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($slug)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);
        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findOneBy(['trickId' => $trick->getId()]);
        $img = $this->getDoctrine()
            ->getRepository(Image::class)
            ->findBy(['trickId' => $trick->getId()]);
        if ($currentId == $trick->getUser()) {
            if ($trick->getPicture() != "default.jpg") {
                $this->deleteFile->delete($trick->getPicture());
            }
            foreach ($img as $image) {
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
            if ($video) {
                //dd('ok');
                $entityManager->Remove($video);
            }
            //dd('no');
            
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
