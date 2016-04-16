<?php

namespace BananaStation\CoreBundle\Service;

use Doctrine\ORM\EntityManager;

class Recent extends \Twig_Extension {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getRecent() {
        $content = array();
        $date = new \DateTime();

        $avisRepo = $this->em->getRepository('BananaStationCoreBundle:Avis');
        $avis = $avisRepo->findBy(array(), array('date' => 'DESC'), 10);
        $commentRepo = $this->em->getRepository('BananaStationCoreBundle:Commentaire');
        $comments = $commentRepo->findBy(array(), array('date' => 'DESC'), 10);
        $noteRepo = $this->em->getRepository('BananaStationCoreBundle:Note');
        $notes =$noteRepo->findBy(array(), array('date' => 'DESC'), 10);
        $projetRepo = $this->em->getRepository('BananaStationCoreBundle:Projet');
        $projets = $projetRepo->findBy(array(), array('date' => 'DESC'), 10);

        foreach($avis as $value) {
            $content[] = $this->createContentArray($value, 'avis');
            if($value->getDate()->diff($date)->format('%R%Y%M%D%H%I%S') > '0') {
                $date = $value->getDate();
            }
        }

        $content = $this->filter($content, $comments, 'commentaire');
        $content = $this->filter($content, $notes, 'note');
        $content = $this->filter($content, $projets, 'projet');

        return $content;
    }

    private function filter($content, $object, $name) {
        for($i = 0; $i < count($content); $i++) {
            foreach($object as $key => $value) {
                if($content[$i]['date']->diff($value->getDate())->format('%R%Y%M%D%H%I%S') > '0') {
                    $match = $this->createContentArray($value, $name);
                    $splice = array_splice($content,$i);
                    $contentf = $this->decaleDroite($splice, $match);
                    $content = array_merge($content,$contentf);
                    unset($object[$key]);
                }
            }
        }
        return array_splice($content, 0 ,10);
    }

    private function decaleDroite($tab, $value) {
        array_unshift($tab,$value);
        $tab = array_splice($tab,0,10);
        return $tab;
    }

    private function createContentArray($object, $name) {
        $id = null;
        $title = null;
        if($name == 'avis' || $name == 'commentaire') {
            $id = $object->getProjet()->getId();
            $title = $object->getProjet()->getTitre();
        } else if($name == 'news') {
            $id = $object->getId();
            $title = $object->getTitre();
        } else if($name == 'note') {
            $id = $object->getProjet()->getId();
            $title = $object->getProjet()->getNom();
        } else if($name == 'projet') {
            $id = $object->getId();
            $title = $object->getNom();
        }
        return array(
            'type' => $name,
            'id' => $id,
            'title' => $title,
            'user' => $object->getUtilisateur()->getUsername(),
            'date' => $object->getDate()
        );
    }

    public function getFunctions() {
        return array(
            'getRecent' => new \Twig_SimpleFunction('getRecent', array($this, 'getRecent'))
        );
    }

    public function getName() {
        return 'Recent';
    }
} 