<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Form\RecoverType;
use App\Form\PasswordType;
use App\Service\Alert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"})
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository(Utilisateur::class);
        $alert = $this->get('user.alert');

        // Création du formulaire d'utilisateur
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($userRepo->findOneByUsername($user->getUsername()) === null) {
                    if ($userRepo->findOneByEmail($user->getEmail()) === null) {
                        $user->setSalt(md5(time()));

                        // Création du mot de passe
                        $factory = $this->get('security.encoder_factory');
                        $encoder = $factory->getEncoder($user);
                        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                        $user->setPassword($password);
                        $em->persist($user);
                        $em->flush();

                        $mailer = $this->get('user.mailer');
                        $mailer->sendInscription($user);

                        return $this->redirect($this->generateUrl('user_success', ['type' => 'register']));
                    }
                    $alert->build(Alert::TYPE_BAD, 'Cette adresse email est déjà utilisée. Veuillez en renseigner une autre.');
                } else {
                    $alert->build(Alert::TYPE_BAD, 'Ce nom d\'utilisateur est déjà utilisé. Veuillez en choisir un autre.');
                }
            } else {
                $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');
            }
        }
        return $this->render('user/register.html.twig', ['form' => $form->createView(), 'alert' => $alert]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/forgetpassword", name="user_forgetpassword")
     */
    public function forgetPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $alert = $this->get('user.alert');

        // Création du formulaire
        $form = $this->createForm(RecoverType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository(Utilisateur::class)->findOneByEmail($form->get('email')->getData());
            if ($user !== null) {
                $user->setToken(md5(time()));
                $em->flush();

                $mailer = $this->get('user.mailer');
                $mailer->sendRecoverPassword($user);

                return $this->redirect($this->generateUrl('user_success', ['type' => 'recover']));
            }
            $alert->build(Alert::TYPE_BAD, 'Cette adresse email ne correspond à aucun utilisateur. Veuillez en renseigner une autre.');
        } else {
            $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');
        }

        return $this->render('user/forgetpassword.html.twig', [
            'form' => $form->createView(),
            'alert' => $alert
        ]);
    }

    /**
     * @param Request $request
     * @param $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/forgetpassword/{token}", name="tron_racine", requirements={"token"="\w+"})
     */
    public function forgetPasswordTokenAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $alert = $this->get('user.alert');
        $user = $em->getRepository(Utilisateur::class)->findOneByToken($token);

        if ($token === '0' || $user === null) {
            throw new NotFoundHttpException();
        }

        // Création du formulaire
        $form = $this->createForm(PasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken('0');
            $user->setSalt(md5(time()));

            // Création du mot de passe
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
            $user->setPassword($password);
            $em->flush();

            return $this->redirect($this->generateUrl('user_success', ['type' => 'recovered']));
        }
        $alert->build(Alert::TYPE_BAD, 'Veuillez remplir les champs correctement.');

        return $this->render('user/forgetpasswordtoken.html.twig', [
            'form' => $form->createView(),
            'alert' => $alert
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/login", name="login")
     */
    public function loginAction(): Response
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $alert = $this->get('user.alert');

        $lastUsername = $authenticationUtils->getLastUsername();

        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $alert->build(Alert::TYPE_BAD, 'Combinaison identifiant / mot de passe incorrecte.');
        }

        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'alert' => $alert
        ]);
    }

    /**
     * @return Response
     *
     * @Route("/cgu", name="user_cgu")
     */
    public function cguAction(): Response
    {
        return $this->render('user/cgu.html.twig');
    }

    /**
     * @param $type
     * @return Response
     *
     * @Route("/success/{type}", name="user_success")
     */
    public function successAction($type): Response
    {
        switch ($type) {
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
        return $this->render('user/success.html.twig', ['title' => $title, 'message' => $message]);
    }
}
