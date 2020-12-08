<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tool\DeleteFile;
use App\Tool\Remove;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserDeleteController extends AbstractController
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
    * @Route("/delete_user/{id}", name="user_delete", requirements={"id"="\d+"})
    * @ParamConverter("user", options={"mapping": {"id": "id"}})
    * @IsGranted("ROLE_SUPER_ADMIN")
    */
    public function delete(User $user)
    {
        $currentId = $this->tokenStorage->getToken()->getUser()->getId();
        if ($user->getAvatar() != "defaultavatar.png") {
            $this->deleteFile->delete($user->getAvatar());
        }
        $this->remove->removeEntity($user);
        $this->session->getFlashBag()->add(
            'success',
            'User supprimé!'
        );
        
        return $this->redirectToRoute('app_user', ['id' => $currentId]);
    }
}
