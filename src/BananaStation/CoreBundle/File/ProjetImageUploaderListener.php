<?php

namespace BananaStation\CoreBundle\File;

use BananaStation\CoreBundle\Entity\Projet;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProjetImageUploaderListener {

    /**
     * @var UploadedFile
     */
    private $file;
    /**
     * @var string
     */
    private $directory = 'public/img/core/projet';
    /**
     * @var array
     */
    private $formats = ['png', 'jpg', 'jpeg', 'svg', 'gif'];

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof Projet) {
            return;
        }
        if ($entity->getImage() == null) {
            $this->tryRemove($entity->getId());
            $entity->setImage('');
            return;
        }
        $this->file = $entity->getImage();
        $entity->setImage($this->file->guessExtension());
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args) {
        if ($this->file == null) {
            return;
        }
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof Projet) {
            return;
        }
        if ($entity->getImage() == null) {
            return;
        }
        $this->file = $entity->getImage();
        $entity->setImage($this->file->guessExtension());

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args) {
        if ($this->file == null) {
            return;
        }
        $entity = $args->getEntity();
        $this->uploadFile($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof Projet) {
            return;
        }
        if ($entity->getImage() == null) {
            $this->tryRemove($entity->getId());
            return;
        }
        $file = $this->directory . DIRECTORY_SEPARATOR . $entity->getId() . '.' . $entity->getImage();
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * @param $entity
     */
    private function uploadFile($entity) {
        if (!$entity instanceof Projet || !$this->file instanceof UploadedFile) {
            return;
        }
        $this->file->move($this->directory, $entity->getId() . '.' . $entity->getImage());
    }

    /**
     * @param $id
     */
    private function tryRemove($id) {
        foreach ($this->formats as $f) {
            $path = $this->directory . DIRECTORY_SEPARATOR . $id . '.' . $f;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

}