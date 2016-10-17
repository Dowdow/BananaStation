<?php

namespace BananaStation\CoreBundle\Controller;

use BananaStation\CoreBundle\Entity\Projet;
use BananaStation\CoreBundle\Form\NoteType;
use BananaStation\CoreBundle\Form\ProjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller {

    public function adminAction() {
        return $this->render('BananaStationCoreBundle::admin.html.twig');
    }

    public function projectAddAction(Request $request) {
        $user = $this->getUser();

        $project = new Projet();
        $form = $this->createForm(ProjetType::class, $project);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $project->setUtilisateur($user);
                $this->getDoctrine()->getManager()->persist($project);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('banana_station_core_project',array('id' => $project->getId())));
            }
        }

        return $this->render('BananaStationCoreBundle::formproject.html.twig', array('form' => $form->createView()));
    }

    public function projectEditAction(Request $request, $id) {
        $projectRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationCoreBundle:Projet');
        $project = $projectRepo->findOneById($id);
        if($project == null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ProjetType::class, $project);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $project->getId())));
            }
        }

        return $this->render('BananaStationCoreBundle::formproject.html.twig', array('form' => $form->createView()));
    }

    public function projectDeleteAction(Request $request, $id) {
        $projectRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationCoreBundle:Projet');
        $project = $projectRepo->findOneById($id);
        if($project == null) {
            throw new NotFoundHttpException();
        }

        if($request->getMethod() == 'POST') {
            $this->getDoctrine()->getManager()->remove($project);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('banana_station_core_projects', array('id' => 1)));
        }

        return $this->render('BananaStationCoreBundle::deleteproject.html.twig', array('project' => $project));
    }

    public function noteEditAction(Request $request, $id) {
        $noteRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationCoreBundle:Note');
        $note = $noteRepo->findOneById($id);
        if($note == null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(NoteType::class, $note);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $note->getProjet()->getId())));
            }
        }

        return $this->render('BananaStationCoreBundle::formnote.html.twig', array('form' => $form->createView()));
    }

    public function noteDeleteAction(Request $request, $id) {
        $noteRepo = $this->getDoctrine()->getManager()->getRepository('BananaStationCoreBundle:Note');
        $note = $noteRepo->findOneById($id);
        if($note == null) {
            throw new NotFoundHttpException();
        }

        if($request->getMethod() == 'POST') {
            $projetid = $note->getProjet()->getId();
            $this->getDoctrine()->getManager()->remove($note);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $projetid)));
        }

        return $this->render('BananaStationCoreBundle::deletenote.html.twig', array('note' => $note));
    }
}
