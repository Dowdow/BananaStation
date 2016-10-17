<?php

namespace BananaStation\CoreBundle\Entity;

use BananaStation\UserBundle\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Projet
 *
 * @ORM\Table(name="banana_projet")
 * @ORM\Entity(repositoryClass="BananaStation\CoreBundle\Entity\Repository\ProjetRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Projet {

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
     * @ORM\ManyToOne(targetEntity="BananaStation\UserBundle\Entity\Utilisateur")
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
     * @ORM\OneToMany(targetEntity="Note", mappedBy="projet", cascade={"persist", "remove"})
     */
    private $notes;

    /**
     * @Assert\Image()
     */
    private $file;

    private $tempFilename;

    /**
     * Constructor
     */
    public function __construct() {
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
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Projet
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Projet
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Projet
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
     * Set image
     *
     * @param string $image
     * @return Projet
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set etat
     *
     * @param string $etat
     * @return Projet
     */
    public function setEtat($etat) {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat() {
        return $this->etat;
    }

    /**
     * Set progression
     *
     * @param integer $progression
     * @return Projet
     */
    public function setProgression($progression) {
        $this->progression = $progression;

        return $this;
    }

    /**
     * Get progression
     *
     * @return integer
     */
    public function getProgression() {
        return $this->progression;
    }

    /**
     * Set utilisateur
     *
     * @param Utilisateur $utilisateur
     * @return Projet
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
     * Add avis
     *
     * @param Avis $avis
     * @return Projet
     */
    public function addAvis(Avis $avis) {
        $this->avis[] = $avis;
        $avis->setProjet($this);
        return $this;
    }

    /**
     * Remove avis
     *
     * @param Avis $avis
     */
    public function removeAvis(Avis $avis) {
        $this->avis->removeElement($avis);
    }

    /**
     * Get avis
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAvis() {
        return $this->avis;
    }

    /**
     * Add commentaires
     *
     * @param Commentaire $commentaire
     * @return Projet
     */
    public function addCommentaire(Commentaire $commentaire) {
        $this->commentaires[] = $commentaire;
        $commentaire->setProjet($this);
        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param Commentaire $commentaire
     */
    public function removeCommentaire(Commentaire $commentaire) {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires() {
        return $this->commentaires;
    }

    /**
     * Add notes
     *
     * @param Note $notes
     * @return Projet
     */
    public function addNote(Note $notes) {
        $this->notes[] = $notes;
        $notes->setProjet($this);
        return $this;
    }

    /**
     * Remove notes
     *
     * @param Note $notes
     */
    public function removeNote(Note $notes) {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes() {
        return $this->notes;
    }

    // FILE FUNCTIONS

    /**
     * Get file
     *
     * @return mixed
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file) {
        $this->file = $file;
        if (null !== $this->image) {
            $this->tempFilename = $this->image;
            $this->image = null;
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->file) {
            $this->image = $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        if (null !== $this->tempFilename) {
            $oldFile = $this->getUploadRootDir().'/'.$this->id.'.'.$this->tempFilename;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->file->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->image
        );

        $this->file = null;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload() {
        $this->tempFilename = $this->getUploadRootDir().'/'.$this->id.'.'.$this->image;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if (file_exists($this->tempFilename)) {
            unlink($this->tempFilename);
        }
    }

    /**
     * Get the relative upload dir
     * @return string
     */
    public function getUploadDir() {
        return 'public/img/core/projet';
    }

    /**
     * Get the absolute upload dir
     * @return string
     */
    protected function getUploadRootDir() {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

}
