<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Projet;
use App\Form\NoteType;
use App\Form\ProjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CoreAdminController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_core%")
 */
class CoreAdminController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/admin", name="core_admin")
     */
    public function adminAction(): Response
    {
        return $this->render('core/admin.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/admin/project/add", name="core_admin_project_add")
     */
    public function projectAddAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $project = new Projet();
        $form = $this->createForm(ProjetType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setSlug($this->get('core.slugger')->slugify($project->getNom()));
            $project->setUtilisateur($user);
            $em->persist($project);
            $em->flush();
            return $this->redirect($this->generateUrl('core_project', ['slug' => $project->getSlug()]));
        }


        return $this->render('core/formproject.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/admin/project/edit/{id}", name="core_admin_project_edit", requirements={"id"="\d+"})
     */
    public function projectEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository(Projet::class)->findOneById($id);
        if ($project === null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ProjetType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setSlug($this->get('core.slugger')->slugify($project->getNom()));
            $em->flush();
            return $this->redirect($this->generateUrl('core_project', ['slug' => $project->getSlug()]));
        }

        return $this->render('core/formproject.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/admin/project/delete/{id}", name="core_admin_project_delete", requirements={"id"="\d+"})
     */
    public function projectDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository(Projet::class)->findOneById($id);
        if ($project === null) {
            throw new NotFoundHttpException();
        }

        if ($request->getMethod() === 'POST') {
            $em->remove($project);
            $em->flush();
            return $this->redirect($this->generateUrl('core_projects', ['id' => 1]));
        }

        return $this->render('core/deleteproject.html.twig', ['project' => $project]);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/admin/note/edit/{id}", name="core_admin_note_edit", requirements={"id"="\d+"})
     */
    public function noteEditAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository(Note::class)->findOneById($id);
        if ($note === null) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(NoteType::class, $note);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('core_project', ['id' => $note->getProjet()->getId()]));
        }

        return $this->render('core/formnote.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/admin/note/delete/{id}", name="core_admin_note_delete", requirements={"id"="\d+"})
     */
    public function noteDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository(Note::class)->findOneById($id);
        if ($note === null) {
            throw new NotFoundHttpException();
        }

        if ($request->getMethod() === 'POST') {
            $projetid = $note->getProjet()->getId();
            $em->remove($note);
            $em->flush();
            return $this->redirect($this->generateUrl('core_project', ['id' => $projetid]));
        }

        return $this->render('core/deletenote.html.twig', ['note' => $note]);
    }
}
