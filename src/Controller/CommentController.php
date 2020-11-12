<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{User, Trick, Image, Comment, Video};
use App\Form\CommentType;
use App\Tool\{Paging, CommentAddForm};
use App\Repository\{TrickRepository, VideoRepository, CommentRepository, ImageRepository};
use Doctrine\DBAL\Driver\Connection;

class CommentController extends AbstractController
{

    public function __construct(
        CommentAddForm $commentAddForm
    ) {
        $this->commentAddForm = $commentAddForm;
    }

    /**
     * @Route("/comment/{id}/{page}", name="comment")
     */
    public function comment($id, $page)
    {
        $pagination = new Paging();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->find($id);

        $commentt = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findByTrickAndPaginate($trick, $page, 5 );

        $image = $this->getDoctrine()
            ->getRepository(Image::class)
            ->findBy(['trickId' => $id]);

        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findBy(['trickId' => $id]);
        
        if ($this->commentAddForm->form($comment, $trick, $form) === true) {
            
            return $this->redirectToRoute('comment', ['id' => $id, 'page' => $page]);
        }
        return $this->render('comment/comment.html.twig', ['pagination' =>  $pagination->pagingComments($page, $commentt),'image' => $image, 'video' => $video, 'trick' => $trick, 'comment' => $commentt, 'form' => $form->createView()]);
    }
}