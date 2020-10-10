<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserLogType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }

    /**
     * @Route("/user", name="user")
     */
    public function user()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        dd($user);
        /*
        $user->setPseudo('thomas');
        $user->setPassword('$2y$13$tQDMscQXOnJikOTa21lxjeIPDPQq1StAE/Uo083MyPpFrtMAsfBWK');
        $user->setEmail('tdss33@hotmail.fr');

        $trick = new Trick();
        $trick->setUser();
        $trick->setName();
        $trick->setDescription();
        $trick->setType();
        $trick->setPicture();
        $trick->setVideo();
        */

        //$entityManager->persist($user);
        //$entityManager->flush();
        return new Response('Saved new product with id '.$user->getId()); 
    }
    
}
