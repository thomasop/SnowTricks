<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserLogType;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    /**
     * @Route("/SnowTricks/comment/{id}", name="comment")
     */
    public function comment($id, TrickRepository $trickRepository, CommentRepository $commentRepository)
    {
        $trick = $trickRepository
        ->find($id);

        $comment = $commentRepository
        ->findByField($id);
        return $this->render('comment/comment.html.twig', ['trick' => $trick, 'comment' => $comment]);
    }
}