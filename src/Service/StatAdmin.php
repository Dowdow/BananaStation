<?php

namespace App\Service;

use App\Entity\Avis;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class StatAdmin extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getNumberUser() {
        $userRepo = $this->em->getRepository(Utilisateur::class);
        return $userRepo->findNumberUser();
    }

    public function getNumberPlusme() {
        $avisRepo = $this->em->getRepository(Avis::class);
        return $avisRepo->findNumberPlusme();
    }

    public function getNumberMoinsme() {
        $avisRepo = $this->em->getRepository(Avis::class);
        return $avisRepo->findNumberMoisme();
    }

    public function getNumberComment() {
        $commentRepo = $this->em->getRepository(Commentaire::class);
        return $commentRepo->findNumberComment();
    }

    public function getFunctions() {
        return [
            'getNumberUser' => new \Twig_SimpleFunction('getNumberUser', [$this, 'getNumberUser']),
            'getNumberPlusme' => new \Twig_SimpleFunction('getNumberPlusme', [$this, 'getNumberPlusme']),
            'getNumberMoinsme' => new \Twig_SimpleFunction('getNumberMoinsme', [$this, 'getNumberMoinsme']),
            'getNumberComment' => new \Twig_SimpleFunction('getNumberComment', [$this, 'getNumberComment'])
        ];
    }

    public function getName() {
        return 'StatAdmin';
    }
}