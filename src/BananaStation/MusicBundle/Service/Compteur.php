<?php

namespace BananaStation\MusicBundle\Service;

use Doctrine\ORM\EntityManager;

class Compteur extends \Twig_Extension{

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

    public function getTotalHipHop() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('H');
    }

    public function getTotalMovies() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('M');
    }

    public function getTotalElectro() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('E');
    }

    public function getTotalDubstep() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('D');
    }

    public function getTotalRock() {
        $musicRepo = $this->em->getRepository('BananaStationMusicBundle:Music');
        return $musicRepo->getTotalStyle('R');
    }

    public function getFunctions() {
        return array(
            'getTotalMusic' => new \Twig_Function_Method($this, 'getTotalMusic'),
            'getTotalGames' => new \Twig_Function_Method($this, 'getTotalGames'),
            'getTotalHipHop' => new \Twig_Function_Method($this, 'getTotalHipHop'),
            'getTotalMovies' => new \Twig_Function_Method($this, 'getTotalMovies'),
            'getTotalElectro' => new \Twig_Function_Method($this, 'getTotalElectro'),
            'getTotalDubstep' => new \Twig_Function_Method($this, 'getTotalDubstep'),
            'getTotalRock' => new \Twig_Function_Method($this, 'getTotalRock')
        );
    }

    public function getName() {
        return 'Compteur';
    }
} 