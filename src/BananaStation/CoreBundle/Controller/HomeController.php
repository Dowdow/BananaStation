<?php

namespace BananaStation\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction(){
        return $this->render('BananaStationCoreBundle::home.html.twig');
    }
}