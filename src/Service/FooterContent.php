<?php

namespace App\Service;

use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use Twig_SimpleFunction;

class FooterContent extends \Twig_Extension
{
    protected $em;

    /**
     * FooterContent constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return mixed
     */
    public function getFooterContentProjects()
    {
        $projectRepo = $this->em->getRepository(Projet::class);
        return $projectRepo->findLastThree();
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            'getFooterContentProjects' => new Twig_SimpleFunction('getFooterContentProjects', [$this, 'getFooterContentProjects'])
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'FooterContent';
    }
}
