<?php

namespace BananaStation\MusicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MusicController extends Controller {
    /**
     * @return Response
     */
    public function homeAction() {
        return $this->render('BananaStationMusicBundle:Page:index.html.twig');
    }

    /**
     * @return Response
     */
    public function gamesAction() {
        return $this->process('G');
    }

    /**
     * @return Response
     */
    public function trapAction() {
        return $this->process('T');
    }

    /**
     * @return Response
     */
    public function moviesAction() {
        return $this->process('M');
    }

    /**
     * @return Response
     */
    public function electroAction() {
        return $this->process('E');
    }

    /**
     * @return Response
     */
    public function dubstepAction() {
        return $this->process('D');
    }

    /**
     * @return Response
     */
    public function rockAction() {
        return $this->process('R');
    }

    /**
     * @param $style
     * @return Response
     */
    private function process($style) {
        $em = $this->getDoctrine()->getManager();
        $musics = $em->getRepository('BananaStationMusicBundle:Music')->findByStyleOrder($style, 0);
        return $this->render('BananaStationMusicBundle:Page:music.html.twig', ['musics' => $musics, 'style' => $style]);
    }

    /**
     * @param $style
     * @param $page
     * @return Response
     */
    public function pageAction($style, $page) {
        $em = $this->getDoctrine()->getManager();
        $begin = ($page - 1) * 20;
        if ($begin < 0) {
            throw new NotFoundHttpException();
        }

        $musics = $em->getRepository('BananaStationMusicBundle:Music')->findByStyleOrder($style, $begin);
        return $this->render('BananaStationMusicBundle:Json:music.json.twig',
            ['musics' => $musics, 'style' => $style],
            new Response('application/json', 200, ['Content-Type' => 'application/json'])
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function searchAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        if (!$request->request->get('search')) {
            if (!$request->headers->get('referer')) {
                return $this->redirect($this->generateUrl('banana_station_music_racine'));
            }
            if (substr($request->headers->get('referer'), -strlen('/music/search')) === '/music/search') {
                return $this->redirect($this->generateUrl('banana_station_music_racine'));
            }
            return $this->redirect($request->headers->get('referer'));
        }
        $query = $request->request->get('search');
        $musics = $em->getRepository('BananaStationMusicBundle:Music')->findByTitleSearch($query);

        return $this->render('BananaStationMusicBundle:Page:music.html.twig', ['musics' => $musics, 'style' => 'S']);
    }
}
