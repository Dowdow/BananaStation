<?php

namespace BananaStation\TronBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TronController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        return $this->render('BananaStationTronBundle::index.html.twig',
            array('server' => $this->container->getParameter('node_tron'))
        );
    }
}
