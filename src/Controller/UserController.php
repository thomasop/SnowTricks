<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\UserRepository;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserLogType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="app_user")
     */
    public function index($id, UserRepository $userRepository)
    {
        $user = $userRepository
        ->findAll();
        return $this->render('user/user.html.twig', ['user' => $user]);
    }
}