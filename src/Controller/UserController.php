<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\Connection;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="app_user")
     */
    public function index($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();
        return $this->render('user/user.html.twig', ['user' => $user]);
    }
}