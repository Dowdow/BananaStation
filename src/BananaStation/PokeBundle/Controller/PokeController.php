<?php

namespace BananaStation\PokeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PokeController extends Controller
{
    public function indexAction()
    {
        return $this->render('BananaStationPokeBundle::index.html.twig',
            array('server' => $this->container->getParameter('node_poke'))
        );
    }
}