<?php

namespace App\Controller;

use App\Entity\{Trick, Video};
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\TrickAddForm;
use App\Responders\TrickAddResponder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickAddController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;
    //private $fileUploader;
    private $trickAddForm;

    public function __construct(
        EntityManagerInterface $entityManager, 
        TrickAddForm $trickAddForm
    ) {
        $this->trickAddForm = $trickAddForm;
        $this->entityManager = $entityManager;
    }

    /**
    * @Route("/add_trick", name="add_trick")
    * @IsGranted("ROLE_ADMIN")
    */
    public function trickAdd()
    { 
        $trick = new Trick();
        $video1 = new Video();
        $video1->setUrl('');
        $trick->addVideo($video1);
        $form = $this->createForm(TrickType::class, $trick);
        if ($this->trickAddForm->form($trick, $form) === true) {
            
            return $this->redirectToRoute('home');
        }

        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
            ]);
    }
}