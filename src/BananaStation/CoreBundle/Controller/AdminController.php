<?php

namespace BananaStation\CoreBundle\Controller;

use BananaStation\CoreBundle\Entity\Projet;
use BananaStation\CoreBundle\Form\NoteType;
use BananaStation\CoreBundle\Form\ProjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminAction() {
        return $this->render('BananaStationCoreBundle::admin.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function projectAddAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $project = new Projet();
        $form = $this->createForm(ProjetType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setSlug($this->get('banana_station_core.slugger')->slugify($project->getNom()));
            $project->setUtilisateur($user);
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', array('slug' => $project->getSlug())));
        }


        return $this->render('BananaStationCoreBundle::formproject.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function projectEditAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('BananaStationCoreBundle:Projet')->findOneById($id);
        if ($project == null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ProjetType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setSlug($this->get('banana_station_core.slugger')->slugify($project->getNom()));
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', array('slug' => $project->getSlug())));
        }

        return $this->render('BananaStationCoreBundle::formproject.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function projectDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('BananaStationCoreBundle:Projet')->findOneById($id);
        if ($project == null) {
            throw new NotFoundHttpException();
        }

        if ($request->getMethod() == 'POST') {
            $em->remove($project);
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_projects', array('id' => 1)));
        }

        return $this->render('BananaStationCoreBundle::deleteproject.html.twig', array('project' => $project));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function noteEditAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('BananaStationCoreBundle:Note')->findOneById($id);
        if ($note == null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $note->getProjet()->getId())));
        }

        return $this->render('BananaStationCoreBundle::formnote.html.twig', array('form' => $form->createView()));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function noteDeleteAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('BananaStationCoreBundle:Note')->findOneById($id);
        if ($note == null) {
            throw new NotFoundHttpException();
        }

        if ($request->getMethod() == 'POST') {
            $projetid = $note->getProjet()->getId();
            $em->remove($note);
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $projetid)));
        }

        return $this->render('BananaStationCoreBundle::deletenote.html.twig', array('note' => $note));
    }
}
