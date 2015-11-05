<?php

namespace BananaStation\CoreBundle\Controller;

use BananaStation\CoreBundle\Entity\News;
use BananaStation\CoreBundle\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    public function homeAction(){
        $em = $this->getDoctrine()->getManager();
        $projetRepo = $em->getRepository('BananaStationCoreBundle:Projet');

        $projet = $projetRepo->findLast();
        if($projet != null){
            switch($projet->getEtat()){
                case 'E':
                    $projet->setEtat('En cours');
                    break;
                case 'T':
                    $projet->setEtat('Terminé');
                    break;
                case 'P':
                    $projet->setEtat('En pause');
                    break;
            }
        }

        return $this->render('BananaStationCoreBundle::home.html.twig',array('projet' => $projet));
    }
}