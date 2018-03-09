<?php

namespace App\Controller;

use App\Service\Alert;
use App\Form\ChangeEmailType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/account", name="user_account")
     */
    public function accountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $alert = $this->get('user.alert');

        $formPass = $this->createForm(ChangePasswordType::class);
        $formEmail = $this->createForm(ChangeEmailType::class);
        if ($request->request->get('password')) {
            $formPass->handleRequest($request);
            if ($formPass->isSubmitted() && $formPass->isValid()) {

                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($formPass->get('apassword')->getData(), $user->getSalt());

                if ($user->getPassword() === $password) {
                    $user->setSalt(md5(time()));
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($formPass->get('npassword')->getData(), $user->getSalt());
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
                    $request->getSession()->invalidate();

                    return $this->redirect($this->generateUrl('core_racine'));
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