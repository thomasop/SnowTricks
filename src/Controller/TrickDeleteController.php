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
use App\Tool\Remove;
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
                $this->deleteFile->delete($image->getName());
            }
            
            if ($video) {
                $this->remove->removeEntity($video);
            }
            $this->remove->removeEntity($trick);
            $this->session->getFlashBag()->add(
                'success',
                'Trick supprimé!'
            );
            
            return $this->redirect('http://localhost:8000/#trick');
        }
        $this->session->getFlashBag()->add(
            'success',
            'Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
