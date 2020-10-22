<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\TrickRepository;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserLogType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(TrickRepository $trickRepository)
    {
        $trick = $trickRepository
        ->findAll();
        return $this->render('home/home.html.twig', ['trick' => $trick]);
    }

    /**
     * @Route("/user/{id}", name="user")
     */
    public function user($id, TrickRepository $trickRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //$trick = new Trick();
        //$trick->getName();
        $trick = $trickRepository
        ->find($id);
        //dd($trick);
        $comment = new Comment();
        $comment->setTrick($trick);
        $comment->setName('thomas');
        $comment->setPicture('Picture');
        $comment->setDate(new \DateTime('now'));
        $comment->setContent('super au top cette figure merci pour le tuto!');
        $entityManager->persist($comment);
        //$entityManager->flush();
        return new Response('Saved new product with id '.$comment->getId() . $comment->getTrick()); 
    }
}
