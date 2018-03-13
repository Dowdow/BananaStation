<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Projet
 *
 * @ORM\Table(name="banana_projet")
 * @ORM\Entity(repositoryClass="App\Repository\ProjetRepository")
 */
class Projet
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     * @Assert\Image()
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=1, nullable=false)
     */
    private $etat;

    /**
     * @var integer
     *
     * @ORM\Column(name="progression", type="integer", nullable=false)
     */
    private $progression;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Avis", mappedBy="projet", cascade={"persist", "remove"})
     */
    private $avis;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="projet", cascade={"persist", "remove"})
     */
    private $commentaires;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Note", mappedBy="projet", cascade={"persist", "remove"})
     */
    private $notes;

    /**
     * Projet constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->etat = 'E';
        $this->progression = 0;
        $this->image = '';
        $this->notes = new ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Projet
     */
    public function setNom($nom): Projet
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Projet
     */
    public function setDescription($description): Projet
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Projet
     */
    public function setDate($date): Projet
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
     * Set image
     *
     * @param string $image
     * @return Projet
     */
    public function setImage($image): Projet
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Set etat
     *
     * @param string $etat
     * @return Projet
     */
    public function setEtat($etat): Projet
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat(): ?string
    {
        return $this->etat;
    }

    /**
     * Set progression
     *
     * @param integer $progression
     * @return Projet
     */
    public function setProgression($progression): Projet
    {
        $this->progression = $progression;

        return $this;
    }

    /**
     * Get progression
     *
     * @return integer
     */
    public function getProgression(): ?int
    {
        return $this->progression;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Projet
     */
    public function setSlug($slug): Projet
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set utilisateur
     *
     * @param Utilisateur $utilisateur
     * @return Projet
     */
    public function setUtilisateur(Utilisateur $utilisateur): Projet
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
     * Add avis
     *
     * @param Avis $avis
     * @return Projet
     */
    public function addAvis(Avis $avis): Projet
    {
        $this->avis[] = $avis;
        $avis->setProjet($this);
        return $this;
    }

    /**
     * Remove avis
     *
     * @param Avis $avis
     */
    public function removeAvis(Avis $avis): void
    {
        $this->avis->removeElement($avis);
    }

    /**
     * Get avis
     *
     * @return Collection
     */
    public function getAvis(): ?Collection
    {
        return $this->avis;
    }

    /**
     * Add commentaires
     *
     * @param Commentaire $commentaire
     * @return Projet
     */
    public function addCommentaire(Commentaire $commentaire): Projet
    {
        $this->commentaires[] = $commentaire;
        $commentaire->setProjet($this);
        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param Commentaire $commentaire
     */
    public function removeCommentaire(Commentaire $commentaire): void
    {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return Collection
     */
    public function getCommentaires(): ?Collection
    {
        return $this->commentaires;
    }

    /**
     * Add notes
     *
     * @param Note $notes
     * @return Projet
     */
    public function addNote(Note $notes): Projet
    {
        $this->notes[] = $notes;
        $notes->setProjet($this);
        return $this;
    }

    /**
     * Remove notes
     *
     * @param Note $notes
     */
    public function removeNote(Note $notes): void
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return Collection
     */
    public function getNotes(): ?Collection
    {
        return $this->notes;
    }

}
