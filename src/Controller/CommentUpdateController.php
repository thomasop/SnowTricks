<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\CommentUpdateForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentUpdateController extends AbstractController
{
    /** @var CommentUpdateForm */
    private $commentUpdateForm;
    /** @var TokenStorageInterface */
    private $tokenStorage;
    /** @var SessionInterface */
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
    * @Route("/update_comment/{id}/{slug}", name="update_comment", requirements={"slug"="[a-z0-9-]+", "id"="\d+"})
    * @IsGranted("ROLE_ADMIN")
    */
    public function new($id, $slug, Request $request)
    {
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(['id' => $id]);
        $trick = $this->getDoctrine()
            ->getRepository(Trick::class)
            ->findBy(['slug' => $slug]);
        $currentId = $this->tokenStorage->getToken()->getUser();
        if ($currentId == $comment->getUserId()) {
            $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
            if ($this->commentUpdateForm->form($comment, $form) === true) {
                return $this->redirectToRoute('comment', ['slug' => $trick->getSlug(), 'page' => '1']);
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
