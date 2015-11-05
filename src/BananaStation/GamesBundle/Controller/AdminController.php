<?php

namespace BananaStation\GamesBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

    public function adminAction() {
        return $this->render('BananaStationGamesBundle:Page:admin.html.twig');
    }

} 