<?php

namespace App\Controller;

use App\Service\Alert;
use App\Form\ChangeEmailType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserAccountController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"})
 */
class UserAccountController extends Controller
{
    /**
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param Alert $alert
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/account", name="user_account")
     * @throws \Symfony\Component\Form\Exception\LogicException
     * @throws \OutOfBoundsException
     */
    public function account(Request $request, UserPasswordEncoderInterface $encoder, Alert $alert)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $formPass = $this->createForm(ChangePasswordType::class);
        $formEmail = $this->createForm(ChangeEmailType::class);
        if ($request->request->get('password')) {
            $formPass->handleRequest($request);
            if ($formPass->isSubmitted() && $formPass->isValid()) {
                $password = $encoder->encodePassword($user, $formPass->get('apassword')->getData());

                if ($user->getPassword() === $password) {
                    $user->setSalt(md5(time()));
                    $password = $encoder->encodePassword($user, $formPass->get('npassword')->getData());
                    $user->setPassword($password);
                    $em->flush();
                    return $this->redirect($this->generateUrl('user_success', ['type' => 'password']));
                }
                $alert->build(Alert::TYPE_BAD, 'Votre mot de passe actuel est incorrect.');
            } else {
                $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');
            }
        }

        if ($request->request->get('email')) {
            $formEmail->handleRequest($request);
            if ($formEmail->isSubmitted() && $formEmail->isValid()) {
                $email = $formEmail->get('aemail')->getData();
                if ($user->getEmail() === $email) {
                    $user->setEmail($formEmail->get('nemail')->getData());
                    $em->flush();
                    return $this->redirect($this->generateUrl('user_success', ['type' => 'mail']));
                }
                $alert->build(Alert::TYPE_BAD, 'Votre email actuel est incorrect.');
            } else {
                $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');
            }
        }

        if ($request->request->get('delete')) {
            $security = $this->get('security.authorization_checker');
            if (!$security->isGranted('ROLE_ADMIN') && !$security->isGranted('ROLE_CORE') && !$security->isGranted('ROLE_MUSIC')) {
                if ($request->request->get('erase') === 'EFFACER') {
                    $em->remove($user);
                    $em->flush();

                    $this->get('security.token_storage')->setToken(null);
                    $session = $request->getSession();
                    if ($session) {
                        $session->invalidate();
                    }

                    return $this->redirect($this->generateUrl('core_index'));
                }
                $alert->build(Alert::TYPE_BAD, 'Vous devez renseigner le mot clé \'EFFACER\' pour supprimer votre compte.');
            } else {
                $alert->build(Alert::TYPE_BAD, 'Un compte administrateur ne peut être supprimé.');
            }
        }

        return $this->render('user/account.html.twig',
            [
                'alert' => $alert,
                'formPass' => $formPass->createView(),
                'formEmail' => $formEmail->createView()
            ]);
    }
} 