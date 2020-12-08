<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="app_user")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function index()
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('user/user.html.twig', ['user' => $user]);
    }
}
