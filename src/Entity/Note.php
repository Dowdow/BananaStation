<?php

namespace App\Entity;

use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Note
 *
 * @ORM\Table(name="banana_note")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\NoteRepository")
 */
class Note {

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
     * @ORM\Column(name="contenu", type="text", nullable=false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @var Projet
     *
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="notes")
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
     * Set contenu
     *
     * @param string $contenu
     * @return Note
     */
    public function setContenu($contenu) {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu() {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Note
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
     * @param Utilisateur $utilisateur
     * @return Note
     */
    public function setUtilisateur(Utilisateur $utilisateur) {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return Utilisateur
     */
    public function getUtilisateur() {
        return $this->utilisateur;
    }

    /**
     * Set projet
     *
     * @param Projet $projet
     * @return Note
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
