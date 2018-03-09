<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotifierController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_notifier%")
 */
class NotifierController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/", name="notifier_racine")
     */
    public function indexAction(): Response
    {
        return $this->render('notifier/index.html.twig');
    }
}
