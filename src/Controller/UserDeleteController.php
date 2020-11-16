<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tool\DeleteFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserDeleteController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $session;
    private $deleteFile;

    public function __construct(
        SessionInterface $session,
        DeleteFile $deleteFile
    ) {
        $this->session = $session;
        $this->deleteFile = $deleteFile;
    }

    /**
    * @Route("/delete_user/{id}", name="user_delete")
    * @IsGranted("ROLE_SUPER_ADMIN")
    */
    public function delete($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->Remove($user);
        $entityManager->flush();
        if($user->getAvatar() != "defaultvatar.png" ){
            $this->deleteFile->delete($user->getAvatar());
        }
        $this->session->getFlashBag()->add(
            'success',
            'User supprimé!'
        );
        
        return $this->redirectToRoute('home');
    }
}
