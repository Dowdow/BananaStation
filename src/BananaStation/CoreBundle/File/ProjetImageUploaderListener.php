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
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();
        if (!$entity instanceof Projet) {
            return;
        }
        if ($entity->getImage() == null) {
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
        $oldImage = $args->getOldValue('image');
        if ($entity->getImage() == null) {
            if ($oldImage != '') {
                $entity->setImage($oldImage);
                return;
            }
            $entity->setImage('');
            return;
        }
        if ($oldImage != '') {
            $this->deleteFile($entity->getId(), $oldImage);
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
        $this->deleteFile($entity->getId(), $entity->getImage());
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
     * @param $ext
     */
    private function deleteFile($id, $ext) {
        $file = $this->directory . DIRECTORY_SEPARATOR . $id . '.' . $ext;
        if (file_exists($file)) {
            unlink($file);
        }
    }

}