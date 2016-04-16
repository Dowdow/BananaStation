<?php

namespace BananaStation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BananaStation\UserBundle\Entity\Utilisateur;
use BananaStation\UserBundle\Form\UtilisateurType;
use BananaStation\UserBundle\Form\RecoverType;
use BananaStation\UserBundle\Form\PasswordType;
use BananaStation\UserBundle\Service\Alert;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller {

    public function registerAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('BananaStationUserBundle:Utilisateur');
        $alert = NULL;

        // Création du formulaire d'utilisateur
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $alert = $this->get('banana_station_user.alert');

            if ($form->isValid()) {
                if (NULL == $userRepo->findOneByUsername($user->getUsername())) {
                    if (NULL == $userRepo->findOneByEmail($user->getEmail())) {
                        $user->setSalt(md5(time()));

                        // Création du mot de passe
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($user);
                        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                        $user->setPassword($password);
                        $em->persist($user);
                        $em->flush();

                        $mailer = $this->get('banana_station_user.mailer');
                        $mailer->sendInscription($user);

                        return $this->redirect($this->generateUrl('banana_station_user_success', array('type' => 'register')));
                    } else {
                        $alert->build(Alert::TYPE_BAD, 'Cette adresse email est déjà utilisée. Veuillez en renseigner une autre.');
                    }
                } else {
                    $alert->build(Alert::TYPE_BAD, 'Ce nom d\'utilisateur est déjà utilisé. Veuillez en choisir un autre.');
                }
            } else {
                $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');
            }
        }

        return $this->render('BananaStationUserBundle::register.html.twig', array('form' => $form->createView(), 'alert' => $alert));
    }

    public function forgetPasswordAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('BananaStationUserBundle:Utilisateur');
        $alert = NULL;

        // Création du formulaire
        $form = $this->createForm(RecoverType::class);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $alert = $this->get('banana_station_user.alert');

            if ($form->isValid()) {
                $user = $userRepo->findOneByEmail($form->get('email')->getData());
                if (NULL != $user) {
                    $user->setToken(md5(time()));
                    $em->flush();

                    $mailer = $this->get('banana_station_user.mailer');
                    $mailer->sendRecoverPassword($user);

                    return $this->redirect($this->generateUrl('banana_station_user_success', array('type' => 'recover')));
                } else {
                    $alert->build(Alert::TYPE_BAD, 'Cette adresse email ne correspond à aucun utilisateur. Veuillez en renseigner une autre.');
                }
            } else {
                $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');
            }
        }

        return $this->render('BananaStationUserBundle::forgetpassword.html.twig', array('form' => $form->createView(), 'alert' => $alert));
    }

    public function forgetPasswordTokenAction(Request $request, $token) {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('BananaStationUserBundle:Utilisateur');
        $alert = NULL;

        if ($token == '0' || NULL == $userRepo->findOneByToken($token)) {
            throw new NotFoundHttpException();
        }

        // Création du formulaire
        $form = $this->createForm(PasswordType::class);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $alert = $this->get('banana_station_user.alert');

            if ($form->isValid()) {
                $user = $userRepo->findOneByToken($token);
                if (NULL != $user) {
                    $user->setToken('0');
                    $user->setSalt(md5(time()));

                    // Création du mot de passe
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
                    $user->setPassword($password);

                    $em->flush();

                    return $this->redirect($this->generateUrl('banana_station_user_success', array('type' => 'recovered')));
                } else {
                    $alert->build(Alert::TYPE_BAD, 'Une erreur est survenue. Veuillez recommencer la procédure de récupération de mot de passe.');
                }
            } else {
                $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');
            }
        }

        return $this->render('BananaStationUserBundle::forgetpasswordtoken.html.twig', array('form' => $form->createView(), 'alert' => $alert));
    }

    public function loginAction(Request $request) {
        return $this->render('BananaStationUserBundle::login.html.twig', array(
            'username' =>  $this->get('security.authentication_utils')->getLastUsername()
        ));
    }

    public function cguAction(){
        return $this->render('BananaStationUserBundle::cgu.html.twig');
    }

    public function successAction($type) {
        switch($type) {
            case 'register':
                $title = 'Inscription effectuée';
                $message = 'Vous venez de vous insrire. Vous pouvez dès à présent vous connecter.';
                break;
            case 'recover':
                $title = 'Récupération du mot de passe (étape 1/2)';
                $message = 'Vous venez de faire une demande de récupération de mot de passe. Un mail vous a été envoyé. Veuillez y suivre les instructions.';
                break;
            case 'recovered':
                $title = 'Récupération du mot de passe (étape 2/2)';
                $message = 'Votre mot de passe a bien été modifié. Vous pouvez dès à présent vous connecter.';
                break;
            case 'mail':
                $title = 'Modification de l\'email';
                $message = 'Votre email vient d\'être modifié avec succès.';
                break;
            case 'password':
                $title = 'Modification du mot de passe.';
                $message = 'Votre mot de passe vient d\'être modifié avec succès.';
                break;
            default:
                throw new NotFoundHttpException();
                break;
        }
        return $this->render('BananaStationUserBundle::success.html.twig', array('title' => $title, 'message' => $message));
    }
}
