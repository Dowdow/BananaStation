<?php

namespace App\Controller;

use App\Entity\Music;
use App\Form\MusicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Response
     *
     * @Route("/admin", name="music_admin")
     */
    public function admin(): Response
    {
        return $this->render('music/Page/admin.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws \Symfony\Component\Form\Exception\LogicException
     *
     * @Route("/admin/add", name="music_admin_add")
     */
    public function add(Request $request)
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
     *
     * @return RedirectResponse|Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Form\Exception\LogicException
     *
     * @Route("/admin/edit/{id}", name="music_admin_edit", requirements={"id"="\d+"})
     */
    public function edit(Request $request, $id)
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
     *
     * @return RedirectResponse|Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/admin/delete/{id}", name="music_admin_delete", requirements={"id"="\d+"})
     */
    public function delete(Request $request, $id)
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
     * @return RedirectResponse
     */
    private function redirectByStyle($style): RedirectResponse
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
        return $this->redirect($this->generateUrl('music_index'));
    }
} 