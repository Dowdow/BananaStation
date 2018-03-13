<?php

namespace App\Service;

use App\Entity\Music;
use Doctrine\ORM\EntityManagerInterface;
use Twig_SimpleFunction;

class Compteur extends \Twig_Extension
{
    protected $em;

    /**
     * Compteur constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getTotalMusic()
    {
        $musicRepo = $this->em->getRepository(Music::class);
        return $musicRepo->getTotalMusic();
    }

    /**
     * @return mixed
     */
    public function getTotalGames()
    {
        $musicRepo = $this->em->getRepository(Music::class);
        return $musicRepo->getTotalStyle('G');
    }

    /**
     * @return mixed
     */
    public function getTotalTrap()
    {
        $musicRepo = $this->em->getRepository(Music::class);
        return $musicRepo->getTotalStyle('T');
    }

    /**
     * @return mixed
     */
    public function getTotalElectro()
    {
        $musicRepo = $this->em->getRepository(Music::class);
        return $musicRepo->getTotalStyle('E');
    }

    /**
     * @return mixed
     */
    public function getTotalDubstep()
    {
        $musicRepo = $this->em->getRepository(Music::class);
        return $musicRepo->getTotalStyle('D');
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            'getTotalMusic' => new Twig_SimpleFunction('getTotalMusic', [$this, 'getTotalMusic']),
            'getTotalGames' => new Twig_SimpleFunction('getTotalGames', [$this, 'getTotalGames']),
            'getTotalTrap' => new Twig_SimpleFunction('getTotalTrap', [$this, 'getTotalTrap']),
            'getTotalElectro' => new Twig_SimpleFunction('getTotalElectro', [$this, 'getTotalElectro']),
            'getTotalDubstep' => new Twig_SimpleFunction('getTotalDubstep', [$this, 'getTotalDubstep'])
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Compteur';
    }
} 