<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Commentaire;
use App\Entity\Note;
use App\Entity\Projet;
use App\Form\CommentaireType;
use App\Form\NoteType;
use App\Service\Alert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CoreProjectController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_core%")
 */
class CoreProjectController extends Controller
{
    /**
     * @param Request $request
     * @param Alert $alert
     * @param $slug
     *
     * @return Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Form\Exception\LogicException
     *
     * @Route("/project/{slug}", name="core_project")
     */
    public function project(Request $request, Alert $alert, $slug): Response
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository(Projet::class)->findOneBySlug($slug);
        if ($project === null) {
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
            if ($request->get('plusme') !== null) {
                $pouce = 'P';
            } elseif ($request->get('moinsme') !== null) {
                $pouce = 'M';
            }
            if (isset($pouce)) {
                $avis = $em->getRepository(Avis::class)->findOneByUtilisateurProjet($utilisateur, $project);
                if ($avis === null) {
                    $avis = new Avis();
                    $avis->setPouce($pouce);
                    $avis->setProjet($project);
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
                $commentaire->setProjet($project);
                $commentaire->setUtilisateur($this->getUser());
                $em->persist($commentaire);
                $em->flush();
                return $this->redirect($this->generateUrl('core_project', ['slug' => $slug]));
            }

        } else {
            if ($request->get('plusme') !== null || $request->get('moinsme') !== null) {
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
                return $this->redirect($this->generateUrl('core_project', ['slug' => $slug]));
            }
        }

        $plusmes = 0;
        $moinsmes = 0;
        if ($project != null) {
            foreach ($project->getAvis() as $value) {
                if ($value->getPouce() === 'P') {
                    $plusmes++;
                } else {
                    $moinsmes++;
                }
            }
        }

        return $this->render('core/project.html.twig', [
            'projet' => $project,
            'plusmes' => $plusmes,
            'moinsmes' => $moinsmes,
            'formcom' => $formcom->createView(),
            'formnote' => $formnote->createView(),
            'alert' => $alert
        ]);
    }

    /**
     * @return Response
     * @throws \LogicException
     * @throws \UnexpectedValueException
     *
     * @Route("/projects", name="core_projects")
     */
    public function projects(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository(Projet::class)->findBy([], ['id' => 'DESC']);
        return $this->render('core/projects.html.twig', ['projets' => $projects]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $idcom
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\Form\Exception\LogicException
     *
     * @Route("/project/{id}/commentaire/edit/{idcom}", name="core_project_edit_commentaire", requirements={"id"="\d+", "idcom"="\d+"})
     */
    public function editCommentaire(Request $request, $id, $idcom)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER') === false) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projet::class)->findOneById($id);
        $commentaire = $em->getRepository(Commentaire::class)->findOneById($idcom);
        $user = $this->getUser();
        if ($projet === null || $commentaire === null) {
            throw new NotFoundHttpException();
        }
        if ($user->getId() !== $commentaire->getUtilisateur()->getId()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('core_project', ['slug' => $commentaire->getProjet()->getSlug()]));
        }
        return $this->render('core/formcommentaire.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $idcom
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @Route("/project/{id}/commentaire/delete/{idcom}", name="core_project_delete_commentaire", requirements={"id"="\d+", "idcom"="\d+"})
     */
    public function deleteCommentaire(Request $request, $id, $idcom)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projet::class)->findOneById($id);
        $commentaire = $em->getRepository(Commentaire::class)->findOneById($idcom);
        $user = $this->getUser();
        if ($projet == null || $commentaire == null) {
            throw new NotFoundHttpException();
        }
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_CORE')
            && $user->getId() !== $commentaire->getUtilisateur()->getId()
        ) {
            throw new AccessDeniedException();
        }
        if ($request->getMethod() === 'POST') {
            $slug = $commentaire->getProjet()->getSlug();
            $em->remove($commentaire);
            $em->flush();
            return $this->redirect($this->generateUrl('core_project', ['slug' => $slug]));
        }
        return $this->render('core/deletecommentaire.html.twig', ['commentaire' => $commentaire]);
    }

}
