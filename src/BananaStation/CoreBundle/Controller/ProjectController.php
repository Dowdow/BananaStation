<?php

namespace BananaStation\CoreBundle\Controller;

use BananaStation\CoreBundle\Entity\Avis;
use BananaStation\CoreBundle\Entity\Commentaire;
use BananaStation\CoreBundle\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BananaStation\CoreBundle\Entity\Note;
use BananaStation\CoreBundle\Form\NoteType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectController extends Controller {

    public function projectAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $projectRepo = $em->getRepository('BananaStationCoreBundle:Projet');

        $project = $projectRepo->findOneById($id);
        if($project == null) {
            throw new NotFoundHttpException();
        }

        switch ($project->getEtat()) {
            case 'E':
                $project->setEtat('En cours');
                break;
            case 'T':
                $project->setEtat('Terminé');
                break;
            case 'P':
                $project->setEtat('En pause');
                break;
        }

        // Création du formulaire de commentaire
        $commentaire = new Commentaire();
        $formcom = $this->createForm(CommentaireType::class, $commentaire);
        // Création du formulaire de note
        $note = new Note();
        $formnote = $this->createForm(NoteType::class, $note);

        $utilisateur = $this->getUser();

        if ($request->getMethod() == 'POST') {

            if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
                // Gestion de l'avis
                if ($request->get('plusme') != null || $request->get('moinsme') != null) {
                    if ($request->get('plusme') != null) {
                        $pouce = 'P';
                    } else {
                        $pouce = 'M';
                    }
                    $avisRepo = $em->getRepository('BananaStationCoreBundle:Avis');
                    $avis = $avisRepo->findOneByUtilisateurProjet($utilisateur, $project);
                    if (NULL == $avis) {
                        $avis = new Avis();
                        $avis->setPouce($pouce);
                        $avis->setprojet($project);
                        $avis->setUtilisateur($utilisateur);
                        $em->persist($avis);
                        $em->flush();
                    } else {
                        if ($avis->getPouce() != $pouce) {
                            $avis->setPouce($pouce);
                            $em->flush();
                        }
                    }
                }

                // Gestion du commentaire
                $formcom->handleRequest($request);
                if ($formcom->isValid()) {
                    $commentaire->setprojet($project);
                    $commentaire->setUtilisateur($this->getUser());
                    $em->persist($commentaire);
                    $em->flush();
                    return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $id)));
                }
            }
            // Gestion de la note
            if ($this->get('security.authorization_checker')->isGranted('ROLE_CORE')) {
                $formnote->handleRequest($request);
                if ($formnote->isValid()) {
                    $note->setProjet($project);
                    $note->setUtilisateur($utilisateur);
                    $em->persist($note);
                    $em->flush();
                    return $this->redirect($this->generateUrl('banana_station_core_project',array('id' => $id)));
                }
            }
        }

        $plusmes = 0;
        $moinsmes = 0;
        if ($project != null) {
            foreach ($project->getAvis() as $value) {
                if ($value->getPouce() == 'P') {
                    $plusmes++;
                } else {
                    $moinsmes++;
                }
            }
        }

        return $this->render('BananaStationCoreBundle::project.html.twig', array('projet' => $project, 'plusmes' => $plusmes, 'moinsmes' => $moinsmes, 'formcom' => $formcom->createView(), 'formnote' => $formnote->createView()));
    }

    public function projectsAction() {
        $em = $this->getDoctrine()->getManager();
        $projectRepo = $em->getRepository('BananaStationCoreBundle:Projet');
        $projects = $projectRepo->findBy(array(), array('id' => 'DESC'));
        return $this->render('BananaStationCoreBundle::projects.html.twig', array('projets' => $projects));
    }

    public function editCommentaireAction(Request $request, $id, $idcom) {
        if($this->get('security.authorization_checker')->isGranted('ROLE_USER') == false) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $projetRepo = $em->getRepository('BananaStationCoreBundle:Projet');
        $commentRepo = $em->getRepository('BananaStationCoreBundle:Commentaire');
        $user = $this->getUser();
        $projet = $projetRepo->findOneById($id);
        $commentaire = $commentRepo->findOneById($idcom);
        if($projet == null || $commentaire == null) {
            throw new NotFoundHttpException();
        }
        if($user->getId() != $commentaire->getUtilisateur()->getId()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        if($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $em->flush();
                return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $id)));
            }
        }
        return $this->render('BananaStationCoreBundle::formcommentaire.html.twig', array('form' => $form->createView()));
    }

    public function deleteCommentaireAction(Request $request, $id, $idcom) {
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $projetRepo = $em->getRepository('BananaStationCoreBundle:Projet');
        $commentRepo = $em->getRepository('BananaStationCoreBundle:Commentaire');
        $user = $this->getUser();
        $projet = $projetRepo->findOneById($id);
        $commentaire = $commentRepo->findOneById($idcom);
        if($projet == null || $commentaire == null) {
            throw new NotFoundHttpException();
        }
        if($user->getId() != $commentaire->getUtilisateur()->getId() && $this->get('security.authorization_checker')->isGranted('ROLE_CORE') == false) {
            throw new AccessDeniedException();
        }
        if($request->getMethod() == 'POST') {
            var_dump('Supprimer');
            $em->remove($commentaire);
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', array('id' => $id)));
        }
        return $this->render('BananaStationCoreBundle::deletecommentaire.html.twig', array('commentaire' => $commentaire));
    }

}
