<?php

namespace BananaStation\GamesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GamesController extends Controller
{
    public function indexAction() {
        return $this->render('BananaStationGamesBundle:Page:index.html.twig');
    }

    public function gamesAction() {
        return $this->render('BananaStationGamesBundle:Page:games.html.twig');
    }
}
