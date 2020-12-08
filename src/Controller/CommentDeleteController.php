<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\Remove;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
    * @Route("/delete_comment/{id}/{slug}", name="delete_comment", requirements={"slug"="[a-z0-9-]+", "id"="\d+"})
    * @ParamConverter("comment", options={"mapping": {"id": "id"}})
    * @ParamConverter("trick", options={"mapping": {"slug": "slug"}})
    * @IsGranted("ROLE_ADMIN")
    */
    public function delete(Comment $comment, Trick $trick)
    {
        $currentId = $this->tokenStorage->getToken()->getUser();
        if ($currentId == $comment->getUserId()) {
            if (!$comment) {
                throw $this->createNotFoundException('Pas de commentaire trouvé avec l\'id '.$id);
            }
            $this->remove->removeEntity($comment);
            $this->session->getFlashBag()->add(
                'success',
                'Commentaire supprimé!'
            );
            return $this->redirectToRoute('comment', ['slug' => $trick->getSlug(), 'page' => '1']);
        }
        $this->session->getFlashBag()->add(
            'success',
            ' Vous n\'avez pas accès a cette page!'
        );
        return $this->redirectToRoute('home');
    }
}
