<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\Remove;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentDeleteController extends AbstractController
{
    /** @var SessionInterface */
    private $session;
    /** @var Remove */
    private $remove;
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        Remove $remove
    ) {
        $this->remove = $remove;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/delete_comment/{id}/{slug}", name="delete_comment")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, $slug)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find($id);
        if ($currentId == $comment->getUserId()) {
            if (!$comment) {
                throw $this->createNotFoundException('Pas de commentaire trouvé avec l\'id '.$id);
            }
            $this->remove->removeEntity($comment);
            $this->session->getFlashBag()->add(
                'success',
                'Commentaire supprimé!'
            );
            return $this->redirectToRoute('comment', ['slug' => $slug, 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            ' Vous n\'avez pas accès a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
