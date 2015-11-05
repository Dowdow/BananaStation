<?php

namespace BananaStation\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MusicController extends Controller
{
    public function homeAction() {
        return $this->render('BananaStationMusicBundle:Page:index.html.twig');
    }

    public function gamesAction() {
        return $this->process('G');
    }

    public function hiphopAction() {
        return $this->process('H');
    }

    public function moviesAction() {
        return $this->process('M');
    }

    public function electroAction() {
        return $this->process('E');
    }

    public function dubstepAction() {
        return $this->process('D');
    }

    public function rockAction() {
        return $this->process('R');
    }

    private function process($style) {
        $musicRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationMusicBundle:Music');
        $musics = $musicRepo->findByStyleOrder($style, 0);
        return $this->render('BananaStationMusicBundle:Page:music.html.twig', array('musics' => $musics, 'style' => $style));
    }

    public function pageAction($style, $page) {
        $begin = ($page - 1) * 20;
        if($begin < 0) {
            throw new NotFoundHttpException();
        }

        $musicRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationMusicBundle:Music');
        $musics = $musicRepo->findByStyleOrder($style, $begin);
        return $this->render('BananaStationMusicBundle:Json:music.json.twig',
            array('musics' => $musics, 'style' => $style),
            new Response('application/json',200, array('Content-Type' => 'application/json')));
    }

    public function searchAction() {
        $request = $this->get('request');

        if(!$request->request->get('search')){
            if(!$request->headers->get('referer')) {
                return $this->redirect($this->generateUrl('banana_station_music_racine'));
            }
            if(substr($request->headers->get('referer'), -strlen('/music/search')) === '/music/search') {
                return $this->redirect($this->generateUrl('banana_station_music_racine'));
            }
            return $this->redirect($request->headers->get('referer'));
        }
        $query = $request->request->get('search');
        $musicRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationMusicBundle:Music');
        $musics = $musicRepo->findByTitleSearch($query);

        return $this->render('BananaStationMusicBundle:Page:search.html.twig', array('musics' => $musics));
    }
}
