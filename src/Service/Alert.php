<?php

namespace App\Service;

/**
 * Classe de message d'alerte
 */
class Alert
{
    public const TYPE_GOOD = 'alert good';
    public const TYPE_BAD = 'alert bad';

    /**
     * Type de l'alerte
     * @var string
     */
    private $type;

    /**
     * Message de l'alerte
     * @var string
     */
    private $message;

    /**
     * Construit une alerte
     * @param string $type
     * @param string $message
     */
    public function build($type, $message): void
    {
        $this->setType($type);
        $this->setMessage($message);
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }

}
