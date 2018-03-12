<?php

namespace App\Controller;

use App\Service\Mailer;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @param UserPasswordEncoderInterface $encoder
     * @param Alert $alert
     * @param Mailer $mailer
     * @return Response
     *
     * @Route("/register", name="user_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, Alert $alert, Mailer $mailer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository(Utilisateur::class);

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
                        $password = $encoder->encodePassword($user, $user->getPassword());
                        $user->setPassword($password);
                        $em->persist($user);
                        $em->flush();

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
     * @param Alert $alert
     * @param Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/forgetpassword", name="user_forgetpassword")
     */
    public function forgetPassword(Request $request, Alert $alert, Mailer $mailer)
    {
        $em = $this->getDoctrine()->getManager();

        // Création du formulaire
        $form = $this->createForm(RecoverType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository(Utilisateur::class)->findOneByEmail($form->get('email')->getData());
            if ($user !== null) {
                $user->setToken(md5(time()));
                $em->flush();

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
     * @param UserPasswordEncoderInterface $encoder
     * @param Alert $alert
     * @param $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *
     * @Route("/forgetpassword/{token}", name="user_forgetpassword_token", requirements={"token"="\w+"})
     */
    public function forgetPasswordToken(Request $request, UserPasswordEncoderInterface $encoder, Alert $alert, $token)
    {
        $em = $this->getDoctrine()->getManager();
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
            $password = $encoder->encodePassword($user, $form->get('password')->getData());
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
     * @param Alert $alert
     * @return Response
     *
     * @Route("/login", name="login")
     */
    public function login(Alert $alert): Response
    {
        $authenticationUtils = $this->get('security.authentication_utils');
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
    public function cgu(): Response
    {
        return $this->render('user/cgu.html.twig');
    }

    /**
     * @param $type
     *
     * @return Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @Route("/success/{type}", name="user_success")
     */
    public function success($type): Response
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
