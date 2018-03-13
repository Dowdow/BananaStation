<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="banana_avis")
 * @ORM\Entity(repositoryClass="App\Repository\AvisRepository")
 */
class Avis
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pouce", type="string", length=1, nullable=false)
     */
    private $pouce;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="avis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @var Projet
     *
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="avis")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projet;

    /**
     * Avis constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set pouce
     *
     * @param string $pouce
     * @return Avis
     */
    public function setPouce($pouce): Avis
    {
        $this->pouce = $pouce;

        return $this;
    }

    /**
     * Get pouce
     *
     * @return string
     */
    public function getPouce(): ?string
    {
        return $this->pouce;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Avis
     */
    public function setDate($date): Avis
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * Set utilisateur
     *
     * @param Utilisateur $utilisateur
     *
     * @return Avis
     */
    public function setUtilisateur(Utilisateur $utilisateur): Avis
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return Utilisateur
     */
    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    /**
     * Set projet
     *
     * @param Projet $projet
     * @return Avis
     */
    public function setProjet(Projet $projet): Avis
    {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return Projet
     */
    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

}
