<?php

namespace App\Controller;

use App\Entity\Music;
use App\Form\MusicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MusicAdminController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_music%")
 */
class MusicAdminController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin", name="music_admin")
     */
    public function adminAction()
    {
        return $this->render('music/Page/admin.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/add", name="music_admin_add")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $music = new Music();
        $form = $this->createForm(MusicType::class, $music);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($music);
            $em->flush();
            return $this->redirectByStyle($music->getStyle());
        }

        return $this->render('music/Page/form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/edit/{id}", name="music_admin_edit", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $music = $em->getRepository(Music::class)->findOneById($id);
        if ($music === null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(MusicType::class, $music);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectByStyle($music->getStyle());
        }

        return $this->render('music/Page/form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/delete/{id}", name="music_admin_delete", requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $music = $em->getRepository(Music::class)->findOneById($id);

        if ($music === null) {
            throw new NotFoundHttpException();
        }

        if ($request->getMethod() === 'POST') {
            $style = $music->getStyle();
            $em->remove($music);
            $em->flush();
            return $this->redirectByStyle($style);
        }

        return $this->render('music/Page/form.html.twig', ['form' => null]);
    }

    /**
     * @param $style
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectByStyle($style)
    {
        switch ($style) {
            case 'G':
                return $this->redirect($this->generateUrl('music_music_games'));
            case 'T':
                return $this->redirect($this->generateUrl('music_music_trap'));
            case 'E':
                return $this->redirect($this->generateUrl('music_music_electro'));
            case 'D':
                return $this->redirect($this->generateUrl('music_music_dubstep'));
        }
        return $this->redirect($this->generateUrl('music_racine'));
    }
} 