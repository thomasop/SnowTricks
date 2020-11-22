<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Video;
use App\Form\TrickType;
use Symfony\Component\HttpFoundation\Response;
use App\Tool\TrickAddForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class TrickAddController extends AbstractController
{
    /** @var TrickAddForm */
    private $trickAddForm;

    public function __construct(
        TrickAddForm $trickAddForm
    ) {
        $this->trickAddForm = $trickAddForm;
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
            return $this->redirect('http://localhost:8000/#trick');
        }

        return $this->render('form/formtrick.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick
            ]);
    }
}
