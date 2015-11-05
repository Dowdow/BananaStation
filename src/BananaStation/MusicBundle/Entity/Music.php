<?php

namespace BananaStation\MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Music
 *
 * @ORM\Table(name="music_music")
 * @ORM\Entity(repositoryClass="BananaStation\MusicBundle\Entity\Repository\MusicRepository")
 */
class Music
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="youtubeid", type="text")
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $youtubeid;

    /**
     * @var string
     *
     * @ORM\Column(name="style", type="string", length=1)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $style;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Music
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set youtubeid
     *
     * @param string $youtubeid
     * @return Music
     */
    public function setYoutubeid($youtubeid)
    {
        $this->youtubeid = $youtubeid;

        return $this;
    }

    /**
     * Get youtubeid
     *
     * @return string
     */
    public function getYoutubeid()
    {
        return $this->youtubeid;
    }

    /**
     * Set style
     *
     * @param string $style
     * @return Music
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Music
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
