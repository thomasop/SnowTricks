<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentType;
use App\Tool\CommentAddForm;
use App\Tool\Paging;
use App\Repository\VideoRepository;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use Doctrine\DBAL\Driver\Connection;

class CommentController extends AbstractController
{
    public function __construct(
        CommentAddForm $commentAddForm
    ) {
        $this->commentAddForm = $commentAddForm;
    }

    /**
     * @Route("/comment/{slug}/{page}", name="comment")
     */
    public function comment($slug, $page)
    {
        $pagination = new Paging();
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findOneBy(['slug' => $slug]);

        $commentt = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findByTrickAndPaginate($trick, $page, 5);

        $image = $this->getDoctrine()
            ->getRepository(Image::class)
            ->findBy(['trickId' => $trick->getId()]);

        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findBy(['trickId' => $trick->getId()]);
        
        if ($this->commentAddForm->form($comment, $trick, $form) === true) {
            return $this->redirectToRoute('comment', ['slug' => $slug, 'page' => $page]);
        }
        return $this->render('comment/comment.html.twig', ['pagination' =>  $pagination->pagingComments($page, $commentt),'image' => $image, 'video' => $video, 'trick' => $trick, 'comment' => $commentt, 'form' => $form->createView()]);
    }
}
