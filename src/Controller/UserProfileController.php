<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;

class UserProfileController extends AbstractController{

    /**
    * @Route("/profile-user/{id}", name="user_profile")
    * @IsGranted("ROLE_ADMIN")
    */
    public function showOneProfile($id, UserRepository $userRepository){
        $user = $userRepository
        ->find($id);

        return $this->render('user/profile.html.twig', ['user' => $user]);
    }
}