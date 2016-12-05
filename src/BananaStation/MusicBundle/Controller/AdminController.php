<?php

namespace BananaStation\MusicBundle\Controller;

use BananaStation\MusicBundle\Entity\Music;
use BananaStation\MusicBundle\Form\MusicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminAction() {
        return $this->render('BananaStationMusicBundle:Page:admin.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($music);
            $em->flush();
            return $this->redirectByStyle($music->getStyle());
        }

        return $this->render('BananaStationMusicBundle:Page:form.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $music = $em->getRepository('BananaStationMusicBundle:Music')->findOneById($id);
        if ($music === null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectByStyle($music->getStyle());
        }

        return $this->render('BananaStationMusicBundle:Page:form.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $music = $em->getRepository('BananaStationMusicBundle:Music')->findOneById($id);

        if ($music === null) {
            throw new NotFoundHttpException();
        }

        if ($request->getMethod() == 'POST') {
            $style = $music->getStyle();
            $em->remove($music);
            $em->flush();
            return $this->redirectByStyle($style);
        }

        return $this->render('BananaStationMusicBundle:Page:form.html.twig', array('form' => null));
    }

    /**
     * @param $style
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectByStyle($style) {
        switch ($style) {
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