<?php

namespace App\Service;

use App\Entity\Utilisateur;
use Symfony\Component\Templating\EngineInterface;

class Mailer
{
    /** @var \Swift_Mailer  */
    protected $mailer;

    /** @var EngineInterface  */
    protected $templating;

    /** @var String  */
    protected $from;

    /**
     * Mailer constructor.
     * @param \Swift_Mailer $mailer
     * @param EngineInterface $templating
     * @param string $from
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, String $from = 'no-reply@banana-station.fr')
    {
        $this->from = $from;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param Utilisateur $user
     * @return bool
     * @throws \RuntimeException
     */
    public function sendInscription(Utilisateur $user): bool
    {
        $subject = 'Bienvenue sur Banana Station !';
        $to = $user->getEmail();
        $body = $this->templating->render(
            'user/Mail/inscription.txt.twig', ['user' => $user]
        );

        return $this->sendMessage($to, $subject, $body);
    }

    /**
     * @param Utilisateur $user
     * @return bool
     * @throws \RuntimeException
     */
    public function sendRecoverPassword(Utilisateur $user): bool
    {
        $subject = 'Votre mail de réinitialisation de mot de passe';
        $to = $user->getEmail();
        $body = $this->templating->render(
            'user/Mail/password.txt.twig', ['user' => $user]
        );

        return $this->sendMessage($to, $subject, $body);
    }

    /**
     * @param string $to
     * @param string $subject
     * @param $body
     * @return bool
     */
    protected function sendMessage($to, $subject, $body): bool
    {
        $mail = new \Swift_Message();

        $mail
            ->setFrom($this->from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body);

        $sent = $this->mailer->send($mail);
        return $sent !== 0;
    }

}
