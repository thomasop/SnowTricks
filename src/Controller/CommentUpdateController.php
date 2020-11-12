<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\CommentUpdateForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentUpdateController extends AbstractController
{
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $commentUpdateForm;
    private $tokenStorage;
    private $session;

    public function __construct( 
       CommentUpdateForm $commentUpdateForm,
       TokenStorageInterface $tokenStorage,
       SessionInterface $session
    ) {
        $this->commentUpdateForm = $commentUpdateForm;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    /**
    * @Route("/update_comment/{id}/{trickid}", name="update_comment")
    * @IsGranted("ROLE_ADMIN")
    */
    public function new($id, $trickid, Request $request, Comment $comment)
    {
        $ok = $this->tokenStorage->getToken()->getUser();
        $commentId = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);
        if ($ok == $commentId->getUserId()){
            $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
            if ($this->commentUpdateForm->form($comment, $form) === true){
                return $this->redirectToRoute('comment', ['id' => $trickid, 'page' => '1']);
            }
            return $this->render('form/formcomment.html.twig', [
                'form' => $form->createView()
                ]);
        } 
        $this->session->getFlashBag()->add(
            'success',
            ' Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');      
    }
}