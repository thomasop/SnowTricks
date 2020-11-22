<?php

namespace App\Tool;

class EmailService {

    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig\Environment $templating) {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }
    
    public function mail($mail, $token, $view) {
        $message = (new \Swift_Message('SnowTricks'))
            ->setFrom('thomasdasilva010@gmail.com')
            ->setTo($mail)
            ->setBody(
                $this->templating->render(
                    $view,
                    ['token' => $token]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}
