<?php

namespace BananaStation\MusicBundle\Controller;

use BananaStation\MusicBundle\Entity\Music;
use BananaStation\MusicBundle\Form\MusicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller  {

    public function adminAction() {
        return $this->render('BananaStationMusicBundle:Page:admin.html.twig');
    }

    public function addAction(Request $request) {
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getDoctrine()->getManager()->persist($music);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectByStyle($music->getStyle());
            }
        }

        return $this->render('BananaStationMusicBundle:Page:form.html.twig', array('form' => $form->createView()));
    }

    public function editAction(Request $request, $id) {
        $musicRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationMusicBundle:Music');
        $music = $musicRepo->findOneById($id);

        if($music === null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(MusicType::class, $music);

        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectByStyle($music->getStyle());
            }
        }

        return $this->render('BananaStationMusicBundle:Page:form.html.twig', array('form' => $form->createView()));
    }

    public function deleteAction(Request $request, $id) {
        $musicRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationMusicBundle:Music');
        $music = $musicRepo->findOneById($id);

        if($music === null) {
            throw new NotFoundHttpException();
        }

        if($request->getMethod() == 'POST') {
            $style = $music->getStyle();
            $this->getDoctrine()->getManager()->remove($music);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectByStyle($style);
        }

        return $this->render('BananaStationMusicBundle:Page:form.html.twig', array('form' => null));
    }

    private function redirectByStyle($style) {
        switch($style) {
            case 'G':
                return $this->redirect($this->generateUrl('banana_station_music_music_games'));
            case 'H':
                return $this->redirect($this->generateUrl('banana_station_music_music_hiphop'));
            case 'M':
                return $this->redirect($this->generateUrl('banana_station_music_music_movies'));
            case 'E':
                return $this->redirect($this->generateUrl('banana_station_music_music_electro'));
            case 'D':
                return $this->redirect($this->generateUrl('banana_station_music_music_dubstep'));
            case 'R':
                return $this->redirect($this->generateUrl('banana_station_music_music_rock'));
        }
        return $this->redirect($this->generateUrl('banana_station_music_racine'));
    }
} 