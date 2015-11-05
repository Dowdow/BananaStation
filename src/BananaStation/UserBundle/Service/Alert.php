<?php

namespace BananaStation\UserBundle\Service;

/**
 * Classe de message d'alerte
 */
class Alert {

    // Constantes
    const TYPE_GOOD = 'alert-good';
    const TYPE_BAD = 'alert-bad';

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
    public function build($type, $message) {
        $this->setType($type);
        $this->setMessage($message);
    }
    
    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

}
