<?php

namespace BananaStation\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    public function homeAction(){
        return $this->render('BananaStationCoreBundle::home.html.twig');
    }
}