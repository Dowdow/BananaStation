<?php

namespace BananaStation\NotifierBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NotifierController extends Controller
{
    public function indexAction()
    {
        return $this->render('BananaStationNotifierBundle::index.html.twig');
    }
}
