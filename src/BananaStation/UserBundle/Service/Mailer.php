<?php

namespace BananaStation\UserBundle\Service;

use BananaStation\UserBundle\Entity\Utilisateur;
use Symfony\Component\Templating\EngineInterface;

class Mailer {

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    protected $from;

    public function __construct($from, \Swift_Mailer $mailer, EngineInterface $templating) {
        $this->from = $from;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendInscription(Utilisateur $user) {
        $subject = 'Bienvenue sur Banana Station !';
        $to = $user->getEmail();
        $body = $this->templating->render(
            'BananaStationUserBundle:Mail:inscription.txt.twig', array('user' => $user)
        );

        return $this->sendMessage($to, $subject, $body);
    }

    public function sendRecoverPassword(Utilisateur $user) {
        $subject = 'Votre mail de réinitialisation de mot de passe';
        $to = $user->getEmail();
        $body = $this->templating->render(
            'BananaStationUserBundle:Mail:password.txt.twig', array('user' => $user)
        );

        return $this->sendMessage($to, $subject, $body);
    }

    protected function sendMessage($to, $subject, $body) {
        $mail = \Swift_Message::newInstance();

        $mail
            ->setFrom($this->from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body);

        $sent = $this->mailer->send($mail);
        return count($sent) === 0 ? false : true;
    }

}
