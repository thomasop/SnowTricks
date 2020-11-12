<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserDeleteController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $session;

    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
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

         $this->session->getFlashBag()->add(
            'success',
            'User supprimé!'
        );
        
        return $this->redirectToRoute('home');
    }
}