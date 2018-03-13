<?php

namespace App\Service;

use App\Entity\Avis;
use App\Entity\Commentaire;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

class StatAdmin extends \Twig_Extension
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * StatAdmin constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getNumberUser()
    {
        $userRepo = $this->em->getRepository(Utilisateur::class);
        return $userRepo->findNumberUser();
    }

    /**
     * @return mixed
     */
    public function getNumberPlusme()
    {
        $avisRepo = $this->em->getRepository(Avis::class);
        return $avisRepo->findNumberPlusme();
    }

    /**
     * @return mixed
     */
    public function getNumberMoinsme()
    {
        $avisRepo = $this->em->getRepository(Avis::class);
        return $avisRepo->findNumberMoisme();
    }

    /**
     * @return mixed
     */
    public function getNumberComment()
    {
        $commentRepo = $this->em->getRepository(Commentaire::class);
        return $commentRepo->findNumberComment();
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            'getNumberUser' => new \Twig_SimpleFunction('getNumberUser', [$this, 'getNumberUser']),
            'getNumberPlusme' => new \Twig_SimpleFunction('getNumberPlusme', [$this, 'getNumberPlusme']),
            'getNumberMoinsme' => new \Twig_SimpleFunction('getNumberMoinsme', [$this, 'getNumberMoinsme']),
            'getNumberComment' => new \Twig_SimpleFunction('getNumberComment', [$this, 'getNumberComment'])
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'StatAdmin';
    }
}