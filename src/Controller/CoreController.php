<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CoreController
 * @package App\Controller
 *
 * @Route(schemes={"%protocol%"}, host="%host_core%")
 */
class CoreController extends Controller
{
    /**
     * @return Response
     *
     * @Route("/", name="core_racine")
     */
    public function homeAction(): Response
    {
        return $this->render('core/home.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/about", name="core_about")
     */
    public function aboutAction(): Response
    {
        return $this->render('core/about.html.twig');
    }
}