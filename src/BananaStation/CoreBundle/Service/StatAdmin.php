<?php

namespace BananaStation\CoreBundle\Service;

use Doctrine\ORM\EntityManager;

class StatAdmin extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getNumberUser() {
        $userRepo = $this->em->getRepository('BananaStationUserBundle:Utilisateur');
        return $userRepo->findNumberUser();
    }

    public function getNumberPlusme() {
        $avisRepo = $this->em->getRepository('BananaStationCoreBundle:Avis');
        return $avisRepo->findNumberPlusme();
    }

    public function getNumberMoinsme() {
        $avisRepo = $this->em->getRepository('BananaStationCoreBundle:Avis');
        return $avisRepo->findNumberMoisme();
    }

    public function getNumberComment() {
        $commentRepo = $this->em->getRepository('BananaStationCoreBundle:Commentaire');
        return $commentRepo->findNumberComment();
    }

    public function getFunctions() {
        return array(
            'getNumberUser' => new \Twig_SimpleFunction('getNumberUser', array($this, 'getNumberUser')),
            'getNumberPlusme' => new \Twig_SimpleFunction('getNumberPlusme', array($this, 'getNumberPlusme')),
            'getNumberMoinsme' => new \Twig_SimpleFunction('getNumberMoinsme', array($this, 'getNumberMoinsme')),
            'getNumberComment' => new \Twig_SimpleFunction('getNumberComment', array($this, 'getNumberComment'))
        );
    }

    public function getName() {
        return 'StatAdmin';
    }
}