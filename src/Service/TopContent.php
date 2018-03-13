<?php

namespace App\Service;

use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Twig_SimpleFunction;

class TopContent extends \Twig_Extension
{
    /** @var EntityManagerInterface */
    protected $em;

    /**
     * TopContent constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getTopPlusme()
    {
        $projetRepo = $this->em->getRepository(Projet::class);
        $projets = $projetRepo->findTopPlusme();
        foreach ($projets as $item) {
            foreach ($item->getAvis() as $value) {
                if ($value->getPouce() === 'M') {
                    $item->removeAvis($value);
                }
            }
        }
        return $projets;
    }

    /**
     * @return mixed
     */
    public function getTopComment()
    {
        $projectRepo = $this->em->getRepository(Projet::class);
        return $projectRepo->findTopComment();
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            'getTopPlusme' => new Twig_SimpleFunction('getTopPlusme', [$this, 'getTopPlusme']),
            'getTopComment' => new Twig_SimpleFunction('getTopComment', [$this, 'getTopComment'])
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'TopContent';
    }
}
