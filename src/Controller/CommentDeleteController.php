<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentDeleteController extends AbstractController
{
    /** @var TrickAddHandler */
    //private $handler;
    /** @var Responder */
    private $responder;
    /** @var EntityManagerInterface */
    private $session;
    private $tokenStorage;

    public function __construct(
        SessionInterface $session,
        TokenStorageInterface $tokenStorage
    ) {
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    /**
    * @Route("/delete_comment/{id}/{trickid}", name="delete_comment")
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete($id, $trickid)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        $comment = $this->getDoctrine()
        ->getRepository(Comment::class)
        ->find($id);
        if ($currentId == $comment->getUserId()) {
            if (!$comment) {
                throw $this->createNotFoundException('Pas de commentaire trouvé avec l\'id '.$id);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->Remove($comment);
            $entityManager->flush();

            $this->session->getFlashBag()->add(
                'success',
                'Commentaire supprimé!'
            );
            return $this->redirectToRoute('comment', ['id' => $trickid, 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            ' Vous n\'avez pas acces a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
