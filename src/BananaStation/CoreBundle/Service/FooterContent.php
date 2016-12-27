<?php

namespace BananaStation\CoreBundle\Service;

use Doctrine\ORM\EntityManager;

class FooterContent extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getFooterContentProjects() {
        $projectRepo = $this->em->getRepository('BananaStationCoreBundle:Projet');
        return $projectRepo->findLastThree();
    }

    public function getFunctions() {
        return [
            'getFooterContentProjects' => new \Twig_SimpleFunction('getFooterContentProjects', [$this, 'getFooterContentProjects'])
        ];
    }

    public function getName() {
        return 'FooterContent';
    }

}
