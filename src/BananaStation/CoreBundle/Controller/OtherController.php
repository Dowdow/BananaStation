<?php

namespace BananaStation\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OtherController extends Controller{

    public function aboutAction(){
        return $this->render('BananaStationCoreBundle::about.html.twig');
    }

} 