<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Music
 *
 * @ORM\Table(name="music_music")
 * @ORM\Entity(repositoryClass="App\Repository\MusicRepository")
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
     * Music constructor.
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
     * Set title
     *
     * @param string $title
     * @return Music
     */
    public function setTitle($title): Music
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set youtubeid
     *
     * @param string $youtubeid
     * @return Music
     */
    public function setYoutubeid($youtubeid): Music
    {
        $this->youtubeid = $youtubeid;

        return $this;
    }

    /**
     * Get youtubeid
     *
     * @return string
     */
    public function getYoutubeid(): ?string
    {
        return $this->youtubeid;
    }

    /**
     * Set style
     *
     * @param string $style
     * @return Music
     */
    public function setStyle($style): Music
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return string
     */
    public function getStyle(): ?string
    {
        return $this->style;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Music
     */
    public function setDate($date): Music
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
}
