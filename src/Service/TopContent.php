<?php

namespace App\Service;

use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;

class TopContent extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getTopPlusme() {
        $projetRepo = $this->em->getRepository(Projet::class);
        $projets = $projetRepo->findTopPlusme();
        foreach ($projets as $item) {
            foreach ($item->getAvis() as $value) {
                if ($value->getPouce() == 'M') {
                    $item->removeAvis($value);
                }
            }
        }
        return $projets;
    }

    public function getTopComment() {
        $projectRepo = $this->em->getRepository(Projet::class);
        return $projectRepo->findTopComment();
    }

    public function getFunctions() {
        return [
            'getTopPlusme' => new \Twig_SimpleFunction('getTopPlusme', [$this, 'getTopPlusme']),
            'getTopComment' => new \Twig_SimpleFunction('getTopComment', [$this, 'getTopComment'])
        ];
    }

    public function getName() {
        return 'TopContent';
    }

}
