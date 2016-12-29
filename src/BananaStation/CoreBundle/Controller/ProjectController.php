<?php

namespace BananaStation\CoreBundle\Controller;

use BananaStation\CoreBundle\Entity\Avis;
use BananaStation\CoreBundle\Entity\Commentaire;
use BananaStation\CoreBundle\Form\CommentaireType;
use BananaStation\UserBundle\Service\Alert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use BananaStation\CoreBundle\Entity\Note;
use BananaStation\CoreBundle\Form\NoteType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProjectController extends Controller {

    /**
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectAction(Request $request, $slug) {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('BananaStationCoreBundle:Projet')->findOneBySlug($slug);
        $alert = $this->get('banana_station_user.alert');
        if ($project == null) {
            throw new NotFoundHttpException();
        }

        // Création du formulaire de commentaire
        $commentaire = new Commentaire();
        $formcom = $this->createForm(CommentaireType::class, $commentaire);
        // Création du formulaire de note
        $note = new Note();
        $formnote = $this->createForm(NoteType::class, $note);

        $utilisateur = $this->getUser();

        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            // Gestion de l'avis
            if ($request->get('plusme') != null) {
                $pouce = 'P';
            } elseif ($request->get('moinsme') != null) {
                $pouce = 'M';
            }
            if (isset($pouce)) {
                $avis = $em->getRepository('BananaStationCoreBundle:Avis')->findOneByUtilisateurProjet($utilisateur, $project);
                if ($avis == null) {
                    $avis = new Avis();
                    $avis->setPouce($pouce);
                    $avis->setprojet($project);
                    $avis->setUtilisateur($utilisateur);
                    $em->persist($avis);
                    $em->flush();
                } else {
                    $avis->setPouce($pouce);
                    $em->flush();
                }
            }

            // Gestion du commentaire
            $formcom->handleRequest($request);
            if ($formcom->isSubmitted() && $formcom->isValid()) {
                $commentaire->setprojet($project);
                $commentaire->setUtilisateur($this->getUser());
                $em->persist($commentaire);
                $em->flush();
                return $this->redirect($this->generateUrl('banana_station_core_project', ['slug' => $slug]));
            }

        } else {
            if ($request->get('plusme') != null || $request->get('moinsme') != null) {
                $alert->build(Alert::TYPE_BAD, 'Vous devez être connecté pour plusmer ou moinsmer un projet.');
            }
        }

        // Gestion de la note
        if ($this->get('security.authorization_checker')->isGranted('ROLE_CORE')) {
            $formnote->handleRequest($request);
            if ($formnote->isSubmitted() && $formnote->isValid()) {
                $note->setProjet($project);
                $note->setUtilisateur($utilisateur);
                $em->persist($note);
                $em->flush();
                return $this->redirect($this->generateUrl('banana_station_core_project', ['slug' => $slug]));
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

        return $this->render('BananaStationCoreBundle::project.html.twig', [
            'projet' => $project,
            'plusmes' => $plusmes,
            'moinsmes' => $moinsmes,
            'formcom' => $formcom->createView(),
            'formnote' => $formnote->createView(),
            'alert' => $alert
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public
    function projectsAction() {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('BananaStationCoreBundle:Projet')->findBy([], ['id' => 'DESC']);
        return $this->render('BananaStationCoreBundle::projects.html.twig', ['projets' => $projects]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $idcom
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public
    function editCommentaireAction(Request $request, $id, $idcom) {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') == false) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository('BananaStationCoreBundle:Projet')->findOneById($id);
        $commentaire = $em->getRepository('BananaStationCoreBundle:Commentaire')->findOneById($idcom);
        $user = $this->getUser();
        if ($projet == null || $commentaire == null) {
            throw new NotFoundHttpException();
        }
        if ($user->getId() != $commentaire->getUtilisateur()->getId()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', ['slug' => $commentaire->getProjet()->getSlug()]));
        }
        return $this->render('BananaStationCoreBundle::formcommentaire.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $idcom
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public
    function deleteCommentaireAction(Request $request, $id, $idcom) {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository('BananaStationCoreBundle:Projet')->findOneById($id);
        $commentaire = $em->getRepository('BananaStationCoreBundle:Commentaire')->findOneById($idcom);
        $user = $this->getUser();
        if ($projet == null || $commentaire == null) {
            throw new NotFoundHttpException();
        }
        if ($user->getId() != $commentaire->getUtilisateur()->getId() &&
            !$this->get('security.authorization_checker')->isGranted('ROLE_CORE')
        ) {
            throw new AccessDeniedException();
        }
        if ($request->getMethod() == 'POST') {
            $slug = $commentaire->getProjet()->getSlug();
            $em->remove($commentaire);
            $em->flush();
            return $this->redirect($this->generateUrl('banana_station_core_project', ['slug' => $slug]));
        }
        return $this->render('BananaStationCoreBundle::deletecommentaire.html.twig', ['commentaire' => $commentaire]);
    }

}
