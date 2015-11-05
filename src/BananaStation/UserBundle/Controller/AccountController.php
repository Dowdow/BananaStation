<?php

namespace BananaStation\UserBundle\Controller;

use BananaStation\UserBundle\Service\Alert;
use BananaStation\UserBundle\Form\ChangeEmailType;
use BananaStation\UserBundle\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AccountController extends Controller {

    public function accountAction() {
        $user = $this->get('security.context')->getToken()->getUser();
        $request = $this->get('request');
        $alert = $this->get('banana_station_user.alert');

        $formPass = $this->createForm(new ChangePasswordType());
        $formEmail = $this->createForm(new ChangeEmailType());
        if($request->getMethod() == 'POST') {
            if($request->request->get('password')) {
                $formPass->handleRequest($request);
                if($formPass->isValid()){

                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($formPass->get('apassword')->getData(), $user->getSalt());

                    if($user->getPassword() === $password) {
                        $user->setSalt(md5(time()));
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($user);
                        $password = $encoder->encodePassword($formPass->get('npassword')->getData(), $user->getSalt());
                        $user->setPassword($password);
                        $this->getDoctrine()->getManager()->flush();
                        return $this->redirect($this->generateUrl('banana_station_user_success', array('type' => 'password')));
                    } else {
                        $alert->build(Alert::TYPE_BAD,'Votre mot de passe actuel est incorrect.');
                    }
                } else {
                    $alert->build(Alert::TYPE_BAD,'Veuillez remplir les champs correctement.');
                }
            }

            if($request->request->get('email')) {
                $formEmail->handleRequest($request);
                if($formEmail->isValid()) {
                    $email = $formEmail->get('aemail')->getData();
                    if($user->getEmail() === $email) {
                        $user->setEmail($formEmail->get('nemail')->getData());
                        $this->getDoctrine()->getManager()->flush();
                        return $this->redirect($this->generateUrl('banana_station_user_success', array('type' => 'mail')));
                    } else {
                        $alert->build(Alert::TYPE_BAD,'Votre email actuel est incorrect.');
                    }
                } else {
                    $alert->build(Alert::TYPE_BAD,'Veuillez remplir les champs correctement.');
                }
            }
        }

        if($request->request->get('delete')) {
            $security = $this->get('security.context');
            if(!$security->isGranted('ROLE_ADMIN') && !$security->isGranted('ROLE_CORE') && !$security->isGranted('ROLE_MUSIC')) {
                if($request->request->get('erase') == 'EFFACER') {
                    $this->getDoctrine()->getManager()->remove($user);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->redirect($this->generateUrl('logout'));
                } else {
                    $alert->build(Alert::TYPE_BAD,'Vous devez renseigner le mot clé \'EFFACER\' pour supprimer votre compte.');
                }
            } else {
                $alert->build(Alert::TYPE_BAD,'Un compte administrateur ne peut être supprimé.');
            }
        }

        return $this->render('BananaStationUserBundle::account.html.twig',
            array(
                'alert' => $alert,
                'formPass' => $formPass->createView(),
                'formEmail' => $formEmail->createView()
            ));
    }

} 