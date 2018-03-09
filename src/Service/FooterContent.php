<?php

namespace App\Service;

use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;

class FooterContent extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getFooterContentProjects() {
        $projectRepo = $this->em->getRepository(Projet::class);
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
