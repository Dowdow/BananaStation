<?php

namespace App\Entity;

use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;

/**
 * Avis
 *
 * @ORM\Table(name="banana_avis")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\AvisRepository")
 */
class Avis {

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
     * Constructor
     */
    public function __construct() {
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set pouce
     *
     * @param string $pouce
     * @return Avis
     */
    public function setPouce($pouce) {
        $this->pouce = $pouce;

        return $this;
    }

    /**
     * Get pouce
     *
     * @return string
     */
    public function getPouce() {
        return $this->pouce;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Avis
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

  /**
   * Set utilisateur
   *
   * @param \App\Entity\Utilisateur $utilisateur
   *
   * @return Avis
   */
    public function setUtilisateur(Utilisateur $utilisateur) {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \App\Entity\Utilisateur
     */
    public function getUtilisateur() {
        return $this->utilisateur;
    }

    /**
     * Set projet
     *
     * @param Projet $projet
     * @return Avis
     */
    public function setProjet(Projet $projet) {
        $this->projet = $projet;

        return $this;
    }

    /**
     * Get projet
     *
     * @return Projet
     */
    public function getProjet() {
        return $this->projet;
    }

}
