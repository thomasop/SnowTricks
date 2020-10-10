<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Trick;
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
        $trick = $this->getDoctrine()
        ->getRepository(Trick::class)
        ->findAll();
        return $this->render('home/home.html.twig', [
            'trick' => $trick
            ]);
    }

    /**
     * @Route("/user/{id}", name="user")
     */
    public function user($id)
    {
        $show = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($id);
        //dd($id);
        
        
        $entityManager = $this->getDoctrine()->getManager();
        //$user = new User();
        $trick = new Trick();
       // $ok = $user->getId();
        //dd($ok);
        
        $trick->setUser($show);
        $trick->setName('tail slide');
        $trick->setDescription('arrière de la planche sur la barre');
        $trick->setType('slides');
        $trick->setPicture('tail slide picture');
        $trick->setVideo('tail slide vidéo');
        $entityManager->persist($trick);
        $entityManager->flush();
        
        return $this->render('home/home.html.twig', [
            'trick' => $trick
            ]);
        
       
    }
}
