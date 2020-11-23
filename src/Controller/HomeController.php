<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TrickRepository;
use App\Entity\Trick;

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
        return $this->render('home/home.html.twig', ['trick' => $trick]);
    }
}
