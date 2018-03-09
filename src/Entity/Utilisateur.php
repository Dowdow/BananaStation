<?php

namespace App\Entity;

use App\Entity\Avis;
use App\Entity\Commentaire;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Utilisateur
 *
 * @ORM\Table(name="banana_utilisateur")
 * @ORM\Entity(repositoryClass="App\Entity\Repository\UtilisateurRepository")
 */
class Utilisateur implements UserInterface {

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
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(min="6",
     *                max="30",
     *                minMessage="Votre nom d'utilisateur doit faire au moins {{ limit }} caractères",
     *                maxMessage="Votre nom d'utilisateur ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\Length(min="8",
     *                max="30",
     *                minMessage="Votre mot de passe doit faire au moins {{ limit }} caractères",
     *                maxMessage="Votre mot de passe ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="salt", type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="string", length=1, nullable=false)
     */
    private $roles;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", nullable=false)
     */
    private $token;

    /**
     * @Recaptcha\IsTrue
     */
    public $recaptcha;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentaire", mappedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avis", mappedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $avis;

    /**
     * Constructeur
     */
    public function __construct() {
        $this->date = new \DateTime();
        $this->roles = 'U';
        $this->token = '0';
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
     * Set username
     *
     * @param string $username
     * @return Utilisateur
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Utilisateur
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Utilisateur
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Utilisateur
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return Utilisateur
     */
    public function setRoles($roles) {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     * @return Role[] The user roles
     */
    public function getRoles() {
        $role = '';
        switch ($this->roles) {
            case 'A':
                $role = 'ROLE_ADMIN';
                break;
            case 'C':
                $role = 'ROLE_CORE';
                break;
            case 'M':
                $role = 'ROLE_MUSIC';
                break;
            case 'G':
                $role = 'ROLE_GAMES';
                break;
            case 'U':
                $role = 'ROLE_USER';
                break;
            default :
                break;
        }
        return [$role];
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Utilisateur
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
     * Set token
     *
     * @param string $token
     * @return Utilisateur
     */
    public function setToken($token) {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * Add commentaires
     *
     * @param Commentaire $commentaires
     * @return Utilisateur
     */
    public function addCommentaire(Commentaire $commentaires) {
        $this->commentaires[] = $commentaires;

        return $this;
    }

    /**
     * Remove commentaires
     *
     * @param Commentaire $commentaires
     */
    public function removeCommentaire(Commentaire $commentaires) {
        $this->commentaires->removeElement($commentaires);
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
     * Add avis
     *
     * @param Avis $avis
     * @return Utilisateur
     */
    public function addAvi(Avis $avis) {
        $this->avis[] = $avis;

        return $this;
    }

    /**
     * Remove avis
     *
     * @param Avis $avis
     */
    public function removeAvi(Avis $avis) {
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

    // USER INTERFACE IMPLEMENTATION

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {

    }
}
