<?php

namespace BananaStation\MusicBundle\Service;

use Doctrine\ORM\EntityManager;

class Compteur extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getTotalMusic() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalMusic();
    }

    public function getTotalGames() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('G');
    }

    public function getTotalTrap() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('T');
    }

    public function getTotalElectro() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('E');
    }

    public function getTotalDubstep() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('D');
    }

    public function getFunctions() {
        return [
            'getTotalMusic' => new \Twig_SimpleFunction('getTotalMusic', [$this, 'getTotalMusic']),
            'getTotalGames' => new \Twig_SimpleFunction('getTotalGames', [$this, 'getTotalGames']),
            'getTotalTrap' => new \Twig_SimpleFunction('getTotalTrap', [$this, 'getTotalTrap']),
            'getTotalElectro' => new \Twig_SimpleFunction('getTotalElectro', [$this, 'getTotalElectro']),
            'getTotalDubstep' => new \Twig_SimpleFunction('getTotalDubstep', [$this, 'getTotalDubstep'])
        ];
    }

    public function getName() {
        return 'Compteur';
    }
} 